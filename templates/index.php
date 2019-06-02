<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post" autocomplete="off">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= ($show_complete_tasks) ? 'checked' :'' ?> >
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php if(is_array($tasks)):
            foreach ($tasks as $task) :
            if (($task['status']) && ($show_complete_tasks)) :
    ?>
    <tr class="tasks__item task task--completed">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                    <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1"<?= ($task['status']) ? ' checked' : '' ?>>
                    <span class="checkbox__text"><?= htmlspecialchars($task['title']) ?></span>
            </label>
        </td>
        <?php if( strcmp('/uploads/', $task['file'])) : ?>
        <td class="task__file"><a href="#"><?= $task['file'] ?></a></td>
        <?php else : ?>
        <td class="task__file"><a href="#"></a></td>
        <?php endif; ?>
        <?php if($task['deadline'] === $null_date) : ?>
        <td class="task__date"></td>
        <?php else : ?>
        <td class="task__date"><?= htmlspecialchars($task['deadline']) ?></td>
        <?php endif; ?>
        <td class="task__controls"></td>
    </tr>
    <?php
        elseif (time()-strtotime($task['date_create'])<86400):
    ?>
    <tr class="tasks__item task task--important">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1"<?= ($task['status']) ? 'checked' : '' ?>>
                <span class="checkbox__text"><?= htmlspecialchars($task['title']) ?></span>
            </label>
        </td>
        <?php if( strcmp('/uploads/', $task['file'])) : ?>
        <td class="task__file"><a href="#"><?= $task['file'] ?></a></td>
        <?php else : ?>
        <td class="task__file"><a href="#"></a></td>
        <?php endif; ?>
        <?php if($task['deadline'] === $null_date) : ?>
        <td class="task__date"></td>
        <?php else : ?>
        <td class="task__date"><?= htmlspecialchars($task['deadline']) ?></td>
        <?php endif; ?>
        <td class="task__controls"></td>
    </tr>
    <?php
        elseif (! $task['status']) :
    ?>
    <tr class="tasks__item task">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1"<?= ($task['status']) ? 'checked' : '' ?>>
                <span class="checkbox__text"><?= htmlspecialchars($task['title']) ?></span>
            </label>
        </td>
        <?php if( strcmp('/uploads/', $task['file'])) : ?>
        <td class="task__file"><a href="#"><?= $task['file'] ?></a></td>
        <?php else : ?>
        <td class="task__file"><a href="#"></a></td>
        <?php endif; ?>
        <?php if($task['deadline'] === $null_date) : ?>
        <td class="task__date"></td>
        <?php else : ?>
        <td class="task__date"><?= htmlspecialchars($task['deadline']) ?></td>
        <?php endif; ?>
        <td class="task__controls"></td>
    </tr>
    <?php
        endif;
        endforeach;
        endif;
    ?>
</table>
