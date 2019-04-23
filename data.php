<?php
$show_complete_tasks = rand(0, 1);
$categories = ['incoming' => 'Входящие',
                'study' => 'Учеба',
                'job' => 'Работа',
                'housework' => 'Домашние дела',
                'car' => 'Авто',];
$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date_of_doing' => '01.12.2018',
        'category' => $categories['job'],
        'complete' => False
    ],[
        'task' => 'Выполнить тестовое задание',
        'date_of_doing' => '25.12.2018',
        'category' => $categories['job'],
        'complete' => False
    ],[
        'task' => 'Сделать задание первого раздела',
        'date_of_doing' => '21.12.2018',
        'category' => $categories['study'],
        'complete' => True
    ],[
        'task' => 'Встреча с другом',
        'date_of_doing' => '22.12.2018',
        'category' => $categories['incoming'],
        'complete' => False
    ],[
        'task' => 'Купить корм для кота',
        'date_of_doing' => null,
        'category' => $categories['housework'],
        'complete' => False
    ],[
        'task' => 'Заказать пиццу',
        'date_of_doing' => null,
        'category' => $categories['housework'],
        'complete' => False
    ],
];?>
