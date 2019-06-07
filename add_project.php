<?php
require_once('assembling.php');
session_start();
$categories = get_categories($con);
if (isset($_SESSION['id'])) {
    $tasks_full = get_tasks_by_categories($con, -1, $_SESSION['id']);
}
if (!empty($_POST['name'])) {
    $new_category_name = $_POST['name'];
    $redirect_var = add_category($con, $new_category_name);
}
if (isset($redirect_var) && $redirect_var === 1) {
    header('Location: index.php');
}
$page_content = include_template('add_project.php', []);
$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'tasks_full' => $tasks_full,
    'content' => $page_content,
    'title' => 'Дела в порядке',
    'choosen_project' => $choosen_project
]);
print($layout_content);
