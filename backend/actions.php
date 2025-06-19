<?php

include_once 'functions.php';
include_once 'conexao.php';

function getPostParams() {
    parse_str(file_get_contents("php://input"),$post_vars);
    return $post_vars;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['updateTodoStatus']) && isset($_POST['id']) && isset($_POST['status'])) {
        $id = intval($_POST['id']);
        $status = $_POST['status'];
        echo updateTodoStatus($id, $status);
        return;
    }

    $title = $_POST['title'];
    echo createTodo($title);
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH') {
    $params = getPostParams();
    $id = $params['id'];
    $title = $params['title'];
    echo updateTodo($id, $title);
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $params = getPostParams();
    $id = $params['id'];
    echo deleteTodo($id);
}


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    echo searchByString($_GET['search']);
}

if (isset($_GET['searchByStatus'])) {
    echo searchByStatus($_GET['searchByStatus']); 
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['getAll'])) {
    echo getAllTodos();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['getById'])) {
    $id = $_GET['id'];
    echo getTodoById($id);
}


?>
