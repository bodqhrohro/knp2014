<?php
$login_page=1;
include('lib/error.php');
include('header.php');
if (!isset($_GET['action']))
 $_GET['action']='';
if ($_GET['action']==='logout')
 user_logout();
$reg=$_GET['action']==='register';
foreach ($_POST as $key => $value){
 $_POST[$key]=mysql_real_escape_string($value);
};
if (count($_POST)>0) {
 if ($reg) {
  $GLOBALS['err_state']=1;
  if ($_POST['password']!=$_POST['password2']) {
   http_response_code(403);
   $GLOBALS['err_state']=2;
  }
  add_user($_POST['login'],$_POST['password'],true);
 } else {
  user_login($_POST['login'],$_POST['password']);
 }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf8'>
<link rel='stylesheet' type='text/css' href='style/gray.css'>
<link rel='stylesheet' type='text/css' href='style/kendo.common.min.css'>
<link rel='stylesheet' type='text/css' href='style/kendo.default.min.css'>
</head>
<body>
<div class="logo_big">К&amp;П 2014</div>
<div style='position:absolute;top:0;right:0;z-index:1;'>
<?php if ($reg){ ?>
<a class='k-button' href='login.php'>Вход</a>
<?php } else { ?>
<a class='k-button' href='login.php?action=register'>Регистрация</a>
<?php } ?>
</div>
<?php if ($GLOBALS['err_state']) { ?>
<div class='k-block <?php echo errstyle(); ?>'>
<?php echo errmsg(); ?>
</div>
<?php } ?>
<div class='k-block k-panelbar' style='margin: 5% auto; width: 300px;text-align:center;'>
<h4 class='k-header'>
<?php if ($reg){ echo 'Регистрация'; } else { echo 'Вход в систему'; } ?>
</h4>
<form method='POST'>
Логин<br>
<input class='k-textbox' type='text' name='login'><br>
Пароль<br>
<input class='k-textbox' type='password' name='password'><br>
<?php if ($reg){ ?>
Повторите пароль<br>
<input class='k-textbox' type='password' name='password2'><br>
<?php } ?>
<button class='k-button' type='submit'>
<?php if ($reg){ echo 'Зарегистрироваться'; } else { echo 'Войти'; } ?>
</button>
</form>
</div>
</body>
</html>
