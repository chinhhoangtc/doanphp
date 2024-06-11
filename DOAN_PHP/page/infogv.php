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


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Giảng Viên</title>
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
    </style>
</head>

<body>
    <?php include "header.php" ?>
    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="../img/avt.png" alt="Profile Image" class="rounded-circle" width="100" height="100"
                            style="object-fit: cover;">
                        <h1 class="h4 mt-3"><?php echo $teacher['fullname'] ?></h1>
                        <h5>MSGV: <?php echo $teacher['_user_id'] ?></h5>
                    </div>
                    <div class="col-md-8">
                        <h2 class="h5 border-bottom pb-2">Thông tin giảng viên</h2>
                        <div class="row">
                            <div class="col-md-6"><i class="fas fa-chalkboard-teacher mr-2"></i><strong>Trạng
                                    thái:</strong> Đang dạy</div>
                            <div class="col-md-6"><i class="fas fa-graduation-cap mr-2"></i><strong>Trình độ:</strong>
                                Thạc sĩ</div>
                            <div class="col-md-6"><i class="fas fa-building mr-2"></i><strong>Khoa:</strong>
                                <?php echo $dep_name; ?></div>
                            <div class="col-md-6"><i class="fas fa-folder mr-2"></i><strong>Mã hồ sơ:</strong>
                                <?php echo $teacher['_user_id'] ?> </div>
                            <div class="col-md-6"><i class="fas fa-calendar-alt mr-2"></i><strong>Ngày vào dạy:</strong>
                                20/9/2021</div>
                            <div class="col-md-6"><i class="fas fa-school mr-2"></i><strong>Cơ sở:</strong> CNTP TP.HCM
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="h5 border-bottom pb-2">Thông tin cá nhân</h2>
                <div class="row">
                    <div class="col-md-6"><i class="fas fa-birthday-cake mr-2"></i><strong>Ngày sinh:</strong>
                        <?php echo $teacher['date'] ?></div>
                    <div class="col-md-6"><i class="fas fa-users mr-2"></i><strong>Dân tộc:</strong> Kinh</div>
                    <div class="col-md-6"><i class="fas fa-id-card-alt mr-2"></i><strong>Số CCCD:</strong> 12345678
                    </div>
                    <div class="col-md-6"><i class="fas fa-calendar-check mr-2"></i><strong>Ngày cấp:</strong>
                        27/04/2021</div>
                    <div class="col-md-6"><i class="fas fa-praying-hands mr-2"></i><strong>Tôn giáo:</strong> Không
                    </div>
                    <div class="col-md-6"><i class="fas fa-map-marker-alt mr-2"></i><strong>Khu vực:</strong> 2NT</div>
                    <div class="col-md-6"><i class="fas fa-phone mr-2"></i><strong>Điện thoại:</strong> 12345678</div>
                    <div class="col-md-6"><i class="fas fa-envelope mr-2"></i><strong>Email:</strong>
                        giangvien@gmail.com</div>
                    <div class="col-md-12"><i class="fas fa-map mr-2"></i><strong>Nơi sinh:</strong> Tỉnh
                        <?php echo $teacher['address'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>