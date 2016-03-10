<?php
 include('../header.php');
 $db=connectDB();
 switch($_GET['type']){
  case 'subscribe':
   if ($_GET['confirm']) {
    $q1=mysql_query(sql([
     'update' => 'Course_Listeners',
     'set' => [
      'affectedBy' => my_userid()
     ],
     'where' => 'idCourse='.$_GET['cid'].' AND idListener='.$_GET['lid']
    ]),$db);
    $q1=$q1 && mysql_query(sql([
     'update' => 'listeners',
     'set' => [
      'affectedBy' => my_userid()
     ],
     'where' => 'idListener='.$_GET['lid']
    ]),$db);
   } else {
    $q1=mysql_query(sql([
     'delete' => [],
     'from' => 'Course_Listeners',
     'where' => 'idCourse='.$_GET['cid'].' AND idListener='.$_GET['lid']
    ]),$db);
   }
  break;
  case 'account':
   if ($_GET['confirm']) {
    $q1=mysql_query(sql([
     'update' => 'users',
     'set' => [
      'sessionid' => NULL
     ],
     'where' => 'login=\''.$_GET['login'].'\''
    ]),$db);
   } else {
    $q1=mysql_query(sql([
     'delete' => [],
     'from' => 'users',
     'where' => 'login=\''.$_GET['login'].'\''
    ]),$db);
   }
  break;
 }
 if (!$q1) {
  header('HTTP/1.1 400 Bad Request');
 }
?>