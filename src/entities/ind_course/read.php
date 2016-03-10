<?php
include('../../header.php');
include('../teacher/rel.php');
include('../listener/rel.php');
$db=connectDB();
if (!userstate(USER_VWR)) { http_response_code(403); exit; }
$q1=mysql_query(sql([
 'select' => [
  'courses.idCourse as idCourse',
  'Name',
  'Description',
  'idTeacher',
  'DateBegin',
  'DateEnd',
  'price',
  'hours',
  'state',
  'spec',
  'havePaid',
  'courses.affectedBy'
 ],
 'from' => ['courses join Course_Listeners on courses.idCourse = Course_Listeners.idCourse'],
 'where' => 'IsIndividual=1',
 'order by' => 'Name'
]));
if (!$q1) {echo mysql_error(); die;}
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
