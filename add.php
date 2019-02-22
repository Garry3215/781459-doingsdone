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

// обработка формы

if (isset($_POST['submit'])) {
    $form_data['name'] = $_POST['name'];
    $form_data['date'] = $_POST['date'];
    $wrong_data = [];
    if (!empty($_POST['name'])) {
        $form_name = text_clean($_POST['name']);
    }
    else {
        $wrong_data['name'] = "Введите название задачи";
    }
    $form_project = mysqli_real_escape_string($con, $_POST['project']);
    $sql = "SELECT id FROM project WHERE user_id = 1 AND name = (?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $form_project);
    $res = mysqli_stmt_execute($stmt);
    $form_project = (int)$res;

    $form_date = text_clean($_POST['date']);

    $form_date_str = strtotime($form_date);
    $form_date = (date("Y-m-d H:i:s", $form_date_str));
    $cur_date = strtotime('now');
    if (($cur_date - $form_date_str) > 0) {
        $wrong_data['date'] = "Указанная дата меньше текущей";
    }
    if (isset($_FILES['preview'])) {
        $file_size = $_FILES['preview']['size'];
        if ($file_size > 200000) {
            $wrong_data['file'] = "Размер файла превышает допустимое значение в 2 Мб";
        }
        else {
            $file_name = $_FILES['preview']['name'];
            $file_path = __DIR__ . '/';
            $file_url = '/' . $file_name;
            move_uploaded_file($_FILES['preview']['tmp_name'], $file_path . $file_name);
        }
    }
    if (empty($wrong_data)) {
        print("Можно отправлять");
        $sql = "insert into task (user_id, project_id, status, name, date_must_done) VALUES (1, ?, 0, ?, ?)";
//        db_insert_data($con, $sql, [$form_project, $form_name, $form_date_str]);
        print($form_project);
        print($form_name);
        print($form_date);
        $stmt = mysqli_prepare($con, $sql);
        $res = mysqli_stmt_bind_param($stmt, 'isi', $form_project, $form_name, $form_date);
        var_dump($stmt);
        mysqli_stmt_execute($stmt);
    }
}
// конец обработки формы


$page_content = include_template('add.php', [
    'tasks' => $tasks,
    'actual_tasks' => $actual_tasks,
    'show_complete_tasks' => $show_complete_tasks,
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


?>
