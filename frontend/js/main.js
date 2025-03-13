$(document).ready(function() {
    function loadTasks() {
        console.log("Loading tasks...");
        $.ajax({
            url: '/api/tasks', // Alterado
            method: 'GET',
            success: function(data) {
                console.log("Tasks loaded:", data);
                $('#tasks-table-body').empty();
                data.forEach(function(task) {
                    $('#tasks-table-body').append(`
                        <tr>
                            <td>${task.id}</td>
                            <td>${task.title}</td>
                            <td>${task.description}</td>
                            <td>${task.status}</td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="updateTask(${task.id}, 'concluída')">Concluir</button>
                                <button class="btn btn-warning btn-sm" onclick="updateTask(${task.id}, 'pendente')">Reabrir</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">Excluir</button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status, error) {
                console.error("Failed to load tasks:", status, error);
            }
        });
    }

    $('#create-task-form').submit(function(event) {
        event.preventDefault();
        const task = {
            title: $('#title').val(),
            description: $('#description').val(),
            status: $('#status').val()
        };
        console.log("Creating task:", task);
        $.ajax({
            url: '/api/tasks', // Alterado
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(task),
            success: function() {
                console.log("Task created successfully");
                loadTasks();
                $('#create-task-form')[0].reset();
            },
            error: function(xhr, status, error) {
                console.error("Failed to create task:", status, error);
            }
        });
    });

    window.updateTask = function(id, status) {
        console.log("Updating task:", id, status);
        $.ajax({
            url: `/api/tasks/${id}`, // Alterado
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify({ status: status }),
            success: function() {
                console.log("Task updated successfully");
                loadTasks();
            },
            error: function(xhr, status, error) {
                console.error("Failed to update task:", status, error);
            }
        });
    };

    window.deleteTask = function(id) {
        console.log("Deleting task:", id);
        $.ajax({
            url: `/api/tasks/${id}`, // Alterado
            method: 'DELETE',
            success: function() {
                console.log("Task deleted successfully");
                loadTasks();
            },
            error: function(xhr, status, error) {
                console.error("Failed to delete task:", status, error);
            }
        });
    };

    loadTasks();
});