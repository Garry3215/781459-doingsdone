<?php
require_once('functions.php');

$con = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($con, "utf8");
if ($con == false) {
    echo 'Ошибка подключения: ' . mysqli_connect_error();
}

session_start();



if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = 0;
}

$wrong_data = [];

if (isset($_POST['submit'])) {
    if (empty($_POST['name'])) {
        $wrong_data['name'] = "Введите название проекта";
    } else {

        foreach ($project_category as $key => $value) {
            if ($_POST['name'] == $value['name']) {
                $wrong_data['name'] = "Проект с таким именем уже существует";
            }
        }
    }
    if (empty($wrong_data)) {
        $safe_name = mysqli_real_escape_string($con, $_POST['name']);
        $sql = "INSERT into project (user_id, name) VALUES (?, ?)";

        $ins = db_insert_data($con, $sql, [$user_id, $safe_name]);
        header("Location:/index.php");
    }
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






$page_content = include_template('add_project.php', [
    'tasks' => $tasks,
    'actual_tasks' => $actual_tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'wrong_data' => $wrong_data
]);

$layout_content = include_template('layout.php', [
    'project_category' => $project_category,
    'tasks' => $tasks,
    'content' => $page_content,
    'title' => 'Главная страница'
]);

echo $layout_content;
