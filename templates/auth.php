<div class="content">

  <section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="auth.php">Войти</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Вход на сайт</h2>

    <form class="form" action="auth.php" method="post">
      <div class="form__row">
        <label class="form__label" for="email">E-mail <sup>*</sup></label>

        <input class="form__input <?php if (isset($wrong_data['email'])): ?>form__input--error<?php endif; ?>" type="text" name="email" id="email" value="" placeholder="Введите e-mail">
        <?php if (isset($wrong_data['email'])): ?>
        <p class="form__message"><?=htmlspecialchars($wrong_data['email'])?></p>
        <?php endif; ?>
      </div>

      <div class="form__row">
        <label class="form__label" for="password">Пароль <sup>*</sup></label>

        <input class="form__input <?php if (isset($wrong_data['password'])): ?>form__input--error<?php endif; ?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">
        <?php if (isset($wrong_data['password'])): ?>
        <p class="form__message"><?=htmlspecialchars($wrong_data['password'])?></p>
        <?php endif; ?>
      </div>

      <?php if (!empty($wrong_data['auth'])): ?>
      <p class="error-message"><?=htmlspecialchars($wrong_data['auth']); ?></p>
      <?php endif; ?>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="submit" value="Войти">
      </div>
    </form>

  </main>

</div>
