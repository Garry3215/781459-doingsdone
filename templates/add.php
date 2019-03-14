<?php if (isset($_SESSION['user_id'])): ?>
  <main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form"  action="add.php" method="post" enctype="multipart/form-data">
      <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>

        <input class="form__input<?php if (isset($wrong_data['name'])): ?> form__input--error<?php endif ?>" type="text" name="name" id="name" value="<?=(htmlspecialchars($form_data['name'])) ?? ''?>" placeholder="Введите название">
        <?php if (isset($wrong_data['name'])): ?>
            <p class="form__message"><?=$wrong_data['name'];?></p>
        <?php endif ?>

      </div>

      <div class="form__row">
        <label class="form__label" for="project">Проект</label>

        <select class="form__input form__input--select" name="project" id="project">
          <?php foreach ($project_category as $key => $value): ?>
              <option value="<?=htmlspecialchars($value['id']);?>"><?=htmlspecialchars($value['name']);?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>

        <input class="form__input form__input--date<?php if (isset($wrong_data['date'])): ?> form__input--error<?php endif ?>" type="date" name="date" id="date" value="<?=(htmlspecialchars($form_data['date'])) ?? ''?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
        <?php if (isset($wrong_data['date'])): ?>
            <p class="form__message"><?=$wrong_data['date'];?></p>
        <?php endif ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="preview">Файл</label>

        <div class="form__input-file">
          <input class="visually-hidden" type="file" name="preview" id="preview" value="">

          <label class="button button--transparent" for="preview">
            <span>Выберите файл</span>
          </label>
          <?php if (isset($wrong_data['file'])): ?>
              <p class="form__message"><?=$wrong_data['file'];?></p>
          <?php endif ?>
        </div>
      </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="submit" value="Добавить">
      </div>
    </form>
  </main>
<?php else: ?>

<?php endif; ?>
