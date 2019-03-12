<?php
error_reporting(E_ALL);
require_once 'functions.php';

$con = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($con, "utf8");
if ($con == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

$project_category = user_projects(1, $con);
$tasks = user_tasks(1, 0, $con);
$actual_tasks = [];

$wrong_data = [];
$form_data = [];

if (isset($_POST['submit'])) {
    $safe_email = mysqli_real_escape_string($con, $_POST['email']);
    $sql = "SELECT * FROM users WHERE email = '$safe_email'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_fetch_row($result);
    if (empty($_POST['email'])) {
        $wrong_data['email'] = "Введите e-mail";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $wrong_data['email'] = "Введите корректный e-mail";
    } elseif ($count > 0) {
        $wrong_data['email'] = "Данный e-mail уже используется";
    } else {
        $form_data['email'] = mysqli_real_escape_string($con, $_POST['email']);
    }
    if (empty($_POST['password'])) {
        $wrong_data['password'] = "Введите пароль";
    } else {
        $form_data['password'] = $_POST['password'];
        $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
    }
    if (empty($_POST['name'])) {
        $wrong_data['name'] = "Введите имя";
    } else {
        $form_data['name'] = mysqli_real_escape_string($con, $_POST['name']);
    }
    if (empty($wrong_data)) {
        $sql = "insert into users (email, password, name) VALUES (?, ?, ?)";
        $ins = db_insert_data($con, $sql, [$form_data['email'], $form_data['password'], $form_data['name']]);
        header("Location:/index.php");
    }
}



$page_content = include_template('register.php', [
    'tasks' => $tasks,
    'actual_tasks' => $actual_tasks,
    'project_category' => $project_category,
    'wrong_data' => $wrong_data,
    'form_data' => $form_data
  ]);
$layout_content = include_template('layout.php', [
    'project_category' => $project_category,
    'tasks' => $tasks,
    'content' => $page_content,
    'user_name' => 'Константин',
    'title' => 'Главная страница'
  ]);

print($layout_content);
