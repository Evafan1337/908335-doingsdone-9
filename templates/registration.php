<h2 class="content__main-heading">Регистрация аккаунта</h2>

          <form class="form" action="/registration.php" method="post" autocomplete="off">
            <div class="form__row">
              <label class="form__label" for="email">E-mail <sup>*</sup></label>

              <input class="form__input" type="text" name="email" id="email" value="" placeholder="Введите e-mail">
              <?php if (isset($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) :
                $check_email = true;
              ?>
                  <p class="form__message">E-mail введён некорректно</p>
              <?php endif;?>

              <?php if (isset($users_email_list) && isset($_POST['email']) && in_array($_POST['email'], $users_email_list)) : ?>
                  <p class="form__message">Этот E-mail уже зарегистрирован</p>
              <?php endif; ?>
            </div>

            <div class="form__row">
              <label class="form__label" for="password">Пароль <sup>*</sup></label>
              <input class="form__input" type="password" name="password" id="password" value="" placeholder="Введите пароль">
              <?php if (isset($_POST) &&!empty($_POST) && empty($_POST['password'])) : ?>
                    <p class="form__message">Введите пароль</p>
              <?php endif; ?>
            </div>

            <div class="form__row">
              <label class="form__label" for="name">Имя <sup>*</sup></label>
              <input class="form__input" type="text" name="name" id="name" value="" placeholder="Введите пароль">
              <?php if (isset($_POST) &&!empty($_POST) && empty($_POST['name'])) : ?>
                    <p class="form__message">Введите имя</p>
              <?php endif; ?>
            </div>

            <div class="form__row form__row--controls">
              <?php if (isset($_POST) && !empty($_POST)) : ?>
              <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
              <?php endif; ?>

              <input class="button" type="submit" name="" value="Зарегистрироваться">
            </div>
          </form>
