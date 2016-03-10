<?php
include('../header.php');
$db=connectDB();
$q1=mysql_query(sql([
 'select distinct' => ['uGroup'],
 'from' => ['listeners']
]));
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
  'where' => 'uGroup="'.$result[0].'"',
   'order by' => 'Surname'
 ]));
 for (;$result2=mysql_fetch_array($q2, MYSQL_ASSOC);$i++) {
  $child[]=[
   'id' => $result2['idListener'],
   'text' => shortName($result2['Surname'],$result2['Name'],$result2['Patronymic'])
  ];
 }
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
?>
