<?php
include('../../header.php');
$db=connectDB();
if (!userstate(USER_VWR)) { http_response_code(403); exit; }
$q1=mysql_query(sql([
 'select' => [
  'courses.idCourse',
  'courses.Name',
  'Description',
  'courses.idTeacher',
  'Surname',
  'teachers.Name tName',
  'Patronymic',
  'price',
  'state',
  'spec',
  'courses.affectedBy',
  'min(date) DateBegin',
  'max(date) DateEnd',
  'count(date) hours'
 ],
 'from' => ['courses left join lessons on courses.idCourse=lessons.idCourse left join teachers on courses.idTeacher=teachers.idTeacher'],
 'where' => 'IsIndividual=0',
 'group by' => 'courses.idCourse',
 'order by' => 'courses.Name'
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $result['TSNP']=shortName($result['Surname'], $result['tName'], $result['Patronymic']);
 $result=array_diff_key($result, array_flip(['Surname', 'tName', 'Patronymic']));
 if ($result['price']===NULL) {
  $result['price']=getCoursePrice($result['idCourse'],false);
 }
 $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
