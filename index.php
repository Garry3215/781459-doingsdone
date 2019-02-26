<?php
require_once('functions.php');

$con = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($con, "utf8");
if ($con == false) {
    echo 'Ошибка подключения: ' . mysqli_connect_error();
}

session_start();

if (!isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
} else {
    $user_id = 0;
}

$project_category = user_projects($user_id, $con);
$tasks = user_tasks($user_id, 0, $con);
$actual_tasks = [];

//обработка кликов по названиям проектов
if (isset($_GET['project_id'])) {
    $project_id = (int) $_GET['project_id'];
    $actual_tasks = user_projects_cur($user_id, $project_id, $con);
    if (empty($actual_tasks)) {
        http_response_code(404);
        die('404 Not Found');
    }
} else {
    $actual_tasks = user_tasks($user_id, 0, $con);
}
//конец обработки

$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'actual_tasks' => $actual_tasks,
    'show_complete_tasks' => $show_complete_tasks
]);

$layout_content = include_template('layout.php', [
    'project_category' => $project_category,
    'tasks' => $tasks,
    'content' => $page_content,
    'title' => 'Главная страница'
]);

echo $layout_content;
