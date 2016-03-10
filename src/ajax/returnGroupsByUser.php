<?php
 include('../header.php');
 $db=connectDB();
 $gq1=mysql_query(sql([
  "select" => ["idCourse"],
  "from" => ["Course_Listeners"],
  "where" => "Course_Listeners.idListener=".$_POST['userid']
 ]));
 while ($result=mysql_fetch_array($gq1)) {
  $fio=mysql_query(sql([
   "select" => ["Name"],
   "from" => ["courses"],
   "where" => "courses.idCourse=".$result['idCourse']
  ]));
  $fio=mysql_fetch_array($fio);
  echo $fio[0]."<br>";
 }

?>