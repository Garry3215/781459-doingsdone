<?php session_start(); ?>

<?php if (isset($_SESSION['user_id'])): ?>
<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="get">
    <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">

        <a href="/index.php?<?=getQueryWithParameters(['tasks_switch' => "all"]);?>" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/index.php?<?=getQueryWithParameters(['tasks_switch' => "today"]);?>" class="tasks-switch__item">Повестка дня</a>
        <a href="/index.php?<?=getQueryWithParameters(['tasks_switch' => "tomorrow"]);?>" class="tasks-switch__item">Завтра</a>
        <a href="/index.php?<?=getQueryWithParameters(['tasks_switch' => "lost"]);?>" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox"
            <?php if ($_GET['show_completed'] == 1): ?>
                checked
            <?php else: ?>

            <?php endif; ?> >
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">

    <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->
  <?php if ($bad_search): ?>
  <p>Ничего не найдено по вашему запросу</p>
  <?php else: ?>
    <?php foreach ($actual_tasks as $key => $value): ?>
      <?php if (isset($_GET) && $_GET['show_completed'] == 0 && $value['status'] == 1): ?>

      <?php else: ?>

        <tr class="tasks__item tasks <?php if ($value['done']): ?>task--completed<?php endif ?><?php if (date_check($value['date_must_done'])): ?>task--important<?php endif ?>">
          <td class="task__select">
            <form class="" id="<?=$value['id']; ?>" action="index.php" method="post" name="<?=$value['id']; ?>">
              <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden" name="<?=$value['id']; ?>" type="checkbox" <?php if ($value['status'] == 1): ?>checked<?php endif ?> onchange="this.form.submit()">
                <span class="checkbox__text"><?=$value['name']; ?></span>
              </label>
              <input class="visually-hidden" type="text" name="<?=$value['id']; ?>" value="<?=$value['id']; ?>">
              <input class="visually-hidden" type="submit" id="<?=$value['id']; ?>" name="<?=$value['id']; ?>" value="<?=$value['id']; ?>">
            </form>
          </td>
          <td class="task__date"><?=Date_DB_to_Man($value['date_must_done']); ?></td>
          <?php if (isset($value['file'])): ?>
            <td class="task__file">
                <a class="download-link" href="<?=$value['file']; ?>"><?=basename($value['file']); ?></a>
            </td>
          <?php endif; ?>

          <td class="task__controls">
          </tr>
        <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
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
