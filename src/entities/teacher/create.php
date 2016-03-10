<?php
include('../../header.php');
include('check.php');
include('../price/rel.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'insert' => [],
 'into' => 'teachers',
 'values' => [
  NULL,
  $_POST['Name'],
  $_POST['Surname'],
  $_POST['Patronymic'],
  $_POST['Phone'],
  $_POST['Email'],
  NULL,
  NULL,
  NULL,
  NULL,
  my_userid(),
  $_POST['degree'],
  NULL
 ]
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 $_POST['idTeacher']=mysql_insert_id($db);
 $_POST['deglab']=getDeglab($_POST['degree']);
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
