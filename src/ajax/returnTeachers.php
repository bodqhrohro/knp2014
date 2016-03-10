<?php
include('../header.php');
$db=connectDB();
$q1=[
 'select' => [
  'idTeacher',
  'Name',
  'Surname',
  'Patronymic'
 ],
 'from' => ['teachers'],
 'order by' => ['Surname']
];
if ($_GET['id']) {
 $q1[]=['where' => 'idTeacher='.$_POST['id']];
}
$q1=mysql_query(sql($q1));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $json[]=array('idTeacher' => $result['idTeacher'], 'TSNP' => shortName($result['Surname'],$result['Name'],$result['Patronymic']));
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
