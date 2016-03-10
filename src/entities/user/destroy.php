<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (userstate(USER_ADM) || my_userid()==$_POST['login']){
 $check=mysql_query(sql([
  'delete' => [],
  'from' => 'users',
  'where' => 'login="'.$_POST['login'].'"'
 ]),$db);
 if (!$check) {
  http_response_code(400);
 } else {
  echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
 }
} else {
 http_response_code(403);
}
?>
