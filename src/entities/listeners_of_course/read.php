<?php
include('../../header.php');
$db=connectDB();
if (!userstate(USER_VWR)) { http_response_code(403); exit; }
$q1=mysql_query(sql([
 'select' => [
  'idCL',
  'idListener',
  'havePaid',
  'affectedBy'
 ],
 'from' => ['Course_Listeners'],
 'where' => 'idCourse='.$_GET['id']
]));
$json=array();
$price=getCoursePrice($_GET['id'],false);
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $dbt=0;
 $q2=mysql_query(sql([
  'select' => [
   'Surname',
   'Name',
   'Patronymic'
  ],
  'from' => ['listeners'],
  'where' => ['idListener='.$result['idListener']]
 ]));
 $q2=mysql_fetch_array($q2,MYSQL_ASSOC);
 $result['Listener']=shortName($q2['Surname'],$q2['Name'],$q2['Patronymic']);
 $result['price']=$price;
 if (intval($result['havePaid'])<floatval($price))
  $dbt=floatval($price)-intval($result['havePaid']);
 $result['debt']=!empty($dbt)?($dbt." грн."):"Нет";
 $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
