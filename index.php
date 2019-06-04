<?php
require_once('assembling.php');

session_start();
//var_dump($_SESSION);
//var_dump($_GET);
$categories = get_categories($con);
$users = get_users($con);

if(isset($_GET['show_completed'])){
    $_SESSION['show_completed'] = $_GET['show_completed'];
}
if(isset($_GET['category'])&& !empty($_SESSION)){
    $_SESSION['category'] = $_GET['category'];
}

if(isset($_GET['task_id'])){
    set_completed($con, $_GET['task_id']);
}
$tasks_full = get_tasks_by_categories($con, -1);
if($_GET['category'] === 'null'){
    //echo 'usual';
    $tasks = get_tasks_by_categories($con,-1);
}
else{
    $tasks = get_tasks_by_categories($con, intval($_SESSION['category']));
}

if(!empty($_GET['sorting']) && !empty($tasks) && $_GET['sorting'] !=='all'){
    //var_dump($tasks);
    echo 'look next!';
    $tasks = get_tasks_by_sorting($_GET['sorting'], $tasks);
    //$_SESSION['sorting'] = $_GET['sorting'];
    //var_dump($tasks);
}
else{
    //$tasks = get_tasks_by_sorting($con,'all');
}


// if(empty($_GET['sorting'])){
//     $_SESSION['sorting'] = 'all';
// }

if( isset($_GET['exit']) && $_GET['exit'] === 'true'){
    session_destroy();
    header('Location: pages/guest.php');
}
if(empty($_SESSION['name']) || $_SESSION['name'] === 'null'){
    header('Location: pages/guest.php');
}

if(isset($_GET['search-form']) && !empty($_SESSION)){
    $tasks = get_tasks_by_search($con, trim($_GET['search-form']));
    if(empty($tasks)){
        $_SESSION['search-result'] = 'no';
    }
    else{
        $_SESSION['search-result'] = 'yes';
    }
}

if(empty($_GET['search-form']) && !empty($_SESSION['search-result'])){
    $_SESSION['search-result'] = 'null';
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$null_date = '1970-01-01';

//var_dump($_SESSION);
//var_dump($tasks);
$page_content = include_template('index.php', [
    'tasks' => $tasks,
    'null_date' => $null_date
]);
$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'tasks_full' => $tasks_full,
    'content' => $page_content,
    'title' => 'Дела в порядке',
    'choosen_project' => $choosen_project
]);
print($layout_content);

?>
