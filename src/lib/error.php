<?php
 $GLOBALS['err_state']=0;
 function errmsg() {
  switch ($GLOBALS['err_state']) {
   case 1: $errstr="Регистрация прошла успешно"; break;
   case 2: $errstr="Пароли не совпадают"; break;
   case 3: $errstr="Ошибка запроса"; break;
   case 4: $errstr="Такой пользователь уже существует"; break;
   case 5: $errstr="Пользователь не найден"; break;
   case 6: $errstr="Пароль неверен"; break;
   case 7: $errstr="Вы не вошли в систему"; break;
   case 8: $errstr="Сессия завершена"; break;
   case 9: $errstr="Неверный логин и/или пароль"; break;
   case 10: $errstr="Учётная запись ещё не подтверждена"; break;
   default: $errstr=""; $iserr=false; break;
  }
  return $errstr;
 }
 function errstyle() {
  switch ($GLOBALS['err_state']) {
   case 1:
   case 7:
   case 8:
    $style="k-success-colored";
   break;
   case 2:
   case 3:
   case 4:
   case 5:
   case 6:
   case 10:
    $style="k-error-colored";
   break;
  }
  return $style;
 }
?>
