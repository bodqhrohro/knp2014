<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'update' => 'prices',
 'set' => [
  'deglab' => $_POST['deglab'],
  'salary' => $_POST['salary']
 ],
 'where' => 'degree='.$_POST['degree']
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
