<?php
require_once('data.php');
require_once('functions.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$con = mysqli_connect('localhost','root','2k91abin1420pirzy','doingsdone');

mysqli_set_charset($con, 'utf8');

$categories = get_categories($con);

if (!empty($_GET['category'])) {
    $choosen_project = $_GET['category'];
};
if (empty($_GET['category']))
{
    $_GET['category']='null';
    $id_choosen_project = -1;
};
if (isset($_GET['category']) && !empty($_GET['category'])){
    $choosen_project = $_GET['category'];
};

if($choosen_project!='null' && !empty($categories)){
    foreach ($categories as $category) {
        if($category['alias'] === $choosen_project){
            $id_choosen_project = $category['id'];
        }
    }
}
else $id_choosen_project = -1;
check_response($categories,$choosen_project);
$tasks = get_tasks_by_categories($con,$id_choosen_project);

if(!$con){
    print('Error'.mysqli_connect_error());
}
else {

    if(empty($tasks) && empty($categories)){
        die('Error while working with SQL request'.mysqli_error($con));
    }
};
view_tasks($categories,$tasks);
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
