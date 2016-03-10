<?php
 include('../header.php');
 $db=connectDB();
 $gq1=mysql_query(sql([
  "select distinct" => ["UGroup"],
  "from" => ["listeners"]
 ]));
 $json=array();
 while ($result=mysql_fetch_row($gq1)) {
  $json[]=$result[0]?$result[0]:"Другие";
 }
echo json_encode($json,JSON_UNESCAPED_UNICODE);
?>
