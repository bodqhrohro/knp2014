<?php
include('../../header.php');
$db=connectDB();
if (!userstate(USER_VWR)) { http_response_code(403); exit; }
$query=[
 'select' => [
  'login',
  'type'
 ],
 'from' => ['users'],
 'where' => '1=1',
 'order by' => 'login'
];
if (!userstate(USER_ADM)){
 $query['where']='login="'.my_userid().'"';
}
$q1=mysql_query(sql($query));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $json[]=$result;
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
