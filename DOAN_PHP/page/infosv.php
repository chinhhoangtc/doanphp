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
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo 'Không tìm thấy thông tin giáo viên.';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Sinh Viên</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .profile-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .profile-header img {
            border-radius: 50%;
            margin-right: 20px;
        }

        .profile-header h1,
        .profile-header h5 {
            margin: 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #667580;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .info-list {
            font-size: 14px;
        }

        .info-list strong {
            display: inline-block;
            min-width: 120px;
        }
    </style>
</head>

<body>
    <?php include "header.php" ?>
    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="../img/avt.png" alt="Profile Image" class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                        <h1 class="h4 mt-3"><?php echo $student['fullname'] ?></h1>
                        <h5>MSSV: <?php echo $student['_user_id'] ?></h5>
                    </div>
                    <div class="col-md-8">
                        <h2 class="section-title">Thông tin sinh viên</h2>
                        <div class="row">
                            <div class="col-md-6 info-list"><strong>Trạng thái:</strong> Đang học</div>
                            <div class="col-md-6 info-list"><strong>Mã hồ sơ:</strong> <?php echo $student['_user_id'] ?></div>
                            <div class="col-md-6 info-list"><strong>Lớp học:</strong> <?php echo $student['class'] ?></div>
                            <div class="col-md-6 info-list"><strong>Khoa:</strong> <?php echo $dep_name; ?></div>
                            <div class="col-md-6 info-list"><strong>Ngày vào trường:</strong> 20/9/2021</div>
                            <div class="col-md-6 info-list"><strong>Cơ sở:</strong> CNTP TP.HCM</div>
                            <div class="col-md-6 info-list"><strong>Loại hình đào tạo:</strong> Chính quy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="section-title">Thông tin cá nhân</h2>
                <div class="row">
                    <div class="col-md-6 info-list"><strong>Ngày sinh:</strong> <?php echo $student['date'] ?></div>
                    <div class="col-md-6 info-list"><strong>Dân tộc:</strong> Kinh</div>
                    <div class="col-md-6 info-list"><strong>Số CCCD:</strong> 12345678</div>
                    <div class="col-md-6 info-list"><strong>Ngày cấp:</strong> 27/04/2021</div>
                    <div class="col-md-6 info-list"><strong>Tôn giáo:</strong> Không</div>
                    <div class="col-md-6 info-list"><strong>Khu vực:</strong> 2NT</div>
                    <div class="col-md-6 info-list"><strong>Điện thoại:</strong> 12345678</div>
                    <div class="col-md-6 info-list"><strong>Email:</strong> sinhvien@gmail.com</div>
                    <div class="col-md-12 info-list"><strong>Nơi sinh:</strong> Tỉnh <?php echo $student['address'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
