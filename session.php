<?php
session_start();


if (isset($_SESSION['user_id'])) {
    $user_id = text_clean($_SESSION['user_id']);
} else {
    $user_id = 0;
}
