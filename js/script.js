
const todoForm = document.querySelector("#todo-form");
const searchForm = document.querySelector("#search-form");
const todoInput = document.querySelector("#todo-input");
const todoList = document.querySelector("#todo-list");
const editForm = document.querySelector("#edit-form");
const editInput = document.querySelector("#edit-input");
const searchInput = document.querySelector("#search-input");
const filterSelectInput = document.querySelector("#filter-select");
const cancelEditBtn = document.querySelector("#cancel-edit-btn");
let oldInputValue = "";

$(document).ready(function () {
    getAllTodos();
});

filterSelectInput.addEventListener('change', (e) => {
    const status = e.target.value;
    searchInput.value = ''
    
    $.ajax({
        url: `/backend/actions.php?searchByStatus=${status}`,
        method: 'GET',
        success: (response) => {
            console.log(response)
            const data = JSON.parse(response);
            if (data.status === 'success') {
                clearAllTodos();
                renderAllTodos(data.todos);
            } else {
                alert('Erro ao buscar tarefas.');
            }
        }
    })
})

const searchTodos = (title) => {
    $.ajax({
        url: `/backend/actions.php?search=${title}`,
        method: 'GET',
        success: (response) => {
            console.log(response)
            const data = JSON.parse(response);
            if (data.status === 'success') {
                clearAllTodos();
                renderAllTodos(data.todos);
            } else {
                alert('Erro ao buscar tarefas.');
            }
        }
    })
}

const searchTodosByStatus = (status) => {
    $.ajax({
        url: `/backend/actions.php?searchTodosByStatus=true&status=${status}`,
        method: 'GET',
        success: (response) => {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                clearAllTodos(); 
                renderAllTodos(data.todos); 
            } else {
                alert('Erro ao buscar tarefas por status.');
            }
        }
    });
};

searchForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const title = searchInput.value.trim();

    searchTodos(title)
})

const renderTodo = ({title, id, status}) => {
    const todo = document.createElement("div");
    todo.classList.add("todo");

    if (status === 'concluida') {
        todo.classList.add("done");
    }

    if (id) {
        todo.dataset.id = id;
    }

    const todoTitle = document.createElement("h3");
    todoTitle.innerText = title;
    todo.appendChild(todoTitle);

    const doneBtn = document.createElement("button");
    doneBtn.classList.add("finish-todo");
    doneBtn.innerHTML = '<i class="bi-check-lg"></i>';
    todo.appendChild(doneBtn);

    const editBtn = document.createElement("button");
    editBtn.classList.add("edit-todo");
    editBtn.innerHTML = '<i class="bi-pencil"></i>';
    todo.appendChild(editBtn);

    const deleteBtn = document.createElement("button");
    deleteBtn.classList.add("remove-todo");
    deleteBtn.innerHTML = '<i class="bi-trash3-fill"></i>';
    todo.appendChild(deleteBtn);

    todoList.appendChild(todo);
    todoInput.value = "";
    todoInput.focus();
}

// Adiciona nova tarefa visualmente
const saveTodo = (text) => {
    renderTodo({title: text});

    $.ajax({
        url: `/backend/actions.php`,
        method: 'POST',
        data: { title: text },
        success: (response) => {
            console.log(response)
            const data = JSON.parse(response);
            if (data.status === 'success') {
                renderAllTodos(data.todos);
            } else {
                alert('Erro ao buscar tarefas.');
            }
        }
    })
};

// Alterna entre formulário de edição e criação
const toggleForms = () => {
    editForm.classList.toggle("hide");
    todoForm.classList.toggle("hide");
    todoList.classList.toggle("hide");
};

// Atualiza visualmente uma tarefa
const updateTodo = (text) => {
    const todos = document.querySelectorAll(".todo");

    todos.forEach((todo) => {
        let todoTitle = todo.querySelector("h3");
        if (todoTitle.innerText === oldInputValue) {
            todoTitle.innerText = text;
        }
    });
};

// Cancelar edição
cancelEditBtn.addEventListener("click", (e) => {
    e.preventDefault();
    toggleForms();
});

// Atualiza tarefa após edição
editForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const id = editForm.dataset.id;
    const title = editInput.value.trim();

    if (title !== "") {
        updateTodoBackend(id, title);
        toggleForms();
    }
});

// Adiciona nova tarefa (formulário)
todoForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const inputValue = todoInput.value.trim();

    if (inputValue !== "") {
        saveTodo(inputValue);
    }
});

// Detecta ações nos botões das tarefas
document.addEventListener("click", (e) => {
    const targetEl = e.target;
    const parentEl = targetEl.closest("div.todo");
    let todoTitle;

    if (parentEl && parentEl.querySelector("h3")) {
        todoTitle = parentEl.querySelector("h3").innerText;
    }

    if (targetEl.classList.contains("finish-todo")) {
        const parentEl = targetEl.closest(".todo");
        const todoId = parentEl.getAttribute("data-id"); 
        const isDone = parentEl.classList.contains("done"); 

        parentEl.classList.toggle("done");

        const newStatus = isDone ? 'pendente' : 'concluida';
        $.ajax({
            url: "/backend/actions.php",
            method: "POST",
            data: {
                updateTodoStatus: true,
                id: todoId,
                status: newStatus
            },
            success: (response) => {
                const data = JSON.parse(response);
                if (data.status !== "success") {
                    alert("Erro ao atualizar o status da tarefa.");
                    parentEl.classList.toggle("done");
                }
            },
            error: () => {
                alert("Erro na requisição AJAX.");
                parentEl.classList.toggle("done");
            }
        });
    }

    if (targetEl.classList.contains("remove-todo")) {
        const id = parentEl.dataset.id;
        if (id) {
            deleteTodoBackend(id);
            parentEl.remove();
        } else {
            parentEl.remove();
        }
    }

    if (targetEl.classList.contains("edit-todo")) {
        toggleForms();
        editInput.value = todoTitle;
        oldInputValue = todoTitle;
        editForm.dataset.id = parentEl.dataset.id || "";
    }
});

// Limpa todas as tarefas da tela
const clearAllTodos = () => {
    todoList.innerHTML = "";
};

// Busca tarefas no backend
const getAllTodos = () => {
    $.ajax({
        url: `/backend/actions.php`,
        method: 'GET',
        data: { getAll: true },
        success: (response) => {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                clearAllTodos();
                renderAllTodos(data.todos);
            } else {
                alert('Erro ao buscar tarefas.');
            }
        }
    });
};

// Renderiza tarefas retornadas do backend
const renderAllTodos = (todos) => {
    todos.forEach(todo => {
        renderTodo(todo);
    });
};

// Atualiza tarefa no backend
const updateTodoBackend = (id, title) => {
    $.ajax({
        url: '/backend/actions.php',
        method: 'PUT',
        data: { id: id, title: title },
        success: (response) => {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                updateTodo(title);
            } else {
                alert('Erro ao atualizar a tarefa.');
            }
        }
    });
};

// Deleta tarefa no backend
const deleteTodoBackend = (id) => {
    $.ajax({
        url: '/backend/actions.php',
        method: 'DELETE',
        data: { id: id },
        success: (response) => {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                alert('Tarefa deletada com sucesso.');
                const todoElement = document.querySelector(`[data-id="${id}"]`);
                if (todoElement) {
                    todoElement.remove();
                }
            } else {
                alert('Erro ao deletar a tarefa.');
            }
        }
    });
};
