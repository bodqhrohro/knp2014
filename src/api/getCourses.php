<?php
$login_page=1;
include('../header.php');
$db=connectDB();
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
  'min(date) DateBegin',
  'max(date) DateEnd',
  'count(date) hours'
 ],
 'from' => ['courses left join lessons on courses.idCourse=lessons.idCourse left join teachers on courses.idTeacher=teachers.idTeacher'],
 'where' => 'IsIndividual=0',
 'group by' => 'courses.idCourse',
 'order by' => 'courses.Name'
]));
$json=[];
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $result['TSNP']=$result['Surname'].' '.$result['tName'].' '.$result['Patronymic'];
 $result=array_diff_key($result, array_flip(['Surname', 'tName', 'Patronymic', 'idTeacher']));
 if ($result['price']===NULL) {
  $result['price']=getCoursePrice($result['idCourse'],false);
 }
 $q2=mysql_query(sql([
  'select' => [
   'date',
   'time',
   'type'
  ],
  'from' => 'lessons',
  'where' => 'idCourse='.$result['idCourse']
 ]));
 $result['lessons']=[];
 while ($lesson=mysql_fetch_array($q2, MYSQL_ASSOC))
  $result['lessons'][]=$lesson;
 $json[]=$result;
}
echo $_GET['callback'].'('.json_encode($json,JSON_UNESCAPED_UNICODE).')';
?>
