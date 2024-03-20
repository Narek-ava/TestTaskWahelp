<?php
// Подключение к базе данных
$servername = "localhost";
$username = "user";
$password = "Mypassword123!@#";
$dbname = "csvDb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверка, что запрос отправлен методом POST и URL содержит "/api/upload"
if ($_SERVER["REQUEST_METHOD"] == "POST" && strpos($_SERVER['REQUEST_URI'], '/api/upload') !== false) {
    // Check if file was uploaded without errors
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if file is a CSV file
        if ($fileType == "csv") {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                // Call function to upload users from CSV
                uploadUsersFromCSV($target_file, $conn);
                echo "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded and users have been added to the database.";

                // Call function to add users to mailing queue
                addToMailingQueue($conn, "Special Offer", "Hello! Check out our special offer!");
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only CSV files are allowed.";
        }
    } else {
        echo "No file uploaded.";
    }
}

// Задание 1: Загрузка списка пользователей в БД из CSV файла
function uploadUsersFromCSV($filename, $conn) {
    $file = fopen($filename, "r");

    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        $number = $data[0];
        $name = $data[1];

        $sql = "INSERT INTO users (number, name) VALUES ('$number', '$name')";
        $conn->query($sql);
    }

    fclose($file);
}

// Функция для добавления пользователей в очередь рассылки
function addToMailingQueue($conn, $mailing_name, $mailing_text) {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $user_id = $row["id"];

            // Проверка, была ли уже отправлена рассылка этому пользователю
            $check_sql = "SELECT * FROM mailing_queue WHERE user_id = $user_id AND mailing_name = '$mailing_name'";
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows == 0) {
                // Добавление пользователя в очередь рассылки
                $insert_sql = "INSERT INTO mailing_queue (user_id, mailing_name, mailing_text) VALUES ('$user_id', '$mailing_name', '$mailing_text')";
                $conn->query($insert_sql);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
</head>
<body>
<h2>Upload CSV File</h2>
<form action="/api/upload" method="post" enctype="multipart/form-data">
    Select CSV file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload CSV" name="submit">
</form>
</body>
</html>
