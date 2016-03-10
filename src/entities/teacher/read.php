<?php
include('../../header.php');
include('../price/rel.php');
$db=connectDB();
if (!userstate(USER_VWR)) { http_response_code(403); exit; }
$q1=mysql_query(sql([
 'select' => [
  'idTeacher',
  'Surname',
  'Name',
  'Patronymic',
  'Phone',
  'Email',
  'degree',
  'affectedBy'
 ],
 'from' => ['teachers'],
 'order by' => 'Surname'
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $result['deglab']=getDeglab($result['degree']);
 $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
