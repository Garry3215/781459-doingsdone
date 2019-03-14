<?php
error_reporting(E_ALL);
require_once 'functions.php';
require_once 'vendor/autoload.php';



$con = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($con, "utf8");
if ($con === false) {
    echo 'Ошибка подключения: ' . mysqli_connect_error();
}
