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
 'where' => isset($_GET['id']) ? 'idListener='.$_GET['id'] : '1=1',
 'order by' => 'Surname'
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 if (!isset($_GET['id'])) {
  $q2=mysql_query(sql([
   'select' => [
    'count(idCourse)'
    ],
    'from' => ['Course_Listeners'],
    'where' => ['idListener='.$result['idListener']],
   ]));
  $result['Courses']=mysql_fetch_array($q2)[0];
 }
 if (isset($_GET['id']))
  $json=$result;
 else
  $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
