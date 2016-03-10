<?php
include('../header.php');
$db=connectDB();
$q1=mysql_query(sql([
 'select distinct' => [
  'teachers.idTeacher',
  'Surname',
  'teachers.Name',
  'Patronymic'
 ],
 'from' => 'courses right join teachers on teachers.idTeacher'
]));
//echo '<table class=\'sel_popup1\'>';
//$sw1=false;
$json=[];
$child=[];
$i=1;
for (;$result=mysql_fetch_array($q1, MYSQL_ASSOC);$i++){
 $q2=mysql_query(sql([
  'select' => [
   'idCourse',
   'Name'
  ],
  'from' => ['courses'],
  'where' => 'IsIndividual=0 and idCourse not in ('.sql([
    'select' => ['idCourse'],
    'from' => ['Course_Listeners'],
    'where' => 'idListener='.$_GET['id']
   ]).') and idTeacher='.$result['idTeacher'],
   'order by' => 'Name'
 ]));
 for (;$result2=mysql_fetch_array($q2, MYSQL_ASSOC);$i++){
  $child[]=[
   'id' => $result2['idCourse'],
   'text' => $result2['Name'],
  ];
 }
 //$sw1=!$sw1;
 if (!empty($child)){
  $json[]=[
   'id' => $i,
   'num' => "1",
   'text' => shortName($result['Surname'],$result['teachers.Name'],$result['Patronymic']),
   'items' => $child
  ];
  $child=[];
 }
 //echo '<tr><td '.($sw1?'':'class=\'k-alt\' ').'onclick=\'subscribeListenerToCourse(this,'.$_GET['id'].','.$result['idCourse'].')\'>'.$result['Name'].'</td></tr>';
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
//echo '</table>';
?>
