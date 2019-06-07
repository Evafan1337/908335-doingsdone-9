<?php
require_once('assembling.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if (!empty($_POST)) {
    $user_name = $_POST['name'];
    $user_password = $_POST['password'];
    $user_email = $_POST['email'];
    $redirect_var = add_user($user_name, $user_password, $user_email, $users, $con);
}

if (isset($redirect_var) && $redirect_var === 1) {
    header('Location: /index.php');
}
$users_email_list = get_emails_list($users);
$page_content = include_template('registration.php', [
    'users' => $users,
    'users_email_list' => $users_email_list
]);
$layout_content = include_template('layout_non_autorized.php', [
    'content' => $page_content
]);
print($layout_content);
