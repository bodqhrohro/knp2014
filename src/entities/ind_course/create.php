<?php
include('../../header.php');
include('check.php');
include('../teacher/rel.php');
include('../listener/rel.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'insert' => [],
 'into' => 'courses',
 'values' => [
  NULL,
  $_POST['Name'],
  $_POST['Description'],
  "b'1'",
  $_POST['idTeacher'],
  $_POST['idTeacher2'],
  NULL,
  empty($_POST['price'])?NULL:$_POST['price'],
  $_POST['state'],
  my_userid(),
  0//$_POST['spec']
 ]
]),$db);
$id=mysql_insert_id();
$check=$check &&
 mysql_query(sql([
  'insert' => [],
  'into' => 'Course_Listeners',
  'values' => [
   $id,
   $_POST['idListener'],
   NULL,
   $_POST['havePaid'],
   "b'1'",
   my_userid(),
   0,
   NULL
  ]
 ]),$db);
if (!$check) {
 http_response_code(400);
} else {
 $_POST['idCourse']=mysql_insert_id($db);
 $_POST['TSNP']=getTeacherSNP($_POST['idTeacher']);
 $_POST['TSNP2']=getTeacherSNP($_POST['idTeacher2']);
 $_POST['LSNP']=getListenerSNP($_POST['idListener']);
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
