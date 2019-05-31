<?php
require_once('assembling.php');
session_start();
if(empty($_SESSION['name'])){
    header('Location: pages/guest.php');
}
// var_dump($_POST);
// var_dump($_SESSION);
$show_complete_tasks = 1;
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$null_date = '1970-01-01';

$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'null_date' => $null_date,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'content' => $page_content,
    'title' => 'Дела в порядке',
    'choosen_project' => $choosen_project
]);
print($layout_content);

?>
