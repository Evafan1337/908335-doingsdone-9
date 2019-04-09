'use strict';

var $checkbox = document.getElementsByClassName('show_completed');

if ($checkbox.length) {
  $checkbox[0].addEventListener('change', function (event) {
    var is_checked = +event.target.checked;

    window.location = '/index.php?show_completed=' + is_checked;
  });
}

var $taskCheckboxes = document.getElementsByClassName('tasks');

if ($taskCheckboxes.length) {

  $taskCheckboxes[0].addEventListener('change', function (event) {
    if (event.target.classList.contains('task__checkbox')) {
      var el = event.target;

      var is_checked = +el.checked;
      var task_id = el.getAttribute('value');

      var url = '/index.php?task_id=' + task_id + '&check=' + is_checked;
      window.location = url;
    }
  });
}

flatpickr('#date', {
  enableTime: false,
  dateFormat: "Y-m-d",
  locale: "ru"
});
