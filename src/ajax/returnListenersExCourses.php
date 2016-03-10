<?php
include('../header.php');
$db=connectDB();
$q1=mysql_query(sql([
 'select distinct' => ['uGroup'],
 'from' => ['listeners']
]));
//echo '<table class=\'sel_popup1\'>';
//$sw1=false;
$json=[];
$child=[];
$i=1;
for (;$result=mysql_fetch_row($q1);$i++){
 $q2=mysql_query(sql([
  'select' => [
   'idListener',
   'Surname',
   'Name',
   'Patronymic'
  ],
  'from' => ['listeners'],
  'where' => 'idListener not in ('.sql([
    'select' => ['idListener'],
    'from' => ['Course_Listeners'],
    'where' => 'idCourse='.$_GET['id']
   ]).') and uGroup="'.$result[0].'"',
   'order by' => 'Surname'
 ]));
 for (;$result2=mysql_fetch_array($q2, MYSQL_ASSOC);$i++) {
  $child[]=[
   'id' => $result2['idListener'],
   'text' => shortName($result2['Surname'],$result2['Name'],$result2['Patronymic'])
  ];
 }
 //$sw1=!$sw1;
 //echo '<tr><td '.($sw1?'':'class=\'k-alt\' ').'onclick=\'subscribeListenerToCourse(this,'.$result['idListener'].','.$_GET['id'].')\'>'.shortName($result['Surname'],$result['Name'],$result['Patronymic']).'</td></tr>';
 if (empty($result[0]))
  $result[0]="Вне групп";
 $json[]=[
  'id' => $i,
  'num' => "1",
  'text' => $result[0],
  'items' => $child
 ];
 $child=[];
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
//echo '</table>';
?>
