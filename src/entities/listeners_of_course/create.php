<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$ids=explode(":",$_POST['ids']);
foreach ($ids as $idk => $id) {
 $check=mysql_query(sql([
  'insert' => [],
  'into' => 'Course_Listeners',
  'values' => [
   $_POST['id'],
   $id,
   NULL,
   0,
   "b'1'",
   my_userid(),
   0
  ]
 ]),$db);
}
if (!$check) {
 http_response_code(400);
} else {
 $_POST['idListener']=mysql_insert_id($db);
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>