<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function connectToDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "todo_app";
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Erro de conexão: " . $conn->connect_error
        ]);
        exit;
    }

    return $conn;
}
?>
