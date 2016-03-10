<?php
$login_page=1;
include('../header.php');
$db=connectDB();
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
 ],
 'from' => ['courses'],
 'where' => 'IsIndividual=0',
 'order by' => 'Name'
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $q2=mysql_query(sql([
  'select' => [
   'Name',
   'Surname',
   'Patronymic'
  ],
  'from' => ['teachers'],
  'where' => ['idTeacher='.$result['idTeacher']]
 ]));
 $q2=mysql_fetch_array($q2,MYSQL_ASSOC);
 $result['TSNP']=$q2['Surname'].' '.$q2['Name'].' '.$q2['Patronymic'];
 unset($result['idTeacher']);
 if ($result['price']===NULL) {
  $result['price']=getCoursePrice($result['idCourse'],false);
 }
 $json[]=$result;
}
echo $_GET['callback'].'('.json_encode($json,JSON_UNESCAPED_UNICODE).')';
?>
