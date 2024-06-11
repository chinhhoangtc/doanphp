<?php
include "../cnf/connect.php";

// get class_id from session
session_start();
if (isset($_SESSION['class_id'])) {
    $class_id = $_SESSION['class_id'];
} else {
    echo "No class ID is set in session.";
    exit;
}

$attendance_id = isset($_GET['attendance_id']) ? $_GET['attendance_id'] : '';

// Truy vấn dữ liệu từ bảng student_class và _user
$stmt = $pdo->prepare("SELECT DISTINCT student_class.student_id, _user.fullname, _user.date, _user.class 
                       FROM student_class 
                       JOIN _user ON student_class.student_id = _user._user_id
                       WHERE student_class.class_id = :class_id");
$stmt->execute(['class_id' => $class_id]);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Truy vấn dữ liệu điểm danh từ bảng attendance_records
$stmt = $pdo->prepare("SELECT * FROM attendance_records WHERE attendance_id = :attendance_id");
$stmt->execute(['attendance_id' => $attendance_id]);
$attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tạo một mảng để dễ dàng truy cập dữ liệu điểm danh theo student_id
$attendance_data = [];
foreach ($attendance_records as $record) {
    $attendance_data[$record['student_id']] = $record;
}

// Kiểm tra xem điểm danh đã bị nộp chưa
$stmt = $pdo->prepare("SELECT is_submit FROM attendance_list WHERE attendance_id = :attendance_id");
$stmt->execute(['attendance_id' => $attendance_id]);
$is_submit = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách lớp học</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
body {
    background-color: #E7ECF0 !important;
}

/* Custom styles for larger checkboxes and full-width input */
input[type="checkbox"] {
    transform: scale(1.5);
    margin: 10px;
}

.form-control {
    width: 100%;
}

.box-ddlh {
    box-shadow: 0px 0px 5px 1px #d5d5d5;
}
</style>

<body>
    <?php include './header.php'; ?>
    <div class="container my-3 border rounded-2 box-ddlh p-3" style="background-color: white;">
        <p style="font-size: 18px; color:#667580" class="fw-bold border-bottom pb-3">Điểm danh sinh viên lớp học</p>
        <form method="POST" action="save_attendance.php">
            <input type="hidden" name="attendance_id" value="<?php echo $attendance_id; ?>">
            Phiếu điểm danh <strong><?php echo $attendance_id; ?></strong>
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">MSSV</th>
                        <th scope="col">Họ tên</th>
                        <th scope="col">Ngày sinh</th>
                        <th scope="col">Lớp</th>
                        <th scope="col">Vắng</th>
                        <th scope="col">Trễ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($classes): ?>
                    <?php foreach($classes as $sc): ?>
                    <tr>
                        <td><?php echo $sc['student_id']; ?></td>
                        <td><?php echo $sc['fullname']; ?></td>
                        <td><?php echo $sc['date']; ?></td>
                        <td><?php echo $sc['class']; ?></td>
                        <td>
                            <input type="checkbox" name="absent[<?php echo $sc['student_id']; ?>]" <?php 
                                echo (isset($attendance_data[$sc['student_id']]) && $attendance_data[$sc['student_id']]['absent']) ? 'checked' : ''; 
                                echo $is_submit ? ' disabled' : '';
                            ?>>
                        </td>
                        <td>
                            <input type="text" name="late[<?php echo $sc['student_id']; ?>]" class="form-control"
                                value="<?php echo isset($attendance_data[$sc['student_id']]) ? $attendance_data[$sc['student_id']]['late'] : ''; ?>"
                                <?php echo $is_submit ? ' disabled' : ''; ?>>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Không có sinh viên nào trong lớp này.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="">
                <a href="attendance_sv.php"><button class="btn btn-primary" type="button">Quay lại</button></a>
            </div>
        </form>
    </div>

</body>

</html>
