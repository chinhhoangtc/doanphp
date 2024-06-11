<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../cnf/connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header('location:login.php');
    exit;
}

$username = $_SESSION['username'];

// Lấy thông tin người dùng từ cơ sở dữ liệu
$stmt = $pdo->prepare('SELECT fullname, role FROM _user WHERE username = :username');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo 'Không tìm thấy thông tin người dùng.';
    exit;
}

$fullname = $user['fullname'];
$role = $user['role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet'
        integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="./login.php">HUIT</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./login.php">Trang chủ</a>
                    </li>
                </ul>
                <div class="dropdown">
                    <button class="dropdown-toggle d-flex align-items-center border-0 bg-transparent" type="button"
                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar rounded-circle me-2" style="width: 40px; height: 40px; overflow: hidden;">
                            <img src="../img/avt.png" alt="User Avatar" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <span style="font-size: 14px;"><?php echo $fullname; ?></span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mt-2" id="dropdownMenu">
                        <li class="border-bottom">
                            <?php if ($role == 1) { ?>
                                <a class="dropdown-item" style="font-size: 13px; color:#667580; padding:10px 15px;" href="infogv.php">
                                    Thông tin cá nhân
                                </a>
                            <?php } else { ?>
                                <a class="dropdown-item" style="font-size: 13px; color:#667580; padding:10px 15px;" href="infosv.php">
                                    Thông tin cá nhân
                                </a>
                            <?php } ?>
                        </li>
                        <li>
                            <a class="dropdown-item" style="font-size: 13px; color:#667580; padding:10px 15px;" href="logout.php">
                                Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>

                <script>
                const dropdownButton = document.querySelector('#dropdownMenuButton');
                const dropdownMenu = document.querySelector('#dropdownMenu');

                dropdownButton.addEventListener('click', () => {
                    dropdownMenu.classList.toggle('show');
                });

                document.addEventListener('click', (event) => {
                    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
                </script>
            </div>
        </div>
    </nav>
</body>

</html>
