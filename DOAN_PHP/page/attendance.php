<?php
// Kết nối đến cơ sở dữ liệu
include "../cnf/connect.php";

// Bắt đầu session
session_start();

// Lấy class_id từ session
if (isset($_SESSION['class_id'])) {
    $class_id = $_SESSION['class_id'];
} else {
    echo "No class ID is set in session.";
    exit; // Dừng thực thi nếu không có class_id trong session
}

// Hàm tạo attendance_id mới
function generateAttendanceId($pdo) {
    // Lấy giá trị attendance_id lớn nhất hiện tại
    $stmt = $pdo->query("SELECT MAX(attendance_id) AS max_id FROM attendance_list");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['max_id']) {
        // Tách phần số từ attendance_id hiện tại
        $maxId = (int)substr($row['max_id'], 3);
        // Tăng phần số lên 1
        $newId = $maxId + 1;
        // Tạo attendance_id mới với định dạng "att" + số
        return 'att' . str_pad($newId, 3, '0', STR_PAD_LEFT);
    } else {
        // Nếu không có attendance_id hiện tại, bắt đầu từ "att001"
        return 'att001';
    }
}

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Bắt đầu transaction
    $pdo->beginTransaction();

    try {
        // Tạo mới attendance_id
        $attendance_id = generateAttendanceId($pdo);

        // Tạo thời gian hiện tại
        $create_at = date('Y-m-d H:i:s');

        // Thêm bản ghi mới vào bảng attendance_list
        $stmt = $pdo->prepare("INSERT INTO attendance_list (attendance_id, class_id, create_at, is_submit) VALUES (:attendance_id, :class_id, :create_at, 0)");
        $stmt->execute(['attendance_id' => $attendance_id, 'class_id' => $class_id, 'create_at' => $create_at]);

        // Lấy danh sách sinh viên thuộc lớp hiện tại
        $stmt = $pdo->prepare("SELECT student_id FROM student_class WHERE class_id = :class_id");
        $stmt->execute(['class_id' => $class_id]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Thêm thông tin điểm danh của mỗi sinh viên vào bảng attendance_records
        $stmt = $pdo->prepare("
            INSERT INTO attendance_records (attendance_id, student_id, absent, late) 
            VALUES (:attendance_id, :student_id, 0, 0)
            ON DUPLICATE KEY UPDATE 
                absent = VALUES(absent), 
                late = VALUES(late)
        ");
        foreach ($students as $student) {
            $stmt->execute(['attendance_id' => $attendance_id, 'student_id' => $student['student_id']]);
        }

        // Commit transaction
        $pdo->commit();

        // Chuyển hướng về trang hiện tại để làm mới danh sách điểm danh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        // Rollback transaction nếu có lỗi
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}

// Truy vấn cơ sở dữ liệu để lấy dữ liệu từ bảng attendance_list dựa trên class_id
$stmt = $pdo->prepare("SELECT * FROM attendance_list WHERE class_id = :class_id");
$stmt->execute(['class_id' => $class_id]);
$attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách điểm danh</title>
    <style>
    body {
        background-color: #E7ECF0 !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr {
        cursor: pointer;
    }

    tbody tr:hover {
        background-color: #F2F2F2;
    }

    .box-dd {
        box-shadow: 0px 0px 5px 1px #d5d5d5;
    }
    </style>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var rows = document.querySelectorAll("table tbody tr");
        rows.forEach(function(row) {
            row.addEventListener("click", function() {
                var href = this.getAttribute("data-href");
                if (href) {
                    window.location.href = href;
                }
            });
        });
    });
    </script>
</head>

<body>
    <?php include "header.php" ?>
    <div class="container my-3 border p-3 rounded-2 box-dd" style="background-color: white;">
        <div class="d-flex">
            <p style="font-size: 18px; color:#667580" class="fw-bold border-bottom pb-3">Danh sách điểm danh</p>
            <a href="statistical.php" class="text-decoration-none">
                <p class="ms-5 border p-1 bg-light text-dark fw-bold">Thống kê danh sách điểm danh</p>
            </a>
        </div>
        <div class="w-100 d-flex justify-content-end pe-5 my-2">
            <form method="POST" action="">
                <button class="btn btn-primary" type="submit">Tạo mới</button>
            </form>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Mã lớp</th>
                    <th>Mã phiếu điểm danh</th>
                    <th>Tạo lúc</th>
                    <th>Điểm danh</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($attendances)): ?>
                <tr>
                    <td colspan="4" class="text-center">Chưa có phiếu điểm danh</td>
                </tr>
                <?php else: ?>
                <?php foreach ($attendances as $attendance): ?>
                <tr data-href="./ddstudent.php?attendance_id=<?php echo $attendance['attendance_id']; ?>">
                    <td><?php echo $attendance['class_id']; ?></td>
                    <td><?php echo $attendance['attendance_id']; ?></td>
                    <td><?php echo $attendance['create_at']; ?></td>
                    <td><?php echo $attendance['is_submit']; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="class_list.php"><button class="btn btn-primary" type="button">Quay lại</button></a>
    </div>
</body>
</html>
