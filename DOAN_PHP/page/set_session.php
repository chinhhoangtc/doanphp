<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['class_id'] = $_POST['class_id'];

    // có thể lưu thêm các thông tin khác vào session ở đây nếu cần
}
?>
