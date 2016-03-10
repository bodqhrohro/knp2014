<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'update' => 'listeners',
 'set' => [
  'Name' => $_POST['Name'],
  'Surname' => $_POST['Surname'],
  'Patronymic' => $_POST['Patronymic'],
  'UGroup' => $_POST['UGroup'],
  'Phone' => $_POST['Phone'],
  'Email' => $_POST['Email'],
  'Notes' => $_POST['Notes'],
  'affectedBy' => my_userid()
 ],
 'where' => 'idListener='.$_POST['idListener']
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
