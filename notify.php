<?php
    require_once('vendor/autoload.php');
    require_once('assembling.php');
    $sql_tasks = 'SELECT t.title,t.task,t.project_id,t.user_id,t.status,t.date_create,t.deadline,t.file,u.name,u.email FROM user u
                INNER JOIN task t
                ON u.id = t.user_id
                WHERE t.deadline ="'.date('Y-m-d', time()).'"
                AND t.status = 0;';
    $res_tasks = mysqli_query($con, $sql_tasks);
    $tasks =  mysqli_fetch_all($res_tasks, MYSQLI_ASSOC);
    $users_list = array_unique(array_column($tasks, 'user_id'));
    array_multisort($tasks);
    $users_list_arr = array();
    $index = 0;
    foreach ($users_list as $list) {
        $users_list_arr[$index] = array($list, 0, 'username', array(), 'email-text');
        $index++;
    }
    foreach ($users_list_arr as &$list) {
        $tasks_list = '';
        foreach ($tasks as $task) {
            if ($task['user_id'] === $list[0]) {
                $list[2] = $task['name'];
                $list[1]++;
                array_push($list[3], $task['title']);
                $list[4] = $task['deadline'];
                $list[5] = $task['email'];
            }
        }
        if (count($list[3]) !== 1) {
            foreach ($list[3] as $list_tasks) {
            $tasks_list =' '.$tasks_list.$list_tasks.' , ';
            }
        } else {
            $tasks_list = $list[3][0];
        }
        $list[6] = 'Уважаемый,'.$list[2].'.У вас '.get_noun_plural_form($list[1] ,'запланирована ','запланированы ','запланированы ').' '.get_noun_plural_form($list[1] ,'задача','задачи','задачи').':'.$tasks_list.' на:'.$list[4];
    }
    foreach ($users_list_arr as $list) {
        $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
        $transport->setUsername("keks@phpdemo.ru");
        $transport->setPassword("htmlacademy");
        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message();
        $message->setSubject('Уведомление от сервиса «Дела в порядке»');
        $message->setFrom(["keks@phpdemo.ru" => "Дела в порядке"]);
        $message->addTo($list[5], 'recipient name');
        $message->addCc($list[5], 'recipient name');
        $message->addBcc($list[5], 'recipient name');
        $message->setBody($list[6]);
        $result = $mailer->send($message);
    }
