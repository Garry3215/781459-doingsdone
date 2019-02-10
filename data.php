<?php

$project_category = ["Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
$tasks = [
  [
    'name' => 'Собеседование в IT компании',
    'date' => '10.02.2019',
    'category' => 'Работа',
    'done' => false
  ],
  [
    'name' => 'Выполнить тестовое задание',
    'date' => '25.12.2019',
    'category' => 'Работа',
    'done' => false
  ],
  [
    'name' => 'Сделать задание первого раздела',
    'date' => '21.12.2019',
    'category' => 'Учеба',
    'done' => true
  ],
  [
    'name' => 'Встреча с другом',
    'date' => '22.12.2019',
    'category' => 'Входящие',
    'done' => false
  ],
  [
    'name' => 'Купить корм для кота',
    'date' => 'Срочно!!!!!!!',
    'category' => 'Домашние дела',
    'done' => false
  ],
  [
    'name' => 'Заказать пиццу',
    'date' => 'Нет',
    'category' => 'Домашние дела',
    'done' => false
  ],
];

$show_complete_tasks = rand(0, 1);
?>
