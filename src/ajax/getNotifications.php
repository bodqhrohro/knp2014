<?php
include('../header.php');
$db=connectDB();
$json=array();
function wordDist($w1, $w2) {
	return !empty($w1) && !empty($w2) ?
		levenshtein($w1, $w2) :
		FALSE;
}
function formatListener($surname, $name, $patronymic, $uGroup) {
	return "$surname $name $patronymic".($uGroup ? " из $uGroup":'');
}
//
if ($_GET['verbose']) {
 $allListeners = [];
 $q2=mysql_query(sql([
  'select' => [
   'idListener',
   'Surname',
   'Name',
   'Patronymic',
   'UGroup'
  ],
  'from' => 'listeners',
  'where' => 'affectedBy<>"0"'
 ]),$db);
 while ($result=mysql_fetch_array($q2,MYSQL_ASSOC))
  $allListeners[$result['idListener']] = $result;
 $q1=mysql_query(sql([
  'select' => [
   'c.idCourse',
   'l.idListener',
   'Surname',
   'l.Name lName',
   'Patronymic',
   'c.Name cName',
   'UGroup'
  ],
  'from' => '(courses as c right join Course_Listeners as cl on c.idCourse=cl.idCourse) join listeners as l on l.idListener=cl.idListener',
  'where' => 'cl.affectedBy="0"'
 ]),$db);
 while ($result=mysql_fetch_array($q1,MYSQL_ASSOC)) {
  $distances = [];
  foreach ($allListeners as $listener) {
   $sdist = wordDist($listener['Surname'], $result['Surname']);
   $ndist = wordDist($listener['Name'], $result['lName']);
   $pdist = wordDist($listener['Patronymic'], $result['Patronymic']);
   $dist = -1; $dcount = 4;
   if ($sdist !== FALSE) { $dist += $sdist; $dcount--; }
   if ($ndist !== FALSE) { $dist += $ndist; $dcount--; }
   if ($pdist !== FALSE) { $dist += $pdist; $dcount--; }
   if ($dcount < 4)
    $distances[$listener['idListener']] = $dist * $dcount;
  }

  $similarListener = $allListeners[array_keys($distances, min($distances))[0]];

  $json[]=[
   'string' => formatListener($result['Surname'],$result['lName'],$result['Patronymic'],$result['UGroup'])." хочет записаться на ".$result['cName'],
   'warning' => $similarListener ? 'Возможно, это '.formatListener($similarListener['Surname'],$similarListener['Name'],$similarListener['Patronymic'],$similarListener['UGroup']) : NULL,
   'params' => [
    'cid' => $result['idCourse'],
    'lid' => $result['idListener'],
    'lid_old' => $similarListener ? $similarListener['idListener'] : NULL
   ],
   'type' => 'subscribe'
  ];
 }
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
