<?php
require_once 'init.php';

$form_data = [];
$wrong_data = [];

//обработка кликов по названиям проектов
if (isset($_GET['project_id'])) {
    $project_id = (int) $_GET['project_id'];
    $project_id = text_clean($project_id);
    $actual_tasks = user_projects_cur($user_id, $project_id, $con);
    if (empty($actual_tasks)) {
        http_response_code(404);
        die('404 Not Found');
    }
} elseif (empty($actual_tasks)) {
    $actual_tasks = user_tasks($user_id, 0, $con);
}


// обработка формы
if (isset($_POST['submit'])) {
    $form_data['name'] = text_clean($_POST['name']);
    $form_data['date'] = text_clean($_POST['date']);
    $form_data['project_id'] = text_clean($_POST['project']);

    if (!empty($_POST['name'])) {
        $form_name = text_clean($_POST['name']);
    } else {
        $wrong_data['name'] = "Введите название задачи";
    }
    $form_project = text_clean($_POST['project']);
    $sql = "SELECT * FROM project WHERE id = '$form_project'";
    $res = mysqli_query($con, $sql);
    $count = mysqli_num_rows($res);
    if ($count <= 0) {
        $form_project = null;
    }

    $form_date = text_clean($_POST['date']);

    $form_date_str = strtotime($form_date);
    $form_date = (date("Y-m-d H:i:s", $form_date_str));
    if ($form_date === "1970-01-01 03:00:00") {
        $form_date = null;
    }
    $cur_date = strtotime('today');
    if (!empty($form_date_str) && ($cur_date - $form_date_str) > 0) {
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
        if ($file_path === null && $form_date !== null) {
            $sql = "insert into task (user_id, project_id, status, name, date_must_done) VALUES (?, ?, 0, ?, ?)";
            $ins = db_insert_data($con, $sql, [$user_id, $form_project, $form_name, $form_date]);
        } elseif ($form_date === null && $file_path !== null) {
            $sql = "insert into task (user_id, project_id, status, name, file) VALUES (?, ?, 0, ?, ?)";
            $ins = db_insert_data($con, $sql, [$user_id, $form_project, $form_name, $file_url]);
        } elseif ($form_date === null && $file_path === null) {
            $sql = "insert into task (user_id, project_id, status, name) VALUES (?, ?, 0, ?)";
            $ins = db_insert_data($con, $sql, [$user_id, $form_project, $form_name]);
        } else {
            $sql = "insert into task (user_id, project_id, status, name, date_must_done, file) VALUES (?, ?, 0, ?, ?, ?)";
            $ins = db_insert_data($con, $sql, [$user_id, $form_project, $form_name, $form_date, $file_url]);
        }
        header("Location:/index.php");
    }
}
// конец обработки формы


$project_category = user_projects($user_id, $con);
$tasks = user_tasks($user_id, 0, $con);

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
    'actual_tasks' => $actual_tasks,
    'con' => $con,
    'title' => 'Главная страница'
  ]);

print($layout_content);
