<?php
require_once 'init.php';

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport->setUsername('keks@phpdemo.ru');
$transport->setPassword('htmlacademy');

$message = new Swift_Message("Уведомление от сервиса «Дела в порядке»");
$message->setFrom("keks@phpdemo.ru", "htmlacademy");

$mailer = new Swift_Mailer($transport);

$cur_date = strtotime('today');
$cur_date = date("Y-m-d", $cur_date);
$sql = "SELECT user_id, name, date_must_done FROM task WHERE status = 0 AND date_must_done = '$cur_date'";
$res = mysqli_query($con, $sql);
$result = mysqli_fetch_all($res, MYSQLI_ASSOC);
$list = [];
foreach ($result as $row) {
  $list[] = $row['name'] . ' на ' . date('H:i', strtotime($row['date_must_done']));
}
$number = mysqli_num_rows($res);
if ($number > 0) {
    foreach ($result as $key => $value) {
        $current = $value['user_id'];
        $sql = "SELECT * FROM users WHERE id = '$current'";
        $res = mysqli_query($con, $sql);
        $result_current = mysqli_fetch_assoc($res);
        $message->setTo([$result_current['email'] => $result_current['name']]);
        $message->setBody("Уважаемый, " . $result_current['name'] . ". У вас запланирована задача (задачи): " . implode(', ', $list));
        $mailer->send($message);
    }
}
