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
  'teachers.Surname tSurname',
  'teachers.Name tName',
  'teachers.Patronymic tPatronymic',
  'price',
  'state',
  'spec',
  'courses.affectedBy',
  'listeners.idListener',
  'listeners.Surname lSurname',
  'listeners.Name lName',
  'listeners.Patronymic lPatronymic',
  'havePaid',
  'min(date) DateBegin',
  'max(date) DateEnd',
  'count(date) hours'
 ],
 'from' => ['courses left join lessons on courses.idCourse=lessons.idCourse left join teachers on courses.idTeacher=teachers.idTeacher left join Course_Listeners on courses.idCourse=Course_Listeners.idCourse left join listeners on listeners.idListener=Course_Listeners.idListener'],
 'where' => 'IsIndividual=1',
 'group by' => 'courses.idCourse',
 'order by' => 'courses.Name'
]));
if (!$q1) {echo mysql_error(); die;}
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $result['TSNP']=shortName($result['tSurname'], $result['tName'], $result['tPatronymic']);
 $result['LSNP']=shortName($result['lSurname'], $result['lName'], $result['lPatronymic']);
 $result['oldIdListener']=$result['idListener'];
 $result=array_diff_key($result, array_flip(['tSurname', 'tName', 'tPatronymic', 'lSurname', 'lName', 'lPatronymic']));
 if ($result['price']===NULL) {
  $result['price']=getCoursePrice($result['idCourse'],false);
 }
 $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
