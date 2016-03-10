<?php
include('../../header.php');
$db=connectDB();
if (!userstate(USER_VWR)) { http_response_code(403); exit; }
$q1=mysql_query(sql([
 'select' => [
  'idListener',
  'Surname',
  'Name',
  'Patronymic',
  'UGroup',
  'Phone',
  'Email',
  'Notes',
  'affectedBy'
 ],
 'from' => ['listeners'],
 'order by' => 'Surname'
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $q2=mysql_query(sql([
  'select' => [
   'count(idCourse)'
   ],
   'from' => ['Course_Listeners'],
   'where' => ['idListener='.$result['idListener']],
  ]));
 $result['Courses']=mysql_fetch_array($q2)[0];
 $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
