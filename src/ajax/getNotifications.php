<?php
include('../header.php');
$db=connectDB();
$json=array();
//
if ($_GET['verbose']) {
 $q1=mysql_query(sql([
  'select' => [
   'c.idCourse',
   'l.idListener',
   'Surname',
   'l.Name',
   'Patronymic',
   'c.Name',
   'UGroup'
  ],
  'from' => '(courses as c right join Course_Listeners as cl on c.idCourse=cl.idCourse) join listeners as l on l.idListener=cl.idListener',
  'where' => 'cl.affectedBy="0"'
 ]),$db);
 while ($result=mysql_fetch_array($q1,MYSQL_ASSOC))
  $json[]=[
   'string' => shortName($result['Surname'],$result['l.Name'],$result['Patronymic']).($result['UGroup']?" из ".$result['UGroup']:"")." хочет записаться на ".$result['Name'],
   'params' => [
    'cid' => $result['idCourse'],
    'lid' => $result['idListener']
   ],
   'type' => 'subscribe'
  ];
 $q2=mysql_query(sql([
  'select' => ['login'],
  'from' => 'users',
  'where' => 'sessionid="q"'
 ]),$db);
 while ($result=mysql_fetch_array($q2,MYSQL_ASSOC))
  $json[]=[
   'string' => "Зарегистрировался пользователь ".$result['login'],
   'params' => ['login' => $result['login']],
   'type' => 'account'
  ];
} else {
 $q1=mysql_query(sql([
  'select' => ['count(*)'],
  'from' => 'Course_Listeners',
  'where' => 'affectedBy="0"'
 ]),$db);
 $q1=mysql_fetch_row($q1);
 if ($q1[0]>0)
  $json[]="Есть ".$q1[0]." новых заявок";
 $q2=mysql_query(sql([
  'select' => ['count(*)'],
  'from' => 'users',
  'where' => 'sessionid="q"'
 ]),$db);
 $q2=mysql_fetch_row($q2);
 if ($q2[0]>0)
  $json[]="Есть ".$q2[0]." новых пользователей";
}
//
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>