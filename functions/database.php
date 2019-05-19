<?php

/**
 * Формирует sql запрос на получение данных о категориях
 * @param myslqli $con Хранит данные о текущем подключении
 * @return mysqli_fetch_all возвращает результат запроса в ассоциативный массив
 */
function get_categories(mysqli $con){
    $sql_categories = 'SELECT name,id,alias FROM project
                    WHERE user = 3;';
    $res_categories = mysqli_query($con, $sql_categories);
    return mysqli_fetch_all($res_categories, MYSQLI_ASSOC);
}

/**
* @param myslqli $con Хранит данные о текущем подключении
* @param int $id_choosen_project id выбранной категории
* @return mysqli_fetch_all возвращает результат запроса в ассоциативный массив
*/
function get_tasks_by_categories(mysqli $con, $id_choosen_project){

    if($id_choosen_project === -1){
        $sql_tasks = 'SELECT t.title,t.project_id,t.user_id,t.status,t.date_create,t.file FROM user u
                INNER JOIN task t
                ON u.id = t.user_id
                WHERE u.id = 3;';
        $res_tasks = mysqli_query($con , $sql_tasks);
        return mysqli_fetch_all($res_tasks, MYSQLI_ASSOC);
    }
    else
    {
        $sql_tasks = 'SELECT t.title,t.project_id,t.user_id,t.status,t.date_create,t.file FROM user u
                INNER JOIN task t
                ON u.id = t.user_id
                WHERE u.id = 3
                AND t.project_id = '.$id_choosen_project.';';
        $res_tasks = mysqli_query($con , $sql_tasks);
        return mysqli_fetch_all($res_tasks, MYSQLI_ASSOC);
    }
}

/**
* @param mysqli $con хранит данные о текущем подключении
* @param array $categories массив с текущими проектами
* @param array $tasks массив с текущими задачами
*/
function add_task(mysqli $con, $categories, $tasks){
    if(!empty($_FILES)){
        $file_uri = move_file_to_uploads();
    }
    if(!empty($_POST['name']) && !empty($_POST['project']) && strtotime($_POST['date'])>=time()){
        //echo 'add file if ok!';
        $task_name = $_POST['name'];
        $task_project = $_POST['project'];
        $sql_add_task = 'INSERT INTO task (user_id,project_id,status,title,file) VALUES
                                             (3,"'.$task_project.'",0,"'.$task_name.'","'.$file_uri.'")';
        $res_sql_add_task = mysqli_query($con, $sql_add_task);
        if($res_sql_add_task === false){
                die('Error while working with SQL request'.mysqli_error($con));
             }
        return 1;
     }
}

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
$tasks_of_category = get_tasks_by_categories($con, $id_choosen_project);
$tasks = get_tasks_by_categories($con,$id_choosen_project);

if(!$con){
    print('Error'.mysqli_connect_error());
}
else {

    if(empty($tasks) && empty($categories)){
        die('Error while working with SQL request'.mysqli_error($con));
    }
};

?>
