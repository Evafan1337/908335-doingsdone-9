<?php

/**
 * Формирует sql запрос на получение данных о категориях
 * @param myslqli $con Хранит данные о текущем подключении
 * @return mysqli_fetch_all возвращает результат запроса в ассоциативный массив
 */
function get_categories(mysqli $con)
{
        $user_id = mysqli_real_escape_string($con, $_SESSION['id']);
        $sql_categories = 'SELECT name,id,alias FROM project
                    WHERE user = "'.$user_id.'";';
        $res_categories = mysqli_query($con, $sql_categories);
        return mysqli_fetch_all($res_categories, MYSQLI_ASSOC);
    return 0;
}

/**
* Функция, отправляющая запрос в БД и сохраняющая данные пользователей из БД в ассоциативном массиве
* @param mysqli $con Хранит данные о текущем подключении
* @return mysqli_fetch_all($res_users, MYSQLI_ASSOC) вывод функции, заполняющей ассоциативный массив
*/
function get_users(mysqli $con)
{
    $sql_users = 'SELECT id,reg_date,email,name,password FROM user;';
    $res_users = mysqli_query($con, $sql_users);
    return mysqli_fetch_all($res_users, MYSQLI_ASSOC);
}

/**
* Функция выбора задач, соответствующих выбранному проекту
* @param myslqli $con Хранит данные о текущем подключении
* @param int $id_choosen_project id выбранной категории
* @param int $user_id id текущего пользователя
* @return mysqli_fetch_all возвращает результат запроса в ассоциативный массив
*/
function get_tasks_by_categories(mysqli $con, $id_choosen_project, $user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql_tasks = 'SELECT t.title,t.task,t.project_id,t.user_id,t.status,t.date_create,t.deadline,t.file FROM user u
            INNER JOIN task t
            ON u.id = t.user_id
            WHERE u.id = "'.$user_id.'";';
    $res_tasks = mysqli_query($con, $sql_tasks);
    if (!empty($_SESSION) && $id_choosen_project !== -1) {
        $id_choosen_project = mysqli_real_escape_string($con, $id_choosen_project);
        $sql_tasks = 'SELECT t.title,t.task,t.project_id,t.user_id,t.status,t.date_create,t.deadline,t.file FROM user u
                INNER JOIN task t
                ON u.id = t.user_id
                WHERE u.id = "'.$user_id.'"
                AND t.project_id = "'.$id_choosen_project.'";';
        $res_tasks = mysqli_query($con, $sql_tasks);
    }
    return mysqli_fetch_all($res_tasks, MYSQLI_ASSOC);
}

/**
* Функция выбора задач, соответствующих сортировке
* @param string $sorting_condition состояние условия сортировки
* @param array $tasks массив текущих задач
* @return array $tasks обработанный масив текущих задач
*/
function get_tasks_by_sorting($sorting_condition, $tasks)
{
    if (is_array($tasks)) {
        $sorting_marker = false;
        $date_tomorrow = strtotime("+1 day");
        $yesterday_23_59 = strtotime('today midnight');
        $yesterday_23_59--;
        foreach ($tasks as $key => $task) {
            if ($sorting_condition === 'today' && ($tasks[$key]['deadline']) !== date('Y-m-d')) {
                unset($tasks[$key]);
                $sorting_marker = true;
            } elseif ($sorting_condition === 'tomorrow' && ($tasks[$key]['deadline']) !== date('Y-m-d', $date_tomorrow)) {
                unset($tasks[$key]);
                $sorting_marker = true;
            }elseif ($sorting_condition ==='outdated' && (strtotime($tasks[$key]['deadline']) > $yesterday_23_59) && $tasks[$key]['status'] !== '1' && $sorting_marker === false || $tasks[$key]['deadline'] === '1970-01-01') {
                unset($tasks[$key]);
            }
        }
        return $tasks;
    }
    return 0;
}

/**
* Функция отбора задач по поиску
* @param myslqli $con Хранит данные о текущем подключении
* @param string $search_word слово для поиска
* @return mysqli_fetch_all возвращает результат запроса в ассоциативный массив
*/
function get_tasks_by_search(mysqli $con, $search_word)
{
    $user_id = mysqli_real_escape_string($con, $_SESSION['id']);
    $search_word = mysqli_real_escape_string($con, $search_word);
    $sql_tasks = 'SELECT t.title,t.task,t.project_id,t.user_id,t.status,t.date_create,t.deadline,t.file FROM user u
                INNER JOIN task t
                ON u.id = t.user_id
                WHERE u.id = "'.$user_id.'"
                AND MATCH (title) AGAINST ("'.$search_word.'");';
    $res_tasks = mysqli_query($con, $sql_tasks);
    return mysqli_fetch_all($res_tasks, MYSQLI_ASSOC);
}
/**
* Функция отметки новой выполненной задачи
* @param myslqli $con Хранит данные о текущем подключении
* @param int $search_word id новой выполненной задачи
* @param int $check_index параметр задачи (выполнено/невыполнено)
*/

function set_completed(mysqli $con, $task_completed_id, $check_index)
{
    $id = mysqli_real_escape_string($con, $task_completed_id);
    $check_index = mysqli_real_escape_string($con, $check_index);
    $sql_update_querie = 'UPDATE task t SET
                    t.status = "'.$check_index.'"
                    WHERE t.task = "'.$id.'";';
    $res_update = mysqli_query($con, $sql_update_querie);
}

/**
* Функция регистрации нового пользователя и занесение его данных в БД
* @param $user_name имя пользователя
* @param $user_password пароль пользователя
* @param $user_email эл.почта пользователя
* @param $users массив данных о пользователях
* @param mysqli $con Хранит данные о текущем подключении
* @return int Идентификатор успешности/не успешности проведения действия
*/
function add_user($user_name, $user_password, $user_email, $users, mysqli $con)
{
    $user_email = mysqli_real_escape_string($con, $user_email);
    $check_email = false;
    $check_already_registered_email = true;

    foreach ($users as $user) {
        if ($user_email === $user['email']) {
            $check_already_registered_email = false;
        }
    }
    if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $check_email = true;
    }
    if ($check_email && $check_already_registered_email) {
        $sql_add_user = 'INSERT INTO user (email, name, password) VALUES (?,?,?)';
        $stmt_add_user = db_get_prepare_stmt($con, $sql_add_user, [
            'email' => $user_email,
            'name' => $user_name,
            'password' => password_hash($user_password, PASSWORD_DEFAULT)
        ]);
        $res_sql_add_user = mysqli_stmt_execute($stmt_add_user);
        session_start();
        $_SESSION['name'] = $_POST['name'];
        $sql_get_user_id = 'SELECT id from user
                            WHERE user.email ="'.$user_email.'";';
        $res_sql_get_user_id = mysqli_query($con, $sql_get_user_id);
        $user_id_array = mysqli_fetch_all($res_sql_get_user_id, MYSQLI_ASSOC);
        $_SESSION['id'] = $user_id_array[0]['id'];
        if ($res_sql_add_user === false) {
                die('Error while working with SQL request '.mysqli_error($con));
        }
        return 1;
    }

    return 0;
}

/**
* Функция добавления задачи в задачи и проверка данных на соответствие правилам
* @param mysqli $con хранит данные о текущем подключении
* @param array $categories массив с текущими проектами
* @param array $tasks массив с текущими задачами
* @param string $task_name название задачи
* @param $task_project id категории, к которой относится добавляемая задача
* @param date $task_date дата, на которую запланирована задача
* @return int Идентификатор успешности/не успешности проведения действия
*/
function add_task(mysqli $con, $categories, $tasks, $task_name, $task_project, $task_date)
{
    $file_uri = null;
    if (!empty($_FILES)) {
        $file_uri = move_file_to_uploads();
    }
    if (strtotime($_POST['date']) >= strtotime('today midnight') || empty($_POST['date'] && !empty($_SESSION))) {
        $sql_add_task = 'INSERT INTO task (user_id, project_id, status, title, file, deadline) VALUES(?,?,?,?,?,?)';
        $stmt_add_task = db_get_prepare_stmt($con, $sql_add_task, [
            'user_id' => $_SESSION['id'],
            'project_id' => $task_project,
            'status' => 0,
            'title' => $task_name,
            'file' => $file_uri,
            'deadline' => $task_date
        ]);
        $res_sql_add_task = mysqli_stmt_execute($stmt_add_task);
        if ($res_sql_add_task === false) {
                die('Error while working with SQL request '.mysqli_error($con));
             }
        return 1;
    }
    return 0;
}

/**
* Функция добавления нового проекта (категории) в базу данных
* @param mysqli $con хранит данные о текущем подключении
* @param string $new_category_name название новой категории
* @return int Идентификатор успешности/не успешности проведения действия
*/
function add_category(mysqli $con, $new_category_name)
{
    if (!empty($_SESSION)) {
        $alias = make_transliteration($new_category_name);
        $sql_add_category = 'INSERT INTO project (user, name, alias) VALUES(?,?,?)';
        $stmt_add_category = db_get_prepare_stmt($con, $sql_add_category, [
            'user' => $_SESSION['id'],
            'name' => $new_category_name,
            'alias' => $alias
        ]);
        $res_sql_add_category = mysqli_stmt_execute($stmt_add_category);
        if ($res_sql_add_category === false) {
                    die('Error while working with SQL request '.mysqli_error($con));
        }
        return 1;
    }
    return 0;
}

$con = mysqli_connect('localhost', 'root', '2k91abin1420pirzy', 'doingsdone');

mysqli_set_charset($con, 'utf8');
if (!empty($_SESSION)) {
    $categories = get_categories($con);
}
$users = get_users($con);

if (!empty($_GET['category'])) {
    $choosen_project = $_GET['category'];
};
if (empty($_GET['category'])) {
    $_GET['category']='null';
    $id_choosen_project = -1;
};
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $choosen_project = $_GET['category'];
};

if ($choosen_project!='null' && !empty($categories)) {
    foreach ($categories as $category) {
        if ($category['alias'] === $choosen_project) {
            $id_choosen_project = $category['id'];
        }
    }
} else {
    $id_choosen_project = -1;
}
if (isset($categories)) {
    check_response($categories, $choosen_project);
}
if (!$con) {
    print('Error'.mysqli_connect_error());
}
