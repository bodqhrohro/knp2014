<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'insert' => [],
 'into' => 'listeners',
 'values' => [
  NULL,
  $_POST['Name'],
  $_POST['Surname'],
  $_POST['Patronymic'],
  $_POST['UGroup'],
  $_POST['Phone'],
  $_POST['Email'],
  $_POST['Notes'],
  my_userid()
 ]
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 $_POST['idListener']=mysql_insert_id($db);
 $_POST['Courses']=0;
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
