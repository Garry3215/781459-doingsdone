<main class="content__main">
  <h2 class="content__main-heading">Добавление проекта</h2>

  <form class="form"  action="add_project.php" method="post">
    <div class="form__row">
      <label class="form__label" for="project_name">Название <sup>*</sup></label>

      <input class="form__input <?php if (isset($wrong_data['name'])): ?> form__input--error<?php endif ?>" type="text" name="name" id="project_name" value="" placeholder="Введите название проекта">
    </div>
    <?php if (isset($wrong_data['name'])): ?>
        <p class="form__message"><?=$wrong_data['name'];?></p>
    <?php endif ?>
    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="submit" value="Добавить">
    </div>
  </form>
</main>
