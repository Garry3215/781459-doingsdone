<?php
require_once('functions.php');
require_once('data.php');
$page_content = include_template('templates\index.php', ['tasks' => $tasks]);
$layout_content = include_template('templates\layout.php', [
    'content' => $page_content,
    'user_name' => 'Константин',
    'title' => 'Главная страница'
]);
print($layout_content);
?>
