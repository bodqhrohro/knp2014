<?php
include('../../header.php');
include('check.php');
include('../price/rel.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'update' => 'teachers',
 'set' => [
  'Name' => $_POST['Name'],
  'Surname' => $_POST['Surname'],
  'Patronymic' => $_POST['Patronymic'],
  'Phone' => $_POST['Phone'],
  'Email' => $_POST['Email'],
  'affectedBy' => my_userid(),
  'degree' => $_POST['degree']
 ],
 'where' => 'idTeacher='.$_POST['idTeacher']
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 $_POST['deglab']=getDeglab($_POST['degree']);
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
