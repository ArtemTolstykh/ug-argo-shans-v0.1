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
require 'add_data.php';

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
      <img src="logo.png" alt="logo-gk-shans">
    </div>

    <span class="main-txt">Заполни анкету, чтобы испытать Шанс!</span>

    <!-- Поле для ввода ФИО -->
    <label for="fullname"><span class="star-required">*</span>ФИО:</label>
    <input type="text" id="fullname" name="fullname" placeholder="Введите ваше ФИО" required>
    
    <!-- Поле для ввода номера телефона -->
    <label for="phone"><span class="star-required">*</span>Номер телефона:</label>
   
    <input type="text" id="phone" name="phone" placeholder="+7" maxlength="20" required>

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
    <input id="slider-button" class="submit" type="submit" name="result-submit" value="Отправить" href="wheel.html">
    
    <span class="data-processing">*нажимая кнопку "отправить", вы соглашаетесь c <a href="https://shans-group.com/soglasie-na-obrabotku-personalnykh-dannykh/">политикой обработки персональных данных<a></span>
  </form>
  </div>

  <div class="slide" id="slide2">
    <div class="wheel-bg">
        <div class="deal-wheel">
        <!-- блок с призами -->
        <ul class="spinner"></ul>
        <!-- язычок барабана -->
        <div class="ticker"></div>
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
/*
$(document).ready(function() {
    $('#dataForm').submit(function(event) {
        event.preventDefault(); // Предотвращаем стандартную отправку формы
        
        $.ajax({
            url: 'add_data.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'redirect') {
                    // Если получен статус "redirect", перенаправляем на указанный URL
                    window.location.href = response.url;
                } else if (response.status === 'error') {
                    alert(response.message); // Выводим сообщение об ошибке
                } else if (response.status === 'success') {
                    //alert(response.message); // Выводим сообщение об успехе
                    //$('#dataForm')[0].reset();
                  }
            },
            error: function() {
                alert('Ошибка отправки запроса. бе');
            }
        });
    });
}); */
</script>
</body>
</html>