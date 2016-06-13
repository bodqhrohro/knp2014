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
  'courses.idTeacher2',
  't.Surname tSurname',
  't.Name tName',
  't.Patronymic tPatronymic',
  't2.Surname t2Surname',
  't2.Name t2Name',
  't2.Patronymic t2Patronymic',
  'price',
  'state',
  'spec',
  'min(date) DateBegin',
  'max(date) DateEnd',
  'count(date) hours'
 ],
 'from' => ['courses left join lessons on courses.idCourse=lessons.idCourse left join teachers t on courses.idTeacher=t.idTeacher left join teachers t2 on courses.idTeacher2=t2.idTeacher'],
 'where' => 'IsIndividual=0',
 'group by' => 'courses.idCourse',
 'order by' => 'courses.Name'
]));
$json=[];
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $result['TSNP']=$result['tSurname'].' '.$result['tName'].' '.$result['tPatronymic'];
 $result['TSNP2']=$result['t2Surname'].' '.$result['t2Name'].' '.$result['t2Patronymic'];
 $result=array_diff_key($result, array_flip(['tSurname', 'tName', 'tPatronymic', 'idTeacher', 't2Surname', 't2Name', 't2Patronymic', 'idTeacher2']));
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
