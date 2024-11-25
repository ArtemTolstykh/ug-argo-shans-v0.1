<?php 
include 'db_conn.php';

require 'vendor/autoload.php'; // Используем PhpSpreadsheet (рекомендуется)

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// SQL запрос для получения данных winners и данных о подарках, если они есть
$sql = "
    SELECT w.id, w.fullname, w.phone, w.hectares, w.farmname, p.name AS prize_name
    FROM winners w
    LEFT JOIN itog i ON w.id = i.winners_id AND i.gift_given = 1
    LEFT JOIN prizes p ON i.prizes_id = p.id
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Создаем новый объект Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Устанавливаем заголовки колонок
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'ФИО');
    $sheet->setCellValue('C1', 'Телефон');
    $sheet->setCellValue('D1', 'Гектары');
    $sheet->setCellValue('E1', 'Ферма');
    $sheet->setCellValue('F1', 'Подарок');

    // Заполняем данные
    $row = 2;
    while ($data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $data['id']);
        $sheet->setCellValue('B' . $row, $data['fullname']);
        $sheet->setCellValue('C' . $row, $data['phone']);
        $sheet->setCellValue('D' . $row, $data['hectares']);
        $sheet->setCellValue('E' . $row, $data['farmname']);
        $sheet->setCellValue('F' . $row, $data['prize_name'] ?: '-'); // Если подарок не выдан, ставим '-'
        $row++;
    }

    // Создаем файл Excel
    $writer = new Xlsx($spreadsheet);
    $fileName = 'winners_export.xlsx';
    $writer->save($fileName);

    echo "Файл ".$fileName." успешно создан.";
} else {
    echo "Нет данных для экспорта.";
}

$conn->close();

?>
