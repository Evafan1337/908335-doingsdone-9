<?php
require_once('data.php');
require_once('functions.php');
//$b = htmlspecialchars("<a href='test'>Test</a>", ENT_QUOTES, 'UTF-8');
//echo "$b";
$page_content = include_template('index.php',['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks ]);
$layout_content = include_template('layout.php',['categories' => $categories, 'tasks' => $tasks, 'content' => $page_content, 'title' => 'Дела в порядке']);
print($layout_content);
?>

