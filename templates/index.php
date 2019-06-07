<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="get" autocomplete="off">
    <input class="search-form__input" type="text" name="search-form" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/index.php?sorting=all" class="tasks-switch__item <?=(isset($_GET['sorting']) && $_GET['sorting'] === 'all') ? 'tasks-switch__item--active' : '' ?>">Все задачи</a>
        <a href="/index.php?sorting=today" class="tasks-switch__item <?=(isset($_GET['sorting']) && $_GET['sorting'] === 'today') ? 'tasks-switch__item--active' : '' ?>">Повестка дня</a>
        <a href="/index.php?sorting=tomorrow" class="tasks-switch__item <?=(isset($_GET['sorting']) && $_GET['sorting'] === 'tomorrow') ? 'tasks-switch__item--active' : '' ?>">Завтра</a>
        <a href="/index.php?sorting=outdated" class="tasks-switch__item <?=(isset($_GET['sorting']) && $_GET['sorting'] === 'outdated') ? 'tasks-switch__item--active' : '' ?>">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed" name="show_completed_tasks" value="1" type="checkbox"<?=(isset($_SESSION['show_completed'])&& $_SESSION['show_completed'] === '1') ? 'checked' :'' ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php
        if (is_array($tasks) && (!isset($_SESSION['search-result']) || (isset($_SESSION['search-result']) &&(($_SESSION['search-result']) !== 'no') || $_SESSION['search-result'] === 'null' ))) :
            foreach ($tasks as $task) :
                if ($task['status'] === '0' || (isset($_SESSION['show_completed']) && $_SESSION['show_completed'] === '1')) :
    ?>
    <?php if ($task['status'] === '1') : ?>
        <tr class="tasks__item task task--completed">
    <?php elseif (date('Y-m-d', time()) === date('Y-m-d', strtotime($task['deadline']))) : ?>
        <tr class="tasks__item task task--important">
    <?php else : ?>
        <tr class="tasks__item task">
    <?php endif; ?>
        <td class="task__select">
            <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden task__checkbox" name="complete_status" value="<?=$task['task']?>" type="checkbox" <?=($task['status']==='1')? 'checked' :''?>>
                    <span class="checkbox__text"><?= htmlspecialchars($task['title']) ?></span>
            </label>
        </td>
        <?php if (strcmp('/uploads/', $task['file'])) : ?>
        <td class="task__file"><a href="#"><?= $task['file'] ?></a></td>
        <?php else : ?>
        <td class="task__file"><a href="#"></a></td>
        <?php endif; ?>
        <?php if ($task['deadline'] === $null_date) : ?>
        <td class="task__date"></td>
        <?php else : ?>
        <td class="task__date"><?= htmlspecialchars($task['deadline']) ?></td>
        <?php endif; ?>
        <td class="task__controls"></td>
    </tr>

    <?php
        endif;
        endforeach;
    ?>
    <?php else : ?>
        <p>Ничего не найдено по вашему запросу</p>
    <?php endif; ?>
</table>
