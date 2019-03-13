<?php
error_reporting(E_ALL);
require_once 'functions.php';
require_once 'vendor/autoload.php';

$con = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($con, "utf8");
if ($con === false) {
    echo 'Ошибка подключения: ' . mysqli_connect_error();
}

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport->setUsername('keks@phpdemo.ru');
$transport->setPassword('htmlacademy');

$message = new Swift_Message("Напоминание о задачах");
$message->setFrom("keks@phpdemo.ru", "htmlacademy");

$mailer = new Swift_Mailer($transport);

$cur_date = strtotime('today');
$cur_date = date("Y-m-d", $cur_date);
$sql = "SELECT user_id, GROUP_CONCAT(name SEPARATOR ', ') as name FROM task WHERE date_must_done = '$cur_date' GROUP BY user_id";
$res = mysqli_query($con, $sql);
$result = mysqli_fetch_all($res, MYSQLI_ASSOC);
$number = mysqli_num_rows($res);
if ($number > 0) {
    foreach ($result as $key => $value) {
        $current = $value['user_id'];
        $sql = "SELECT * FROM users WHERE id = '$current'";
        $res = mysqli_query($con, $sql);
        $result_current = mysqli_fetch_assoc($res);
        $message->setTo([$result_current['email'] => $result_current['name']]);
        $message->setBody("Сегодня истекает срок исполнения следующих задач: " . $value['name']);
        $mailer->send($message);
    }
}
