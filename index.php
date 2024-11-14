<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Шанс на барабане</title>
  <link rel="stylesheet" href="style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php
// Подключение к базе данных
require 'db_conn.php';

// Получение максимального id из таблицы prizes
$query_prizes = "SELECT MAX(id) AS max_id FROM prizes";
$result_prizes = mysqli_query($conn, $query_prizes);
$row_prizes = mysqli_fetch_assoc($result_prizes);
$max_id_prizes = $row_prizes['max_id'];

// Получение максимального prizes_id из таблицы itog
$query_itog = "SELECT MAX(prizes_id) AS max_id_from_itog FROM itog";
$result_itog = mysqli_query($conn, $query_itog);
$row_itog = mysqli_fetch_assoc($result_itog);
$max_id_itog = $row_itog['max_id_from_itog'];

// Сравнение значений и переадресация, если они равны
if ($max_id_prizes == $max_id_itog) {
    header("Location: no-prizes.html");
    exit;
}

// Остальной код, если значения не совпадают
?>

<div class="slider-container">
    
  <div class="slide" id="slide1">

  <form class="" id="dataForm" action="add_data.php" method="POST">
    <div class="logo">
      <img src="logo.webp" alt="logo-gk-shans">
    </div>

    <div class="main-txt-in-form"><span class="main-txt">Заполни анкету и получи приз!</span></div>

    <!-- Поле для ввода ФИО -->
    <label for="fullname"><span class="star-required">*</span>ФИО:</label>
    <input type="text" id="fullname" name="fullname" placeholder="Введите ваше ФИО" required>
    
    <!-- Поле для ввода номера телефона -->
    <label for="phone"><span class="star-required">*</span>Номер телефона:</label>
   
    <input type="text" id="phone" name="phone" maxlength="20" inputmode="numeric" placeholder="+7 " required>

    <!-- Ползунок для выбора гектаров -->
    <label for="hectares"><span class="star-required">*</span>Количество гектаров:</label>

    <div class="range-container">
    <output id="rangeOutput" class="range-output">Менее 500 га</output>
    <input type="range" id="hectares" min="500" max="40000" step="100" value="0" oninput="updateOutput(this.value)" required>
    <input type="number" id="hectaresInput" name="hectares" min="500" max="40000" step="100" value="500" oninput="updateRange(this.value)">
    <div class="range-labels">
        <span>Менее 500 га</span>
        <span>Более 40 000 га</span>
    </div>
</div>
  
    <!-- Поле для ввода названия хозяйства -->
    <label for="farmname">Название хозяйства:</label>
    <input type="text" id="farmname" name="farmname" placeholder="Введите название хозяйства">

    <!-- Кнопка для отправки формы -->
    <input id="slider-button" class="submit" type="submit" name="result-submit" value="Отправить">
    
    <div class="data-processing-block">
        <span class="data-processing">Нажимая кнопку "отправить", вы соглашаетесь c <a href="https://shans-group.com/soglasie-na-obrabotku-personalnykh-dannykh/">политикой обработки персональных данных</a></span>
    </div>
    <div class="data-processing-block">
        <span class="data-processing"><a class="data-processing" href="http://promo.shans-group.com/rules">Правила проведения акции</a></span>
    </div>
  </form>
  </div>

  <div class="slide" id="slide2">
    <div class="wheel-bg shadow-for-wheel">

        <div class="deal-wheel">
            <img class=" shadow-for-wheel-elem" src="logo-for-whell.webp" alt="">
        <!-- блок с призами -->
        <ul class="spinner shadow-for-wheel-elem"></ul>
        <!-- язычок барабана -->
        <div class="ticker shadow-for-wheel-elem"></div>
        <!-- кнопка -->
        <form method="POST" action="prize-output.php" style="display: contents;">
            <input class="btn-spin btn-result" type="submit" name="btn-spin-result" value="Испытай удачу">
            </form>
        </div>
        </div>
  </div>
</div>

<script src="main.js"></script>
<script type="text/javascript">
function updateOutput(value) {
    const output = document.getElementById('rangeOutput');
    const hectaresInput = document.getElementById('hectaresInput');
    const intValue = parseInt(value, 10);
    
    // Обновляем значение текстового поля
    hectaresInput.value = intValue;
    
    if (intValue <= 500) {
        output.innerText = "Менее 500 га";
    } else if (intValue >= 40000) {
        output.innerText = "Более 40 000 га";
    } else {
        output.innerText = intValue + " га";
    }
}

function updateRange(value) {
    const range = document.getElementById('hectares');
    const output = document.getElementById('rangeOutput');
    const intValue = parseInt(value, 10);
    
    // Проверяем, что значение находится в пределах диапазона
    if (intValue >= 0 && intValue <= 45000) {
        // Обновляем значение ползунка
        range.value = intValue;

        // Обновляем вывод
        if (intValue == 0) {
            output.innerText = "Менее 500 га";
        } else if (intValue >= 40000) {
            output.innerText = "Более 40 000 га";
        } else {
            output.innerText = intValue + " га";
        }
    }
}

</script>
</body>
</html>