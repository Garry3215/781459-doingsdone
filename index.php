<?php
require_once('functions.php');

$con = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($con, "utf8");
if ($con == false) {
print("Ошибка подключения: " . mysqli_connect_error());
}

$project_category = user_projects (1, $con);
$tasks = user_tasks(1, 0, $con);

//обработка кликов по названиям проектов
if (isset($_GET['project_id'])) {
    $project_id = (int) $_GET['project_id'];
    $actual_tasks = user_projects_cur($project_id, $con);
}
else {
    $actual_tasks = user_tasks(1, 0, $con);
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
    'user_name' => 'Константин',
    'title' => 'Главная страница'
  ]);

print($layout_content);
?>
