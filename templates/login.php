<h2 class="content__main-heading">Вход на сайт</h2>

        <form class="form" action="login.php" method="post" autocomplete="off">
          <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>
            <input class="form__input <?=(!empty($errors['email'])) ? 'form__input--error' : '' ?>" type="text" name="email" id="email" value="" placeholder="Введите e-mail">
            <?php if (!empty($errors['email'])) : ?>
            <p class="form__message"><?= $errors['email'] ?></p>
            <?php endif; ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>

            <input class="form__input <?=(!empty($errors['password'])) ? 'form__input--error' : '' ?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">
            <?php if (!empty($errors['password'])) : ?>
            <p class="form__message"><?= $errors['password'] ?></p>
            <?php endif; ?>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Войти">
          </div>
        </form>
