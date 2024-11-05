<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Поздравляем!</title>
  <link rel="stylesheet" href="style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
	<div id="myModal" class="modal">
  <div class="modal-content">
    <!--<span class="close">&times;</span> -->
    <h2>Поздравляем!</h2>
    <p>Вы выиграли: 
    <strong>
    <?php include 'add_data.php'; 
    
    $result_sql = call_sql($conn, $servername, $username_db, $password_db, $dbname);
    echo $result_sql ?? " ";

    ?>
    </strong></p>
    <p>Получите его на стенде ГК "Шанс", назвав свой номер телефона.</p>

    <button class="modalBtn" id="btn-reset">Отлично</button>
  </div>
</div>
<script src="main.js"></script>
<script type="text/javascript">
	document.getElementById("btn-reset").addEventListener("click", function() {
  window.location.href = "index.php";
});
</script>
</body>
</html>