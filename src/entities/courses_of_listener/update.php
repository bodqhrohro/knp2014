<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (!userstate(USER_CP)) { http_response_code(403); exit; }
$check=mysql_query(sql([
 'update' => 'Course_Listeners',
 'set' => 'havePaid='.(isset($_GET['full'])?'':'havePaid+').$_POST['payment'],
 'where' => isset($_GET['full'])?'idCL='.$_GET['id']:'idListener='.$_GET['id'].' AND idCourse='.$_POST['idCourse']
]),$db);
if (!$check) {
 http_response_code(400);
} else {
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
}
?>
