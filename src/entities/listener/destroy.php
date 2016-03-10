<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'delete' => [],
 'from' => 'listeners',
 'where' => 'idListener='.$_POST['idListener']
]),$db);
$check=$check &&
 mysql_query(sql([
  'delete' => [],
  'from' => 'Course_Listeners',
  'where' => 'idListener='.$_POST['idListener']
 ]),$db);
if (!$check) {
 http_response_code(400);
} else {
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
