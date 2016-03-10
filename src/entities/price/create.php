<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'insert' => [],
 'into' => 'prices',
 'values' => [
  NULL,
  $_POST['deglab'],
  $_POST['salary']
 ]
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 $_POST['degree']=mysql_insert_id($db);
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
