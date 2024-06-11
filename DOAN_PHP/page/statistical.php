<?php
// Kết nối đến cơ sở dữ liệu
include "../cnf/connect.php";

// get class_id
session_start();
if (isset($_SESSION['class_id'])) {
    $class_id = $_SESSION['class_id'];
} else {
    echo "No class ID is set in session.";
    exit();
}

// Truy vấn để lấy danh sách các ngày điểm danh đã nộp
try {
    $dateStmt = $pdo->prepare("
        SELECT DISTINCT create_at 
        FROM attendance_list 
        WHERE class_id = :class_id AND is_submit = 1
    ");
    $dateStmt->execute(['class_id' => $class_id]);
    $dates = $dateStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo 'Lỗi: ' . $e->getMessage();
    exit;
}

// Kiểm tra nếu ngày đã được chọn
$selectedDate = isset($_POST['selected_date']) ? $_POST['selected_date'] : null;

// Truy vấn cơ sở dữ liệu để lấy dữ liệu cần thiết dựa trên class_id và selected_date
try {
    if ($selectedDate) {
        $stmt = $pdo->prepare("
            SELECT 
                _user.fullname,
                _user.class AS lop,
                attendance_list.create_at AS ngay,
                IFNULL(attendance_records.absent, 0) AS absent,
                IFNULL(attendance_records.late, '') AS late
            FROM 
                student_class
            JOIN 
                _user ON student_class.student_id = _user._user_id
            JOIN 
                attendance_list ON student_class.class_id = attendance_list.class_id
            LEFT JOIN 
                attendance_records ON attendance_list.attendance_id = attendance_records.attendance_id 
                AND attendance_records.student_id = _user._user_id
            WHERE 
                student_class.class_id = :class_id
            AND
                attendance_list.create_at = :selected_date
            GROUP BY 
                _user._user_id
        ");
        $stmt->execute(['class_id' => $class_id, 'selected_date' => $selectedDate]);
        $attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $attendances = [];
    }
} catch (Exception $e) {
    echo 'Lỗi: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê danh sách điểm danh</title>
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

    .box-dd {
        box-shadow: 0px 0px 5px 1px #d5d5d5;
    }
    </style>
</head>

<body>
    <?php include "header.php" ?>
    <div class="container my-3 border p-3 rounded-2 box-dd" style="background-color: white;">
        <div class="d-flex">
            <p style="font-size: 18px; color:#667580" class="fw-bold border-bottom pb-3">Thống kê danh sách điểm danh lớp <?php echo htmlspecialchars($class_id); ?></p>
        </div>
        
        <!-- Form để chọn ngày điểm danh -->
        <form method="post" action="">
            <label for="selected_date">Chọn ngày điểm danh:</label>
            <select name="selected_date" id="selected_date" onchange="this.form.submit()">
                <option value="">Chọn ngày</option>
                <?php foreach ($dates as $date): ?>
                    <option value="<?php echo htmlspecialchars($date['create_at']); ?>" <?php if ($selectedDate == $date['create_at']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($date['create_at']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Lớp</th>
                    <th>Họ tên</th>
                    <th>Ngày</th>
                    <th>Vắng</th>
                    <th>Trễ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($attendances)): ?>
                <tr>
                    <td colspan="6" class="text-center">Chưa có phiếu điểm danh</td>
                </tr>
                <?php else: ?>
                <?php foreach ($attendances as $index => $attendance): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($attendance['lop']); ?></td>
                    <td><?php echo htmlspecialchars($attendance['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($attendance['ngay']); ?></td>
                    <td><?php echo $attendance['absent'] ? 'X' : ''; ?></td>
                    <td><?php echo htmlspecialchars($attendance['late']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex mt-3 gap-3">
            <a href="attendance.php"><button class="btn btn-primary" type="button">Quay lại</button></a>
        </div>
    </div>
</body>

</html>
