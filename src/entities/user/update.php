<?php
include('../../header.php');
include('check.php');
$db=connectDB();
if (userstate(USER_ADM) || my_userid()==$_POST['login']){
 add_user($_POST['login'],$_POST['password'],false);
 echo json_encode([$_POST],JSON_UNESCAPED_UNICODE);
} else {
 http_response_code(403);
}
?>
