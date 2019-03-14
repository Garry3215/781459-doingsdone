<?php
require_once 'init.php';
require_once 'session.php';

$project_category = user_projects($user_id, $con);
$tasks = user_tasks($user_id, 0, $con);
$actual_tasks = [];
$bad_search = false;
$show_check = true;

// Обратотка формы поиска
if (isset($_GET) && isset($_GET['search'])) {
    $search = text_clean($_GET['search']);
    $sql = "SELECT * FROM task WHERE user_id='$user_id' AND MATCH(name) AGAINST ('$search')";
    $res = mysqli_query($con, $sql);
    $actual_tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
    if (empty($actual_tasks)) {
        $bad_search = true;
    }
}
// Конец обратоткт формы поиска

// обработка ЧекБокса "выполненная задача"
    $task_id = array_keys($_POST);
    if (!empty($task_id)) {
        $task_id = (int) $task_id[0];
        $sql = "SELECT status FROM task WHERE id = '$task_id'";
        $res = mysqli_query($con, $sql);
        $task_status = mysqli_fetch_assoc($res);
        if ($task_status['status'] === '0') {
            $sql = "UPDATE task SET status = '1' WHERE id = '$task_id'";
        } else {
            $sql = "UPDATE task SET status = '0' WHERE id = '$task_id'";
        }
        $res = mysqli_query($con, $sql);
    }
// конец обработки ЧекБокса "выполненная задача"

//обработка кликов по названиям проектов
if (isset($_GET['project_id'])) {
    $project_id = (int) $_GET['project_id'];
    $project_id = text_clean($project_id);
    $actual_tasks = user_projects_cur($user_id, $project_id, $con);
    if (empty($actual_tasks)) {
        http_response_code(404);
    }
} elseif (empty($actual_tasks)) {
    $actual_tasks = user_tasks($user_id, 0, $con);
}


if (isset($_GET['tasks_switch'])) {
    if (($_GET['tasks_switch']) === "today") {
        $actual_tasks_cur = [];
        $cur_date = strtotime('today');
        foreach ($actual_tasks as $key => $value) {
            $value_date = strtotime($value['date_must_done']);
            if ($value_date === $cur_date) {
                $actual_tasks_cur[$key] = $value;
            }
        }
            $actual_tasks = $actual_tasks_cur;
    }
    if (($_GET['tasks_switch']) === "tomorrow") {
        $actual_tasks_cur = [];
        $cur_date = strtotime('today + 1 day');
        foreach ($actual_tasks as $key => $value) {
            $value_date = strtotime($value['date_must_done']);
            if ($value_date === $cur_date) {
                $actual_tasks_cur[$key] = $value;
            }
        }
        $actual_tasks = $actual_tasks_cur;
    }
    if (($_GET['tasks_switch']) === "lost") {
        $actual_tasks_cur = [];
        $cur_date = strtotime('today');
        foreach ($actual_tasks as $key => $value) {
            $value_date = strtotime($value['date_must_done']);
            if (!empty($value_date) && $value_date < $cur_date) {
                $actual_tasks_cur[$key] = $value;
            }
        }
        $actual_tasks = $actual_tasks_cur;
    }
}

if (isset($_GET['show_completed'])) {
    if ($_GET['show_completed'] === '1') {
        $show_check = false;
    }
}


//конец обработки




$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'actual_tasks' => $actual_tasks,
    'project_category' => $project_category,
    'bad_search' => $bad_search,
    'show_check' => $show_check
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
