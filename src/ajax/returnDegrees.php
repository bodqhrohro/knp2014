<?php
 include('../header.php');
 $db=connectDB();
 $q1=mysql_query(sql([
 'select' => [
  'degree',
  'deglab'
 ],
 'from' => ['prices'],
 'order by' => ['deglab']
]));
$json=array();
while ($result=mysql_fetch_array($q1, MYSQL_ASSOC)){
 $json[]=array('degree' => $result['degree'], 'deglab' => $result['deglab']);
}
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>