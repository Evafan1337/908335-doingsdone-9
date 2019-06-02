<?php
require_once('assembling.php');
session_start();
$categories = get_categories($con);
$users = get_users($con);
if(!empty($_POST) && !empty($_POST['name']) && !empty($_POST['project'])){
    $task_name = $_POST['name'];
    $task_project = $_POST['project'];
    if(empty($_POST['date'])){
        $task_date = date('Y-m-d',strtotime('1970-01-01'));
    }
    else {
        $task_date = $_POST['date'];
    }
    $redirect_var = add_task($con,$categories, $tasks, $task_name, $task_project, $task_date);
}
if(isset($redirect_var) && $redirect_var === 1){
    header('Location: index.php');
}
$content = include_template( 'add.php',[
    'categories' => $categories
]);
$layout_content = include_template ('layout.php',[
    'content' => $content,
    'categories' => $categories,
    'title' => 'Дела в порядке',
    'tasks' => $tasks,
    'choosen_project' => $choosen_project
]);
print($layout_content);
?>