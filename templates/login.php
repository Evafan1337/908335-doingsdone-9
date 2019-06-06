<h2 class="content__main-heading">Вход на сайт</h2>

        <form class="form" action="login.php" method="post" autocomplete="off">
          <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>
            <input class="form__input <?=(empty($_SESSION) && !empty($_POST)) ? 'form__input--error' : '' ?>" type="text" name="email" id="email" value="" placeholder="Введите e-mail">
            <?php if(empty($_SESSION) && !empty($_POST)): ?>
            <p class="form__message">E-mail введён некорректно</p>
            <?php endif; ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>

            <input class="form__input <?=(!empty($_POST['password']) && !in_array(password_hash($_POST['password'], PASSWORD_DEFAULT), $users_passwords)) ? 'form__input--error' : '' ?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">
            <?php if(!empty($_POST['password']) && !in_array(password_hash($_POST['password'], PASSWORD_DEFAULT), $users_passwords)) : ?>
            <p class="form__message">Введите корректный пароль</p>
            <?php endif; ?>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Войти">
          </div>
        </form>
