<?php
require_once('assembling.php');
session_start();
$categories = get_categories($con);
$users = get_users($con);
if($_GET['category'] === 'null'){
    $tasks = get_tasks_by_categories($con,-1);
}
else{
    $tasks = get_tasks_by_categories($con, intval($_GET['category']));
}

if( isset($_GET['exit']) && $_GET['exit'] === 'true'){
    session_destroy();
    header('Location: pages/guest.php');
}
if(empty($_SESSION['name']) || $_SESSION['name'] === 'null'){
    header('Location: pages/guest.php');
}
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
