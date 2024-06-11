<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Kết nối đến cơ sở dữ liệu
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

// Tạo đối tượng Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Thiết lập tiêu đề cột
$sheet->setCellValue('A1', 'MSSV');
$sheet->setCellValue('B1', 'Họ tên');
$sheet->setCellValue('C1', 'Ngày sinh');
$sheet->setCellValue('D1', 'Lớp');
$sheet->setCellValue('E1', 'Vắng');
$sheet->setCellValue('F1', 'Trễ');

// Thêm dữ liệu vào các dòng tiếp theo
$rowNumber = 2;
foreach ($classes as $sc) {
    $sheet->setCellValue('A' . $rowNumber, $sc['student_id']);
    $sheet->setCellValue('B' . $rowNumber, $sc['fullname']);
    $sheet->setCellValue('C' . $rowNumber, $sc['date']);
    $sheet->setCellValue('D' . $rowNumber, $sc['class']);
    $sheet->setCellValue('E' . $rowNumber, isset($attendance_data[$sc['student_id']]) && $attendance_data[$sc['student_id']]['absent'] ? 'X' : '');
    $sheet->setCellValue('F' . $rowNumber, isset($attendance_data[$sc['student_id']]) ? $attendance_data[$sc['student_id']]['late'] : '');
    $rowNumber++;
}

// Xuất file Excel
$writer = new Xlsx($spreadsheet);
$filename = 'attendance_' . $attendance_id . '.xlsx';

// Đảm bảo không có dữ liệu thừa được xuất ra
ob_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
