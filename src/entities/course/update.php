<?php
include('../../header.php');
include('check.php');
include('../teacher/rel.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'update' => 'courses',
 'set' => [
  'Name' => $_POST['Name'],
  'Description' => $_POST['Description'],
  'idTeacher' => $_POST['idTeacher'],
  'idTeacher2' => $_POST['idTeacher2'],
  'price' => $_POST['price']==getCoursePrice($_POST['idCourse'],false)?NULL:$_POST['price'],
  'state' => $_POST['state'],
  'spec' => $_POST['spec'],
  'affectedBy' => my_userid()
 ],
 'where' => 'idCourse='.$_POST['idCourse']
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 $_POST['TSNP']=getTeacherSNP($_POST['idTeacher']);
 $_POST['TSNP2']=getTeacherSNP($_POST['idTeacher2']);
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
