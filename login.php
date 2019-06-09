<?php
require_once('assembling.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$errors = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    if (empty($user_email)) {
        $errors['email'] = 'Пустые поля не допустимы';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите email';
    } else {
        $errors['email'] = 'Введите корректный email';
    }
    if (check_user($con, $user_email, $user_password)) {
        header('Location: /index.php');
    }
    if (empty($user_password)) {
        $errors['password'] = 'Пустые поля не допустимы';
    } else {
        $errors['password'] = 'Введите корректный пароль';
    }
}
$users_passwords = array_column($users, 'password');
$page_content = include_template('login.php', [
    'users_passwords' => $users_passwords,
    'errors' => $errors
]);
$layout_content = include_template('layout_non_autorized.php', [
    'content' => $page_content
]);
print($layout_content);
