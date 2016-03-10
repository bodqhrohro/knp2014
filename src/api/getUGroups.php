<?php
$login_page=1;
include('../header.php');
$db=connectDB();
$q1=mysql_query(sql([
 'select distinct' => [
  'UGroup',
 ],
 'from' => ['listeners'],
 'order by' => 'UGroup'
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $json[]=$result['UGroup'];
}
echo $_GET['callback'].'('.json_encode($json,JSON_UNESCAPED_UNICODE).')';
?>
