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
</head>
<body>
	
<?php
// Подключение к базе данных
include 'add_data.php';

// Переменная для хранения номера телефона для поиска
$searchPhone = isset($_GET['phone']) ? $_GET['phone'] : '';

// Запрос для получения данных с учетом поиска
$query = "
    SELECT 
        winners.id AS winner_id,
        winners.fullname,
        winners.phone,
        winners.farmname,
        prizes.name AS prize_name
    FROM 
        itog
    JOIN 
        winners ON itog.winners_id = winners.id
    JOIN 
        prizes ON itog.prizes_id = prizes.id
";

// Если передан номер телефона для поиска, добавляем условие
if (!empty($searchPhone)) {
    $query .= " WHERE winners.phone LIKE '%" . mysqli_real_escape_string($conn, $searchPhone) . "%'";
}

$result = mysqli_query($conn, $query);

echo '<form method="GET" action="admin.php">
        <label for="phone">Поиск по номеру телефона:</label>
        <input type="text" name="phone" id="phone" value="' . htmlspecialchars($searchPhone) . '">
        <button type="submit">Искать</button>
      </form>';

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<tr>
            <th>Порядковый номер</th>
            <th>ФИО</th>
            <th>Телефон</th>
            <th>Название хозяйства</th>
            <th>Приз</th>
          </tr>";

    $number = 1; // Счетчик для порядкового номера

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$number}</td>
                <td>{$row['fullname']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['farmname']}</td>
                <td>{$row['prize_name']}</td>
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
</body>
</html>