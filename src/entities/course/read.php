<?php
include('../../header.php');
include('../teacher/rel.php');
$db=connectDB();
if (!userstate(USER_VWR)) { http_response_code(403); exit; }
$q1=mysql_query(sql([
 'select' => [
  'idCourse',
  'Name',
  'Description',
  'idTeacher',
  'DateBegin',
  'DateEnd',
  'price',
  'hours',
  'state',
  'spec',
  'affectedBy'
 ],
 'from' => ['courses'],
 'where' => 'IsIndividual=0',
 'order by' => 'Name'
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $result['TSNP']=getTeacherSNP($result['idTeacher']);
 if ($result['price']===NULL) {
  $result['price']=getCoursePrice($result['idCourse'],false);
 }
 $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
