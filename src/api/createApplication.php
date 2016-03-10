<?php
include('../header.php');
include('check.php');
$db=connectDB();
if ($_POST['token'] != 'ce713e7c-0522-45d1-a99a-c6a9d3dec450') {
 http_response_code(403);
 die();
}
$check=mysql_query(sql([
 'insert' => [],
 'into' => 'listeners',
 'values' => [
  NULL,
  $_POST['Name'],
  $_POST['Surname'],
  $_POST['Patronymic'],
  $_POST['UGroup'],
  $_POST['Phone'],
  $_POST['Email'],
  $_POST['Notes'],
  0
 ]
]),$db);
$id=mysql_fetch_row(mysql_query(sql(["select" => "last_insert_id()"])))[0];
$check=$check &&
 mysql_query(sql([
  'insert' => [],
  'into' => 'Course_Listeners',
  'values' => [
   $_POST['idCourse'],
   $id,
   NULL,
   0,
   "b'0'",
   0,
   0
  ]
 ]),$db);
if (!$check) {
 http_response_code(400);
}
?>
