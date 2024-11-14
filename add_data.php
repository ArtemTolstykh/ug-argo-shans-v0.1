<?php
include 'db_conn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $hectares = $_POST['hectares'];
    $farm = $_POST['farmname'];

    // Убираем "+7" и пробелы из номера телефона
    $phone = preg_replace('/^\+7\s*/', '', $phone);
    $phone = str_replace(' ', '', $phone);

    // Проверка, есть ли введенный номер телефона в базе данных
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM winners WHERE phone = ?");
    $check_stmt->bind_param("s", $phone);
    $check_stmt->execute();
    $check_stmt->bind_result($phone_exists);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($phone_exists > 0) {
        // Переадресация, если телефон уже зарегистрирован
        echo json_encode(['redirect' => 'er-message.html']);
        exit();
    } else {
        // Подготовка запроса для вставки в таблицу winners
        $stmt = $conn->prepare("INSERT INTO winners (fullname, phone, hectares, farmname) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $fullname, $phone, $hectares, $farm);

        // Выполнение запроса на вставку
        if ($stmt->execute()) {
            $winner_id = $conn->insert_id;

            // Поиск доступного приза
            $stmt_prize = $conn->prepare("SELECT id FROM prizes WHERE id NOT IN (SELECT prizes_id FROM itog) ORDER BY id ASC LIMIT 1");
            $stmt_prize->execute();
            $result = $stmt_prize->get_result();
            if ($row = $result->fetch_assoc()) {
                $prizes_id = $row['id'];

                // Связывание приза и победителя
                $stmt_itog = $conn->prepare("INSERT INTO itog (prizes_id, winners_id) VALUES (?, ?)");
                $stmt_itog->bind_param("ii", $prizes_id, $winner_id);

                if ($stmt_itog->execute()) {
                    echo "Prize and winner successfully linked in itog table!";
                } else {
                    echo "Error inserting into itog table: " . $stmt_itog->error;
                }

                $stmt_itog->close();
            } else {
                echo "No available prizes without a winner.";
            }

            $stmt_prize->close();
        } else {
            echo "Error inserting into winners table: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
}


function call_sql($conn, $servername, $username_db, $password_db, $dbname)
    {
    
    // SQL-запрос для получения названия последнего выигранного подарка
    $sql_output = "
    SELECT 
        w.fullname AS person_name,    -- ФИО человека
        p.name AS prize_name          -- Название приза
    FROM 
        itog i
    JOIN 
        winners w ON i.winners_id = w.id
    JOIN 
        prizes p ON i.prizes_id = p.id
    ORDER BY 
        i.id DESC
    LIMIT 1;
    ";

    $result_query = $conn->query($sql_output);

    // Проверка результата
    if ($result_query->num_rows > 0) {
        // Получение строки результата
        $row = $result_query->fetch_assoc();
        $result_prize = $row['prize_name'];
    } else {
        $result_prize = "no prize";
        
    }
    $conn->close();
    return $result_prize;
    }

?>