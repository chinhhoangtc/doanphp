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

// Truy vấn cơ sở dữ liệu để lấy dữ liệu từ bảng attendance_list dựa trên class_id và is_submit = 1
$stmt = $pdo->prepare("SELECT * FROM attendance_list WHERE class_id = :class_id AND is_submit = 1");
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
                <tr data-href="./ddstudent_sv.php?attendance_id=<?php echo $attendance['attendance_id']; ?>">
                    <td><?php echo $attendance['class_id']; ?></td>
                    <td><?php echo $attendance['attendance_id']; ?></td>
                    <td><?php echo $attendance['create_at']; ?></td>
                    <td><?php echo $attendance['is_submit']; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="class_list_sv.php"><button class="btn btn-primary" type="button">Quay lại</button></a>
    </div>
</body>
</html>
