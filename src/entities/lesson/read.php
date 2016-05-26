<?php
include('../../header.php');
$db=connectDB();
if (!userstate(USER_VWR)) { http_response_code(403); exit; }
$q1=mysql_query(sql([
 'select' => [
  'idLesson',
  'idCourse',
  'date',
  'time',
  'type',
  'affectedBy'
 ],
 'from' => ['lessons'],
 'where' => 'idCourse='.$_GET['idCourse']
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
