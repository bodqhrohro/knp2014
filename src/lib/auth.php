<?php
define("USER_CP","1");
define("USER_ADM","2");
define("USER_VWR","3");
define("GLOBAL_SALT","Онищенко");
function randhash($in,$pow){
 $steps=rand(1,$pow);
 for ($i=1;$i<=$steps;$i++)
  $in=md5($in);
 return $in;
}
function userstate($role, $redir=true){
 $db=connectDB();
 if (isset($_COOKIE['sess_id'])){
  $type=mysql_query(sql([
   'select' => 'type',
   'from' => 'users',
   'where' => 'sessionid=\''.$_COOKIE['sess_id'].'\''
  ]),$db);
  if ($type)
   $type=mysql_fetch_row($type)[0];
  if ($type!==FALSE) {
   $type=ord($type);
   return
    ($role==USER_VWR) ||
    ($role==USER_CP && $type<50) ||
    ($role==USER_ADM && $type===49);
  }
 }
 if (!$redir)
  http_response_code(401);
 $GLOBALS['err_state']=7;
 return false;
}
function add_user($login,$pass,$check){
 $db=connectDB();
 $adm=mysql_query(sql([
  'select' => 'count(*)',
  'from' => 'users'
 ]),$db);
 $q1=mysql_query(sql([
  'select' => 'count(*)',
  'from' => 'users',
  'where' => 'login=\''.$login.'\''
 ]),$db);
 if (mysql_fetch_row($q1)[0]!=0 && $check){
  http_response_code(403);
  $GLOBALS['err_state']=4;
  return;
 }
 $adm=(mysql_fetch_row($adm)[0]==="0");
 $salt=randhash($login,50);
 $values=[
  $login,
  crypt($pass,$salt.GLOBAL_SALT),
  $salt,
  $adm,
  'q'
 ];
 $q2=mysql_query(sql($check?[
  'insert' => [],
  'into' => 'users',
  'values' => $values
 ]:[
  'update' => 'users',
  'set' => [
   'password' => $values[1],
   'salt' => $values[2]
  ],
  'where' => 'login=\''.$login.'\''
 ]),$db);
 if (!$q2) {
  http_response_code(400);
  $GLOBALS['err_state']=3;
 }
}
function user_login($login,$pass){
 $db=connectDB();
 $q1=mysql_query(sql([
  'select' => [
   'password',
   'salt',
   'sessionid'
  ],
  'from' => 'users',
  'where' => 'login=\''.$login.'\''
 ]),$db);
 $q1=mysql_fetch_row($q1);
 if (!$q1) {
  http_response_code(400);
  $GLOBALS['err_state']=5;
  return;
 }
 if ($q1[2]=='q') {
  http_response_code(403);
  $GLOBALS['err_state']=10;
  return;
 }
 if ($q1[0]!=crypt($pass,$q1[1].GLOBAL_SALT)) {
  http_response_code(403);
  $GLOBALS['err_state']=6;
  return;
 }
 $sess_id=randhash($login,500);
 $q2=mysql_query(sql([
  'update' => 'users',
  'set' => [
   'sessionid' => $sess_id
  ],
  'where' => 'login=\''.$login.'\''
 ]),$db);
 if ($q2) {
  setcookie('sess_id',$sess_id);
  header('Location: index.php');
 }
}
function user_logout(){
 $db=connectDB();
 if (isset($_COOKIE['sess_id']))
  mysql_query(sql([
   'update' => 'users',
   'set' => [
    'sessionid' => NULL
   ],
   'where' => 'sessionid=\''.$_COOKIE['sess_id'].'\''
  ]),$db);
 setcookie('sess_id','');
 header('Location: login.php');
 http_response_code(401);
 $GLOBALS['err_state']=8;
}
function my_userid(){
 $db=connectDB();
 $login=mysql_query(sql([
  'select' => 'login',
  'from' => 'users',
  'where' => 'sessionid=\''.$_COOKIE['sess_id'].'\''
 ]),$db);
 return mysql_fetch_row($login)[0];
}
?>
