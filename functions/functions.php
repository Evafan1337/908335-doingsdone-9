<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } elseif (is_string($value)) {
                $type = 's';
            }elseif (is_double($value)) {
                $type = 'd';
            }
            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}
/**
* Функция подсчета количества дел, относящихся к проекту
* @param array $tasks Список задач
* @param string $category Категория, с которой будет вестись сравнение
* @author Ershov Sasha
* @return $index
*/
function count_categories($tasks, $category)
{
    $index = 0;
    if (is_array($tasks)) {
        foreach ($tasks as $task) {
            if ($task['project_id'] === $category['id']) {
                    $index++;
            };
        }
        return $index;
    }
    return 0;
}

/**
* Функция проверки на корректность выбора категории и вывода ошибки 404 в случае неудачи
* @param array $categories - массив проектов
* @param string $choosen_project название выбранного проекта на английском языке
*/
function check_response($categories, $choosen_project)
{
    if (isset($categories)) {
        $categories_aliases = array_column($categories, 'alias');
        if ($_GET['category']!='null' && !in_array($choosen_project, $categories_aliases)) {
            http_response_code(404);
            die('error 404!');
        }
    }
}
/**
* Функция переноса загруженного файла из служебной директории в ./uploads
* @return string $file_url
*/
function move_file_to_uploads()
{
    $file_name = $_FILES['file']['name'];
    $file_path = dirname(dirname(__DIR__. '/uploads/'));
    $file_url = '/uploads/' . $file_name;
    move_uploaded_file($_FILES['file']['tmp_name'], $file_path.$file_name);
    return $file_url;
}

/**
* Функция перевода с русской раскладки на английскую,с учетом пробелов,перевода каретки и тд..
* @param $s данные, которые необходимо перевести
* @return $s переведенные данные
*/
function make_transliteration($s)
{
    $s = (string) $s;
    $s = strip_tags($s);
    $s = str_replace(array("\n", "\r"), " ", $s);
    $s = preg_replace("/\s+/", ' ', $s);
    $s = trim($s);
    $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s);
    $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s);
    $s = str_replace(" ", "-", $s);
    return $s;
}


/**
* Функция получения одномерного массива эл.почт пользователей из многомерного массива всей информации о пользователях
* @param $users массив инф-ии обо всех пользователях
* @return $users_emails_list одномерный массив эл.почт
*/
function get_emails_list($users)
{
    $users_emails_list = array_column($users, 'email');
    return $users_emails_list;
}

/**
* Функция, осуществляющая проверку и вход пользователя на сайт
* @param mysqli $con хранит данные о текущем подключении к БД
* @param $user_email эл.почта пользователя, проходящего авторизацию
* @param $user_password пароль пользователя, проходящего авторизацию
* @return boolean Идентификатор успешности/не успешности проведения авторизации
*/
function check_user(mysqli $con, $user_email, $user_password)
{
    $user_email = mysqli_real_escape_string($con, $user_email);
    $user_password = mysqli_real_escape_string($con, $user_password);
    $check_login_query = 'SELECT name,password,name,id FROM user WHERE email = "'.$user_email.'";';
    $res_query = mysqli_query($con, $check_login_query);
    $current_user = mysqli_fetch_all($res_query, MYSQLI_ASSOC);
    if (!empty($current_user) && password_verify($user_password, $current_user[0]['password'])){
        $_SESSION['name'] = $current_user[0]['name'];
        $_SESSION['id'] = $current_user[0]['id'];
        $_SESSION['password'] = password_hash($user_password, PASSWORD_DEFAULT);
        return true;
    }
    return false;
}
