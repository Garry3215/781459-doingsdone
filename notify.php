<?php
require_once 'functions.php';
require_once 'vendor/autoload.php';

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

$cur_date = strtotime('today');
$cur_date = date("Y-m-d H:i:s", $cur_date);
$sql = "SELECT * FROM task WHERE status = 0 AND user_id = '$user_id' AND date_must_done = '$cur_date'";
$res = mysqli_query($con, $sql);
$result = mysqli_fetch_assoc($res);
$number = mysqli_num_rows($res);
if ($number > 0) {
    $today_task_name = null;
    $today_task_date = null;
    foreach ($res as $key => $value) {
        if ($today_task_name === null) {
            $today_task_name = $value['name'];
        } else {
            $today_task_name = $today_task_name . ", " . $value['name'];
        }
        if ($today_task_date === null) {
            $today_task_date = Date_DB_to_Man($value['date_must_done']);
        } else {
            $today_task_date = $today_task_date . ", " . Date_DB_to_Man($value['date_must_done']);
        }
    }
}
$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport->setUsername('keks@phpdemo.ru');
$transport->setPassword('htmlacademy');

$message = new Swift_Message("Напоминание о задачах");
$message->setTo([$_SESSION['user_email'] => $_SESSION['user_name']]);
$message->setBody("Сегодня истекает срок исполнения следующих задач: " . $today_task_name);
$message->setFrom("keks@phpdemo.ru", "htmlacademy");

$mailer = new Swift_Mailer($transport);
$mailer->send($message);
