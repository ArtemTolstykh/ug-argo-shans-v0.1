<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vertical Slider test</title>
  <link rel="stylesheet" href="style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>


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
        <button class="btn-spin btn-result">Испытай удачу</button>
      </div>
    </div>
  </div>
</div>

<div id="myModal" class="modal">
  <div class="modal-content">
    <!--<span class="close">&times;</span> -->
    <h2>Поздравляем!</h2>
    <p>Вы выйграли: </p>
    <strong>
    <?php include 'add_data.php'; 
    $result_sql = call_sql($conn, $servername, $username_db, $password_db, $dbname); 
    echo $result_sql ?? " ";

    ?>
    </strong>
    <p>Получите его на стенде ГК "Шанс", назвав свой номер телефона.</p>

    <button class="modalBtn" id="confirmButton">Отлично</button>
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