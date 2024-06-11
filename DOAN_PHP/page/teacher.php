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
    echo 'Không tìm thấy thông tin giáo viên.';
    exit;
}

$_user_id = $user['_user_id'];
$dep_name = $user['dep_name'];

// Hiển thị toàn bộ thông tin của giáo viên dựa trên _user_id
$stmt = $pdo->prepare('SELECT * FROM _user WHERE _user_id = :user_id');
$stmt->execute(['user_id' => $_user_id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    echo 'Không tìm thấy thông tin giáo viên.';
    exit;
}

// Hiển thị số lớp giáo viên đang dạy
$stmt = $pdo->prepare('SELECT COUNT(class_id) AS total_classes FROM class WHERE teacher_id = :user_id');
$stmt->execute(['user_id' => $_user_id]);
$class_count = $stmt->fetch(PDO::FETCH_ASSOC);

$total_classes = $class_count['total_classes'];
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
        max-width: 120px;
        max-height: 120px;
    }

    .colors {
        color: #667580;
    }
    </style>
</head>

<body>
    <?php include "header.php" ?>
    <div class='container my-3'>
        <div class='row'>
            <div class='col-lg-6 col-12 mb-3' style="background-color: white;">
                <div class='border rounded-2 p-3 h-100 box-info row'>
                    <h3 class="border-bottom fw-bolder pb-3 colors" style="font-size: 18px;">Thông tin giáo viên
                    </h3>
                    <div class="d-flex flex-column col-lg-3 col-12">
                        <div class="avt-info rounded-circle px-2 text-center">
                            <img src="../img/avt.png" class="w-100 h-100 rounded-circle mb-2" alt="">
                        </div>
                        <a href="infogv.php" class="text-center">Xem chi tiết</a>
                    </div>
                    <div class="col-lg-6 col-12">
                        <ul class='list-unstyled' style="font-size: 14px;">
                            <li class="colors py-2">Họ và tên: <span class="fw-bold py-5"><?php echo $teacher['fullname']; ?></span></li>
                            <li class="colors py-2">Trình độ: <span class="fw-bold">Thạc sĩ</span></li>
                            <li class="colors py-2">Khoa: <span class="fw-bold"><?php echo $dep_name; ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class='col-lg-6 col-12 mb-3 ' style="color: blue;">
                <div onclick="window.location.href='./class_list.php';" class='dsl border p-3 h-100 clickable rounded-2 box-info'>
                    <p>Danh sách lớp hiện hành</p>
                    <p class='fs-1'><?php echo $total_classes; ?></p>
                    <a>Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
