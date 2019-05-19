<?php
    require_once('assembling.php');
    $redirect_var = add_task($con,$categories, $tasks);
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
