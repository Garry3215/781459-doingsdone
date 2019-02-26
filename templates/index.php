<?php session_start(); ?>

<?php if (isset($_SESSION['user_id'])): ?>
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
          <td class="task__date"><?=Date_DB_to_Man($value['date_must_done']); ?></td>
          <?php if (isset($value['file'])): ?>
            <td class="task__file">
                <a class="download-link" href="<?=$value['file']; ?>"><?=basename($value['file']); ?></a>
            </td>
          <?php endif ?>

          <td class="task__controls">
          </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>

<?php else: ?>
  <div class="content">
    <section class="welcome">
      <h2 class="welcome__heading">«Дела в порядке»</h2>

      <div class="welcome__text">
        <p>«Дела в порядке» — это веб приложение для удобного ведения списка дел. Сервис помогает пользователям не забывать о предстоящих важных событиях и задачах.</p>

        <p>После создания аккаунта, пользователь может начать вносить свои дела, деля их по проектам и указывая сроки.</p>
      </div>

      <a class="welcome__button button" href="register.php">Зарегистрироваться</a>
    </section>
  </div>

<?php endif; ?>
