<?php
require_once 'init.php';
require_once 'session.php';

$wrong_data = [];
$project_category = user_projects($user_id, $con);


if (isset($_POST['submit'])) {
    if (empty($_POST['name'])) {
        $wrong_data['name'] = "Введите название проекта";
    } else {
        foreach ($project_category as $key => $value) {
            if ($_POST['name'] === $value['name']) {
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


$tasks = user_tasks($user_id, 0, $con);
$actual_tasks = [];


$page_content = include_template('add_project.php', [
    'tasks' => $tasks,
    'actual_tasks' => $actual_tasks,
    'wrong_data' => $wrong_data
]);

$layout_content = include_template('layout.php', [
    'project_category' => $project_category,
    'tasks' => $tasks,
    'content' => $page_content,
    'actual_tasks' => $actual_tasks,
    'con' => $con,
    'title' => 'Главная страница'
]);

echo $layout_content;
