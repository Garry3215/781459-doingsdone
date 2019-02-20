<?php
require_once('functions.php');
require_once('data.php');
$con = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($con, "utf8");
if ($con == false) {
print("Ошибка подключения: " . mysqli_connect_error());
};

$project_category = user_projects (1, $con);

//обработка кликов по названиям проектов
if (isset($_GET['project_id'])) {
    $project_id = (int) $_GET['project_id'];
    $sql = "SELECT * FROM task WHERE user_id = 1";
    $result = mysqli_query($con, $sql);
    $user_project_id = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($user_project_id as $key => $value) {
        if ($project_id === $value['project_id']) {
            $tasks = user_tasks(1, $project_id, $con);
        }
        else {
            http_response_code(404);
        }
    }
}
else {
    $tasks = user_tasks(1, 0, $con);
}

//конец обработки

$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
  ]);
$layout_content = include_template('layout.php', [
    'project_category' => $project_category,
    'tasks' => $tasks,
    'content' => $page_content,
    'user_name' => 'Константин',
    'title' => 'Главная страница'
  ]);

print($layout_content);
?>
