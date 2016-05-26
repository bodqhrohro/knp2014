<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'update' => 'lessons',
 'set' => [
  'date' => $_POST['date'],
  'time' => $_POST['time'],
  'type' => $_POST['type'],
  'affectedBy' => my_userid()
 ],
 'where' => 'idLesson='.$_POST['idLesson']
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
