<?php
include_once 'conexao.php';

// GET ALL
function getAllTodos() {
    $conn = connectToDatabase();
    $sql = "SELECT * FROM tarefas ORDER BY id DESC";
    $result = $conn->query($sql);

    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }

    $conn->close();
    return json_encode(['status' => 'success', 'todos' => $todos]);
}

// CREATE
function createTodo($title) {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("INSERT INTO tarefas (title) VALUES (?)");
    $stmt->bind_param("s", $title);

    if ($stmt->execute()) {
        $id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
        return json_encode(['status' => 'success', 'id' => $id]);
    } else {
        $stmt->close();
        $conn->close();
        return json_encode(['status' => 'error', 'message' => 'Erro ao criar tarefa']);
    }
}

// UPDATE
function updateTodo($id, $title) {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("UPDATE tarefas SET title = ? WHERE id = ?");
    $stmt->bind_param("si", $title, $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return json_encode(['status' => 'success']);
    } else {
        $stmt->close();
        $conn->close();
        return json_encode(['status' => 'error', 'message' => 'Erro ao atualizar tarefa']);
    }
}

// DELETE
function deleteTodo($id) {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return json_encode(['status' => 'success']);
    } else {
        $stmt->close();
        $conn->close();
        return json_encode(['status' => 'error', 'message' => 'Erro ao excluir tarefa']);
    }
}

// SEARCH
function searchByString($searchTerm) {
    $conn = connectToDatabase();
    $sql = "SELECT * FROM tarefas WHERE title LIKE ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);

    $searchTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }

    $conn->close();
    return json_encode(['status' => 'success', 'todos' => $todos]);
}

function searchByStatus($status) {
    $conn = connectToDatabase();

    if ($status === 'all') {
        $sql = "SELECT * FROM tarefas ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
    } else {
        $sql = "SELECT * FROM tarefas WHERE status = ? ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $status);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }

    $conn->close();
    return json_encode(['status' => 'success', 'todos' => $todos]);
}


function updateTodoStatus($id, $status) {
    $conn = connectToDatabase();
    $sql = "UPDATE tarefas SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        $conn->close();
        return json_encode(['status' => 'success']);
    } else {
        $conn->close();
        return json_encode(['status' => 'error']);
    }
}

?>
