<?php
foreach ($_POST as $key => $value){
 $_POST[$key]=mysql_real_escape_string($value);
};
foreach ($_GET as $key => $value){
 $_GET[$key]=mysql_real_escape_string($value);
};
?>
