<?php
require_once('assembling.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if(!empty($_POST)){
    session_start();
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $redirect_var = login_user($users, $user_email, $user_password);
}

if (isset($redirect_var) && $redirect_var === 1){
    header('Location: /index.php');
}
$users_passwords = array_column($users, 'password');
$page_content = include_template('login.php', [
    'users_passwords' => $users_passwords
]);
$layout_content = include_template('layout_non_autorized.php', [
    'content' => $page_content
]);
print($layout_content);

?>
