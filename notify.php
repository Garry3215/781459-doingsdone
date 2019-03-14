<?php
require_once 'init.php';

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport->setUsername('keks@phpdemo.ru');
$transport->setPassword('htmlacademy');

$message = new Swift_Message("Уведомление от сервиса «Дела в порядке»");
$message->setFrom("keks@phpdemo.ru", "htmlacademy");

$mailer = new Swift_Mailer($transport);

$start_date = date('Y-m-d H:i:s', time());
$end_date = date('Y-m-d H:i:s', strtotime('+1 day'));
$sql = "SELECT u.name AS user_name, u.email AS user_email, u.id AS user_id, t.name, t.date_must_done FROM task t LEFT JOIN users u ON u.id = t.user_id WHERE t.status = 0 AND t.date_must_done BETWEEN '$start_date' AND '$end_date'";
$res = mysqli_query($con, $sql);
$array = [];
while ($row = mysqli_fetch_assoc($res)) {
	if (!isset($array[$row['user_id']])) {
		$array[$row['user_id']] = [
			'name' => $row['user_name'],
			'email' => $row['user_email'],
		];
	}
	$array[$row['user_id']]['tasks'][] = $row;
}

if (!empty($array)) {
    foreach ($array as $key => $row) {
        $list = [];
		foreach ($row['tasks'] as $task) {
			$list[] = $task['name'] . ' на ' . date('H:i', strtotime($task['date_must_done']));
		}
        $message->setTo([$row['email'] => $row['name']]);
        $message->setBody("Уважаемый, " . $row['name'] . ". У вас запланирована задача (задачи): " . implode(', ', $list));
        $mailer->send($message);
    }
}
