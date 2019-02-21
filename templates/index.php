<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
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
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks === 1): ?>checked<?php endif; ?> >
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <tr class="tasks__item task">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                <span class="checkbox__text">Сделать главную страницу Дела в порядке</span>
            </label>
        </td>

        <td class="task__file">
            <a class="download-link" href="#">Home.psd</a>
        </td>

        <td class="task__date"></td>
    </tr>
    <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->

    <?php foreach ($actual_tasks as $key => $value): ?>
      <?php if ($show_complete_tasks === 1 && $value['done']): ?>

      <?php else: ?>
        <tr class="tasks__item task <?php if ($value['done']): ?>task--completed<?php endif ?><?php if (date_check($value['date'])): ?>task--important<?php endif ?>">
          <td class="task__select">
            <label class="checkbox task__checkbox">
              <input class="checkbox__input visually-hidden" type="checkbox" <?php if ($value['done']): ?>checked<?php endif ?>>
              <span class="checkbox__text"><?=$value['name']; ?></span>
            </label>
          </td>
          <td class="task__date"><?=$value['date_must_done']; ?></td>
          <td class="task__controls">
          </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
