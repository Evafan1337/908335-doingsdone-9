<?php
require_once('assembling.php');
$show_complete_tasks = 1;
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$page_content = include_template('index.php', [
    'tasks' => $tasks,
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
