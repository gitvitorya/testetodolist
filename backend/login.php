<?php
session_start(); 

include_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $username = $_POST['username'];
    $password = $_POST['senha'];

    $conn = connectToDatabase();
    $stmt = $conn->prepare("SELECT * FROM users WHERE nome_usuario = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: /"); 
            exit();
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }

    $stmt->close();
    $conn->close();
}
?>

