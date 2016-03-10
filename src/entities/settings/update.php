<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_ADM)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'update' => 'settings',
 'set' => [
  'title' => $_POST['title'],
  'value' => $_POST['value']
 ],
 'where' => 'name=\''.$_POST['name'].'\''
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
