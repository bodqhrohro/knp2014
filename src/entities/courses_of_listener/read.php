<?php
include('../../header.php');
$db=connectDB();
if (!userstate(USER_VWR)) { http_response_code(403); exit; }
$q1=mysql_query(sql([
 'select' => [
  'idCL',
  'idCourse',
  'havePaid',
  'affectedBy'
 ],
 'from' => ['Course_Listeners'],
 'where' => 'idListener='.$_GET['id']
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $dbt=0;
 $q2=mysql_query(sql([
  'select' => [
   'Name'
  ],
  'from' => ['courses'],
  'where' => ['idCourse='.$result['idCourse']]
 ]));
 $q2=mysql_fetch_array($q2,MYSQL_ASSOC);
 $result['Course']=$q2['Name'];
 $result['price']=getCoursePrice($result['idCourse'],false);
 if (intval($result['havePaid'])<intval($result['price']))
  $dbt=intval($result['price'])-intval($result['havePaid']);
 $result['debt']=!empty($dbt)?($dbt." грн."):"Нет";
 $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
