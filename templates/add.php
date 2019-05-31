<h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"  action="add.php" enctype="multipart/form-data" method="post" autocomplete="off">
          <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <?= (empty($_POST['name'])&&(!empty($_POST))) ? '<p class ="form__message">Введите название!</p>' : '' ?>
            <input class="form__input <?= (empty($_POST['name'])&&(!empty($_POST))) ? 'form__input--error' : '' ?>" type="text" name="name" id="name" placeholder="Введите название"
            value ="<?= !empty($_POST)? $_POST['name'] : '' ?>">
          </div>

          <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select" name="project" id="project">
            <?php foreach ($categories as $category):?>
              <option value ="<?= $category['id'];?>">
                <?= $category['name']?>
              </option>
            <?php endforeach; ?>
            </select>
          </div>

          <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>
            <?= ((!empty($_POST)) && !empty($_POST['date']) && strtotime($_POST['date'])<=time()) ? '<p class ="form__message">Выберите корректную дату!</p>' : '' ?>
            <input class="form__input form__input--date <?= ((!empty($_POST)) && !empty($_POST['date']) && strtotime($_POST['date'])<=time()) ? 'form__input--error' : ''?>" type="text" name="date" id="date" value="" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
          </div>

          <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
              <input class="visually-hidden" type="file" name="file" id="file" value="">

              <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
              </label>
            </div>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
