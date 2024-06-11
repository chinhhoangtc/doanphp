<?php
include '../cnf/connect.php';

// Lấy dữ liệu từ form
$attendance_id = isset( $_POST[ 'attendance_id' ] ) ? $_POST[ 'attendance_id' ] : '';
$absent = isset( $_POST[ 'absent' ] ) ? $_POST[ 'absent' ] : [];
$late = isset( $_POST[ 'late' ] ) ? $_POST[ 'late' ] : [];

// Lấy class_id từ URL ( phương thức GET )
//$class_id = isset( $_GET[ 'class_id' ] ) ? $_GET[ 'class_id' ] : '';
// get class_id from localstorage
session_start();
if ( isset( $_SESSION[ 'class_id' ] ) ) {
    $class_id = $_SESSION[ 'class_id' ];
} else {
    echo 'No class ID is set in session.';
}

// Truy vấn dữ liệu từ bảng student_class và _user
$stmt = $pdo->prepare( "SELECT DISTINCT student_class.student_id, _user.fullname 
                       FROM student_class 
                       JOIN _user ON student_class.student_id = _user._user_id
                       WHERE student_class.class_id = :class_id" );
$stmt->execute( [ 'class_id' => $class_id ] );
$classes = $stmt->fetchAll( PDO::FETCH_ASSOC );

// Xử lý lưu dữ liệu
foreach ( $classes as $sc ) {
    $student_id = $sc[ 'student_id' ];
    $is_absent = isset( $absent[ $student_id ] ) ? 1 : 0;
    $late_info = isset( $late[ $student_id ] ) ? $late[ $student_id ] : '';

    echo $attendance_id;

    ?> <br> <?php
    echo $is_absent;
    ?> <br> <?php

    echo $late_info;
    ?> <br> <?php

    echo $student_id;
    ?> <br> <?php

    // Chèn hoặc cập nhật dữ liệu vào bảng attendance_records
    $stmt = $pdo->prepare( "INSERT INTO attendance_records (attendance_id, student_id, absent, late) 
                           VALUES (:attendance_id, :student_id, :absent, :late)
                           ON DUPLICATE KEY UPDATE absent = :absent, late = :late" );
    $stmt->execute( [
        'attendance_id' => $attendance_id,
        'student_id' => $student_id,
        'absent' => $is_absent,
        'late' => $late_info
    ] );
}

// Cập nhật cột is_submit trong bảng attendance_list
$stmt = $pdo->prepare( 'UPDATE attendance_list SET is_submit = 1 WHERE attendance_id = :attendance_id' );
$stmt->execute( [ 'attendance_id' => $attendance_id ] );

// Chuyển hướng về trang attendance.php với class_id trên URL
header( 'Location: attendance.php?classid=' .$class_id );
exit();
?>
