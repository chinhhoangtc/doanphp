<?php
include "../cnf/connect.php";
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}

// Lấy _user_id của người dùng từ cơ sở dữ liệu
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT _user_id FROM _user WHERE username=:username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Không tìm thấy thông tin sinh viên.";
    exit;
}

$_user_id = $user['_user_id'];

// Lấy danh sách các lớp mà sinh viên đang học, kết hợp với thông tin môn học
$stmt = $pdo->prepare("
    SELECT c.class_id, c.class_name, s.subject_name
    FROM class c
    JOIN course co ON c.course_id = co.course_id
    JOIN subject s ON co.subject_id = s.subject_id
    JOIN student_class sc ON c.class_id = sc.class_id
    WHERE sc.student_id = :student_id
");
$stmt->execute(['student_id' => $_user_id]);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách các lớp</title>
</head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

body {
    font-family: 'Roboto', sans-serif;
    background-color: #E7ECF0 !important;
}

.box-ds {
    box-shadow: 0px 0px 5px 1px #d5d5d5;
}

.box-mh:hover {
    cursor: pointer;
}
</style>

<body>
    <?php include "./header.php" ?>
    <div class="container border my-3 p-3 rounded-2 box-ds" style="background-color: white;">
        <p style="font-size: 18px; color:#667580" class="fw-bold border-bottom pb-3">Danh sách các lớp</p>
        <div class="d-flex flex-column gap-3 ">
            <?php foreach ($classes as $class) { ?>
            <div class="border p-3 clickable rounded-2 box-mh" style="background-color: #E0FBFF; color:blue"
                onclick="goToAttendance('<?php echo $class['class_id'];?>')">
                <p><?php echo $class['subject_name']; ?></p>
                <p><?php echo $class['class_name']; ?> - <?php echo $class['class_id']; ?></p>
            </div>
            <?php } ?>
        </div>
        <a href="login.php"><button class=" mt-2 btn btn-primary" type="button">Quay lại</button></a>
    </div>

</body>
<script>
function goToAttendance(classId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "set_session.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            window.location.href = 'attendance_sv.php';
        }
    };
    xhr.send(`class_id=${classId}`);
}
</script>

</html>