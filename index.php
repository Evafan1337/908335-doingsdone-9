<?php
require_once('data.php');
require_once('functions.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$con = mysqli_connect('localhost','root','2k91abin1420pirzy','doingsdone');

mysqli_set_charset($con, 'utf8');

$categories = [];
$tasks = [];

if(!$con){
    print('Error'.mysqli_connect_error());
}
else {
    $sql_tasks = 'SELECT t.title,t.project_id,t.user_id,t.status,t.date_create FROM user u
                INNER JOIN task t
                ON u.id = t.user_id
                WHERE u.id = 3;';

    $sql_categories = 'SELECT name,id FROM project
                    WHERE user = 3;';
    $res_tasks = mysqli_query($con, $sql_tasks);
    $res_categories = mysqli_query($con, $sql_categories);

    if($res_tasks === false && $res_categories === false){
        die('Error while working with SQL request'.mysqli_error($con));
    }

    $tasks = mysqli_fetch_all($res_tasks, MYSQLI_ASSOC);
    $categories = mysqli_fetch_all($res_categories, MYSQLI_ASSOC);
};

$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);
print($layout_content);
?>
