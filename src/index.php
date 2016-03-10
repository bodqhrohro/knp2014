<?php
include('header.php');
?>
<!DOCTYPE html>
<html>
 <head>
  <meta charset='utf8'>
  <title>К&amp;П 2014</title>
  <link rel='stylesheet' href='style/kendo.common.min.css'>
  <link rel='stylesheet' href='style/kendo.default.min.css'>
  <link rel='stylesheet' href='style/gray.css'>
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<?php if (!userstate(USER_CP)) { ?>
  <script type='text/javascript'>
   window.courses2014_editlock=1;
  </script>
<?php } ?>
  <script src='lib/jquery.min.js'>
  </script>
  <script src='lib/kendo.core.min.js'>
  </script>
  <script src='lib/kendo.data.min.js'>
  </script>
  <script src='lib/kendo.culture.ru-UA.min.js'>
  </script>
  <script src='lib/kendo.popup.min.js'>
  </script>
  <script src='lib/kendo.list.min.js'>
  </script>
  <script src='lib/kendo.calendar.min.js'>
  </script>
  <script src='lib/kendo.dropdownlist.min.js'>
  </script>
  <script src='lib/kendo.userevents.min.js'>
  </script>
  <script src='lib/kendo.draganddrop.min.js'>
  </script>
  <script src='lib/kendo.autocomplete.min.js'>
  </script>
  <script src='lib/kendo.pager.min.js'>
  </script>
  <script src='lib/kendo.sortable.min.js'>
  </script>
  <script src='lib/kendo.resizable.min.js'>
  </script>
  <script src='lib/kendo.validator.min.js'>
  </script>
  <script src='lib/kendo.binder.min.js'>
  </script>
  <script src='lib/kendo.editable.min.js'>
  </script>
  <script src='lib/kendo.numerictextbox.min.js'>
  </script>
  <script src='lib/kendo.datepicker.min.js'>
  </script>
  <script src='lib/kendo.combobox.min.js'>
  </script>
  <script src='lib/kendo.filtermenu.min.js'>
  </script>
  <script src='lib/kendo.menu.min.js'>
  </script>
  <script src='lib/kendo.window.min.js'>
  </script>
  <script src='lib/kendo.grid.min.js'>
  </script>
  <script src='lib/kendo.tabstrip.min.js'>
  </script>
  <script src='lib/kendo.treeview.min.js'>
  </script>
  <script src='lib/jquery.hotkeys-0.7.9.min.js'>
  </script>
  <script src='ajax.js'>
  </script>
 </head>
 <body>
  <div class='header1'>
   <ul id="mainmenu">
    <li><a onclick="openTable('listener');">Слушатели</a>
    <li>Курсы
     <ul>
      <li><a onclick="openTable('course');">Общие</a>
      <li><a onclick="openTable('ind_course');">Индивидуальные</a>
     </ul>
    <li><a onclick="openTable('teacher');">Преподаватели</a>
    <!--<li><a onclick="openWindow('paymentlog.php');">Лог оплат</a>-->
<?php if (userstate(USER_ADM)) { ?>
    <li>Администрирование
     <ul>
      <li><a onclick="openTable('price');">Расценки</a>
      <li><a onclick="openTable('settings');">Настройки</a>
     </ul>
<?php } if (userstate(USER_CP)) { ?>
    <li><a onclick="openTable('user');">Пользователи</a>
<?php } ?>
    <li>Отчёты
     <ul>
      <li><a onclick="reportDialog('course');">По курсам</a>
      <li><a onclick="reportDialog('course_sim');">По курсам (упрощённый)</a>
     </ul>
<?php if (userstate(USER_CP)) { ?>
    <li><a onclick="showNotifications()">Уведомления <span id="notification_count"></span></a>
<?php } ?>
    <li><a href="login.php?action=logout">Выход</a>
   </ul>
  </div>
  <div id="window_container">
  </div>
  <ol id="notifications">
  </div>
 </body>
</html>
