<?php
session_start();
include '../cnf/connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header('location:login.php');
    exit;
}

// Lấy _user_id của người dùng từ cơ sở dữ liệu
$username = $_SESSION['username'];
$stmt = $pdo->prepare('SELECT u._user_id, d.dep_name 
                      FROM _user u
                      JOIN department d ON u.department_id = d.department_id
                      WHERE u.username = :username');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo 'Không tìm thấy thông tin sinh viên.';
    exit;
}

$_user_id = $user['_user_id'];
$dep_name = $user['dep_name'];

// Hiển thị toàn bộ thông tin của sinh viên dựa trên _user_id
$stmt = $pdo->prepare('SELECT * FROM _user WHERE _user_id = :user_id');
$stmt->execute(['user_id' => $_user_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo 'Không tìm thấy thông tin sinh viên.';
    exit;
}

// Lấy số lượng lớp sinh viên đang học từ bảng student_class
$class_stmt = $pdo->prepare('SELECT COUNT(class_id) as total_classes FROM student_class WHERE student_id = :user_id');
$class_stmt->execute(['user_id' => $_user_id]);
$class_info = $class_stmt->fetch(PDO::FETCH_ASSOC);
$total_classes = $class_info['total_classes'];
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Điểm danh sinh viên</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css'>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

    body {
        font-family: 'Roboto', sans-serif;
        background-color: #E7ECF0 !important;
    }

    .dsl:hover {
        cursor: pointer;
    }

    .dsl {
        background-color: #E0FBFF;
    }

    .box-info {
        box-shadow: 0px 0px 5px 1px #d5d5d5;
    }

    .avt-info img {
        overflow: hidden;
        object-fit: cover;
        width: 100px;
        height: 100px;
        border-radius: 50%;
    }

    .colors {
        color: #667580;
    }

    .fw-bold {
        font-weight: bold;
    }

    .info-title {
        font-size: 16px;
    }

    .info-list {
        font-size: 12px;
    }

    .box-info {
        background-color: white;
    }
    .box-info-2{
        box-shadow: 0px 0px 5px 1px #d5d5d5;
        background-color: #E0FBFF;
    }
    </style>
</head>

<body>
    <?php include "header.php" ?>
    <div class='container my-3'>
        <div class='row'>
            <div class='col-lg-6 col-12 mb-3 teacher-info'>
                <div class='border rounded-2 p-3 h-100 box-info'>
                    <h3 class="border-bottom fw-bolder pb-3 colors info-title">Thông tin sinh viên</h3>
                    <div class="d-flex">
                        <div class="avt-info text-center me-3">
                            <img src="../img/avt.png" class="mb-2" alt="">
                            <a href="infosv.php" class="text-center d-block">Xem chi tiết</a>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <ul class='list-unstyled info-list me-4'>
                                    <li class="colors py-1">MSSV: <span
                                            class="fw-bold"><?php echo $student['_user_id']; ?></span></li>
                                    <li class="colors py-1">Họ và tên: <span
                                            class="fw-bold"><?php echo $student['fullname']; ?></span></li>
                                    <li class="colors py-1">Ngày sinh: <span
                                            class="fw-bold"><?php echo $student['date']; ?></span></li>
                                    <li class="colors py-1">Nơi sinh: <span
                                            class="fw-bold"><?php echo $student['address']; ?></span></li>
                                </ul>
                                <ul class='list-unstyled info-list'>
                                    <li class="colors py-1">Lớp: <span
                                            class="fw-bold"><?php echo $student['class']; ?></span></li>
                                    <li class="colors py-1">Bậc đào tạo: <span class="fw-bold">Đại học</span></li>
                                    <li class="colors py-1">Loại hình đào tạo: <span class="fw-bold">Chính quy</span>
                                    </li>
                                    <li class="colors py-1">Ngành: <span class="fw-bold"><?php echo $dep_name; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-lg-6 col-12 mb-3 ' style="color: blue;">
                <div onclick="window.location.href='./class_list_sv.php';"
                    class='dsl border p-3 h-100 clickable rounded-2 box-info-2'>
                    <p>Danh sách lớp hiện hành</p>
                    <p class='fs-1'><?php echo $total_classes; ?></p>
                    <a>Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
