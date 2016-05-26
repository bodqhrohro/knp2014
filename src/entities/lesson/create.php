<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'insert' => [],
 'into' => 'lessons',
 'values' => [
  NULL,
  $_POST['idCourse'],
  empty($_POST['date'])?NULL:$_POST['date'],
  empty($_POST['time'])?NULL:$_POST['time'],
  $_POST['type'],
  my_userid(),
 ]
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 $_POST['idLesson']=mysql_insert_id($db);
 $_POST['date']=date_from_kendo($_POST['date']);
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
