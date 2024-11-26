<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Административная панель</title>
    <link rel="stylesheet" href="style-for-admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        .row-checked {
            background-color: rgba(109,171,40, .7);
        }
    </style>
    <script>
        function toggleRowClass(checkbox) {
            const row = checkbox.closest('tr');
            if (checkbox.checked) {
                row.classList.add('row-checked');
            } else {
                row.classList.remove('row-checked');
            }
        }
    </script>
</head>
<body>

<?php
// Подключение к базе данных

use PhpOffice\PhpSpreadsheet\Calculation\Information\Value;

include 'db_conn.php';

// Обновление статуса gift_given
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['winner_id'])) {
    $winner_id = intval($_POST['winner_id']);
    $gift_given = isset($_POST['gift_given']) ? 1 : 0;

    $update_query = "UPDATE itog SET gift_given = $gift_given WHERE winners_id = $winner_id";
    mysqli_query($conn, $update_query);
}

// Переменная для хранения номера телефона для поиска
$searchPhone = isset($_GET['phone']) ? $_GET['phone'] : '';

// Запрос для получения данных с учетом поиска
$query = "
    SELECT 
        winners.id AS winner_id,
        winners.fullname,
        winners.phone,
        winners.farmname,
        prizes.name AS prize_name,
        itog.gift_given
    FROM 
        itog
    JOIN 
        winners ON itog.winners_id = winners.id
    JOIN 
        prizes ON itog.prizes_id = prizes.id
";

// Получение максимального id из таблицы prizes
$query_prizes = "SELECT MAX(id) AS max_id FROM prizes";
$result_prizes = mysqli_query($conn, $query_prizes);
$row_prizes = mysqli_fetch_assoc($result_prizes);
$max_id_prizes = $row_prizes['max_id'];

echo '<label>Общее количество призов: </label>' . htmlspecialchars($max_id_prizes);

$query_itog = "SELECT MAX(prizes_id) AS max_id_from_itog FROM itog";
$result_itog = mysqli_query($conn, $query_itog);
$row_itog = mysqli_fetch_assoc($result_itog);
$max_id_itog = $row_itog['max_id_from_itog'];

echo '<br><label>Осталось призов: </label>' . htmlspecialchars($max_id_prizes - $max_id_itog);

// Если передан номер телефона для поиска, добавляем условие
if (!empty($searchPhone)) {
    $query .= " WHERE winners.phone LIKE '%" . mysqli_real_escape_string($conn, $searchPhone) . "%'";
}

$result = mysqli_query($conn, $query);

echo '
<div class="doing_in_form">
    <form class="admin-search-phone" method="GET" action="admin.php">
        <label for="phone">Поиск по номеру телефона:</label>
        <input type="text" name="phone" id="phone" value="" placeholder="без +7 и 8' . htmlspecialchars($searchPhone) . '">
        <button type="submit">Искать</button>
        <br><label>!!! Вводите номер без "+7" и "8"</label>
    </form>

    <div class="download_in_form">        
        <label for="download">Вы можете сохранить таблицу в Excel документ</label><br>
        <input style="float:right; margin-top:5px;" type="submit" name="download" id="download_excel_table" value="Скачать">
    </div>
</div>
';

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<tr>
            <th>Порядковый номер</th>
            <th>ФИО</th>
            <th>Телефон</th>
            <th>Название хозяйства</th>
            <th>Приз</th>
            <th>Подарок выдан</th>
          </tr>";

    $number = 1; // Счетчик для порядкового номера

    while ($row = mysqli_fetch_assoc($result)) {
        $checked = $row['gift_given'] ? 'checked' : '';
        $rowClass = $row['gift_given'] ? 'row-checked' : '';
        echo "<tr class='$rowClass'>
                <td>{$number}</td>
                <td>{$row['fullname']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['farmname']}</td>
                <td>{$row['prize_name']}</td>
                <td>
                    <form method='POST' action='admin.php'>
                        <input type='hidden' name='winner_id' value='{$row['winner_id']}'>
                        <input type='checkbox' name='gift_given' value='1' {$checked} onchange='this.form.submit(); toggleRowClass(this)'>
                    </form>
                </td>
              </tr>";
        $number++;
    }

    echo "</table>";
} else {
    echo "Нет данных для отображения.";
}

// Закрытие соединения
mysqli_close($conn);
?>

<script type="text/javascript">
    document.getElementById('download_excel_table').addEventListener('click', function() {
        window.location.href = 'export_to_excel.php';
    });
</script>
</body>
</html>
