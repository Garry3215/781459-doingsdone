<div class="content">
  <section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="auth.php">Войти</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Регистрация аккаунта</h2>

    <form class="form" action="register.php" method="post">
      <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?php if (isset($wrong_data['email'])): ?>form__input--error<?php endif; ?>" type="text" name="email" id="email" value="<?=isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''?>" placeholder="Введите e-mail">
        <?php if (isset($wrong_data['email'])): ?>
        <p class="form__message"><?=$wrong_data['email']?></p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?php if (isset($wrong_data['password'])): ?>form__input--error<?php endif; ?>" type="password" name="password" id="password" value="<?=isset($form_data['name']) ? htmlspecialchars($form_data['password']) : ''?>" placeholder="Введите пароль">
        <?php if (isset($wrong_data['password'])): ?>
        <p class="form__message"><?=$wrong_data['password']?></p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="name">Имя <sup>*</sup></label>

        <input class="form__input <?php if (isset($wrong_data['name'])): ?>form__input--error<?php endif; ?>" type="text" name="name" id="name" value="<?=isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''?>" placeholder="Введите имя">
        <?php if (isset($wrong_data['name'])): ?>
        <p class="form__message"><?=$wrong_data['name']?></p>
        <?php endif; ?>
      </div>

      <div class="form__row form__row--controls">
        <?php if (!empty($wrong_data)): ?>
        <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>
        <input class="button" type="submit" name="submit" value="Зарегистрироваться">
      </div>
    </form>
  </main>
</div>
