<?php
 include('../header.php');
 $db=connectDB();
 switch($_GET['type']){
  case 'subscribe':
   if ($_GET['confirm']) {
    if (isset($_POST['idListener'])) {
     $finalLid = $_POST['idListener'];
     $listener = $_POST;
     $q1=mysql_query(sql([
      'delete' => [],
      'from' => 'listeners',
      'where' => 'idListener='.$_GET['lid']
     ]),$db);
    } else {
     $finalLid = $_GET['lid'];
     $listener = [];
     $q1=true;
    }
    $listener['affectedBy'] = my_userid();
    $q1=$q1 && mysql_query(sql([
     'update' => 'Course_Listeners',
     'set' => [
      'idListener' => $finalLid,
      'affectedBy' => my_userid()
     ],
     'where' => 'idCourse='.$_GET['cid'].' AND idListener='.$_GET['lid']
    ]),$db);
    $q1=$q1 && mysql_query(sql([
     'update' => 'listeners',
     'set' => $listener,
     'where' => 'idListener='.$finalLid
    ]),$db);
    $q2 = mysql_query(sql([
     'select' => [
      'idCL',
      'callback'
     ],
     'from' => 'Course_Listeners',
     'where' => 'idCourse='.$_GET['cid'].' AND idListener='.$finalLid
    ]),$db);
    $q1=$q1 && $q2;
    $finalCL=mysql_fetch_array($q2, MYSQL_ASSOC);
   } else {
    $q1 = mysql_query(sql([
     'select' => [
      'idCL',
      'callback'
     ],
     'from' => 'Course_Listeners',
     'where' => 'idCourse='.$_GET['cid'].' AND idListener='.$_GET['lid']
    ]),$db);
    $finalCL=[
     'callback' => mysql_fetch_array($q1, MYSQL_ASSOC)['callback'],
     'idCL' => 'reject'
    ];
    $q1=$q1 && mysql_query(sql([
     'delete' => [],
     'from' => 'Course_Listeners',
     'where' => 'idCourse='.$_GET['cid'].' AND idListener='.$_GET['lid']
    ]),$db);
    $q1=$q1 && mysql_query(sql([
     'delete' => [],
     'from' => 'listeners',
     'where' => 'idListener='.$_GET['lid']
    ]),$db);
   }
   $q1=$q1 && curl_exec(curl_init(urldecode(str_replace('${id}', $finalCL['idCL'], $finalCL['callback']))));
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
