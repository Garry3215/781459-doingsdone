<?php
require_once('functions.php');

$con = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($con, "utf8");
if ($con == false) {
    echo 'Ошибка подключения: ' . mysqli_connect_error();
}

session_start();
$_SESSION = [];

$project_category = user_projects(1, $con);
$tasks = user_tasks(1, 0, $con);
$actual_tasks = [];

//обработка кликов по названиям проектов
if (isset($_GET['project_id'])) {
    $project_id = (int) $_GET['project_id'];
    $actual_tasks = user_projects_cur($project_id, $con);
    if (empty($actual_tasks)) {
        http_response_code(404);
        die('404 Not Found');
    }
} else {
    $actual_tasks = user_tasks(1, 0, $con);
}
//конец обработки

$wrong_data = [];
$form_data = [];
$user_data = [];

if (isset($_POST['submit'])) {
    if (empty($_POST['email'])) {
        $wrong_data['email'] = "Введите e-mail";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $wrong_data['email'] = "Введите корректный e-mail";
    } else {
        $form_data['email'] = mysqli_real_escape_string($con, $_POST['email']);
        $safe_email = $form_data['email'];
    }
    if (empty($_POST['password'])) {
        $wrong_data['password'] = "Введите пароль";
    } else {
        $form_data['password'] = mysqli_real_escape_string($con, $_POST['password']);
    }
    if (empty($wrong_data)) {
        $sql = "SELECT * FROM users WHERE email = '$safe_email'";
        $result = mysqli_query($con, $sql);
        $count = mysqli_fetch_row($result);

        if ($count > 0) {
            $result = mysqli_query($con, $sql);
            $user_data = mysqli_fetch_assoc($result);

            if (password_verify($form_data['password'], $user_data['password'])) {
                $_SESSION['user_id'] = $user_data['id'];
                header("Location:/index.php");
            } else {
                $_SESSION = [];
                $wrong_data['auth'] = "Логин и/или пароль указаны не верно";
                
            }
        }

    }
}


$page_content = include_template('auth.php', [
    'tasks' => $tasks,
    'actual_tasks' => $actual_tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'wrong_data' => $wrong_data
]);

$layout_content = include_template('layout.php', [
    'project_category' => $project_category,
    'tasks' => $tasks,
    'content' => $page_content,
    'user_id' => $_SESSION,
    'title' => 'Главная страница'
]);

echo $layout_content;
