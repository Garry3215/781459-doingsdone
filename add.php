<?php
error_reporting(E_ALL);
require_once 'functions.php';

$con = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($con, "utf8");
if ($con == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = 0;
}

$form_data = [];
$wrong_data = [];


// обработка формы



if (isset($_POST['submit'])) {
    $form_data['name'] = $_POST['name'];
    $form_data['date'] = $_POST['date'];
    $form_data['project_id'] = $_POST['project'];

    if (!empty($_POST['name'])) {
        $form_name = text_clean($_POST['name']);
    } else {
        $wrong_data['name'] = "Введите название задачи";
    }
    $form_project = text_clean($_POST['project']);

    $form_date = text_clean($_POST['date']);

    $form_date_str = strtotime($form_date);
    $form_date = (date("Y-m-d H:i:s", $form_date_str));
    $cur_date = strtotime('today');
    if (($cur_date - $form_date_str) > 0) {
        $wrong_data['date'] = "Указанная дата меньше текущей";
    }
    if (isset($_FILES['preview'])) {
        if (empty($_FILES['preview']['name'])) {
            $file_path = null;
        } else {
            $file_size = $_FILES['preview']['size'];
            if ($file_size > 200000) {
                $wrong_data['file'] = "Размер файла превышает допустимое значение в 2 Мб";
            } else {
                $file_name = $_FILES['preview']['name'];
                $file_path = __DIR__ . '/';
                $file_url = '/' . $file_name;
                move_uploaded_file($_FILES['preview']['tmp_name'], $file_path . $file_name);
                $file_path = $file_path . $file_name;
            }
        }
    }
    if (empty($wrong_data)) {
        if ($file_path == null) {
            $sql = "insert into task (user_id, project_id, status, name, date_must_done) VALUES (?, ?, 0, ?, ?)";
            $ins = db_insert_data($con, $sql, [$user_id, $form_project, $form_name, $form_date]);
        } else {
            $sql = "insert into task (user_id, project_id, status, name, date_must_done, file) VALUES (?, ?, 0, ?, ?, ?)";
            $ins = db_insert_data($con, $sql, [$user_id, $form_project, $form_name, $form_date, $file_path]);
        }

        header("Location:/index.php");
    }
}
// конец обработки формы


$project_category = user_projects($user_id, $con);
$tasks = user_tasks($user_id, 0, $con);

//обработка кликов по названиям проектов
if (isset($_GET['project_id'])) {
    $project_id = (int) $_GET['project_id'];
    $actual_tasks = user_projects_cur($project_id, $con);
    if (empty($actual_tasks)) {
        http_response_code(404);
        die('404 Not Found');
    }
} else {
    $actual_tasks = user_tasks($user_id, 0, $con);
}
//конец обработки


$page_content = include_template('add.php', [
    'tasks' => $tasks,
    'project_category' => $project_category,
    'actual_tasks' => $actual_tasks,
    'wrong_data' => $wrong_data,
    'form_data' => $form_data
  ]);
$layout_content = include_template('layout.php', [
    'project_category' => $project_category,
    'tasks' => $tasks,
    'content' => $page_content,
    'title' => 'Главная страница'
  ]);

print($layout_content);
