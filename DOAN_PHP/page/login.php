<?php
session_start();
include '../cnf/connect.php';

// Khởi tạo biến lỗi
$error = '';

// Kiểm tra nếu session username đã được thiết lập, chuyển hướng người dùng đến trang tương ứng
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $stm = $pdo->prepare('SELECT role FROM _user WHERE username=:username');
    $stm->execute(['username' => $username]);
    $data = $stm->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        if ($data['role'] == 1) {
            // Nếu là giáo viên
            header('location:teacher.php');
            exit();
        } else {
            // Nếu là sinh viên
            header('location:student.php');
            exit();
        }
    }
}

// Tiếp tục xử lý đăng nhập nếu chưa có session username hoặc không tìm thấy thông tin vai trò trong cơ sở dữ liệu
if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Yêu cầu nhập đầy đủ thông tin đăng nhập !!!';
    } else {
        $stm = $pdo->prepare('SELECT * FROM _user WHERE username=:username AND password=:password');
        $stm->execute(['username' => $username, 'password' => $password]);
        $data = $stm->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Xác định vai trò của người dùng dựa trên cột 'role'
            if ($data['role'] == 1) {
                // Đăng nhập là giáo viên
                $_SESSION['username'] = $username;
                header('location:teacher.php');
                exit();
            } else {
                // Đăng nhập là sinh viên
                $_SESSION['username'] = $username;
                header('location:student.php');
                exit();
            }
        } else {
            $error = 'Thông tin tài khoản hoặc mật khẩu không chính xác !!!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điểm danh sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
    }

    .background-blur {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('../img/bannerLogin.jpg') no-repeat center center;
        background-size: cover;
        z-index: -1;
        filter: brightness(60%);
        filter: blur(7px);
    }

    .bg {
        background: url('../img/bgtrai.png') no-repeat center center;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .container {
        position: relative;
        z-index: 1;
    }

    .fit-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    </style>
</head>

<body>
    <div class="background-blur"></div>
    <div class="container vh-100">
        <div class="row mt-5">
            <div class="col-lg-6 col-12 text-center rounded-2 p-2">
                <img class="fit-image" src="../img/bgtrai.png" alt="Background Image">
            </div>
            <div class="col-lg-6 col-12 d-flex justify-content-center">
                <div class="border p-4 rounded shadow w-100"
                    style="max-width: 400px; background-color: rgb(255 255 255 / 80%);">
                    <h3 class="text-center mb-4" style="color: blue;">Đăng nhập hệ thống</h3>
                    <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>
                    <form action="login.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tài khoản</label>
                            <input value="<?php echo isset($_POST['username']) ? $_POST['username'] : "" ?>" class="form-control" type="text" name="username" id="username">
                            
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input class="form-control" type="password" name="password" id="password">
                        </div>
                        <div class="text-center mt-3 w-100">
                            <button type="submit" class="btn btn-primary w-100" name="login">Đăng nhập</button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>