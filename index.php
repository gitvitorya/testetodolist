<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /formlogin");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="./css/estilo.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />
    <title>TO DO LIST</title>
</head>

<body class="corpo">
    <div style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('img/logo.png') no-repeat center 75%;
    background-size: 720px;
    opacity: 0.07;
    z-index: -1;
    pointer-events: none;
"></div>
     <header style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; background: linear-gradient(90deg, #145369, #25a5be); color: white; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
   <img src="img/logo.png" alt="Logo" style="height: 40px; width: auto;"/>
    <form action="/backend/logout.php" method="post" style="margin: 0;">
        <button type="submit" style="background: white; color: #2596be; font-weight: bold; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.1); transition: background 0.3s;">
            Sair
        </button>
    </form>
</header>

    <div class="todo-container">
        <header class="header">
            <h2>TO DO LIST</h2>
        </header>

        <form id="todo-form">
            <p>Adicione a sua tarefa:</p>
            <div class="form-controle">
                <input type="text" id="todo-input" placeholder="O que você vai fazer?" autocomplete="off" />
                <button id="add" type="submit">
                    <i class="bi-plus-square"></i>
                </button>
            </div>
        </form>

        <form id="edit-form" class="hide" data-id="">
            <p>Edite a sua tarefa:</p>
            <div class="form-controle">
                <input type="text" id="edit-input" autocomplete="off" />
                <button id="edit" type="submit">
                    <i class="bi-pencil"></i>
                </button>
            </div>
            <button id="cancel-edit-btn" type="button">CANCELAR</button>
        </form>

        <div id="toolbar">
            <div id="search">
                <h4>Pesquisar:</h4>
                <form id="search-form">
                    <input type="text" id="search-input" placeholder="Buscar" autocomplete="off" />
                    <button id="search-button" type="submit">
                        <i class="bi-search-heart"></i>
                    </button>
                </form>
            </div>
            <div id="filter">
                <h4>Filtrar:</h4>
                <select id="filter-select">
                    <option value="all">Todos</option>
                    <option value="concluida">Feitas</option>
                    <option value="pendente">A fazer</option>
                </select>
            </div>
        </div>

        <div id="todo-list">
            
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="./js/script.js"></script>
</body>


</html>
