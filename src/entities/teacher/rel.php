<?php
include_once('../../header.php');

function getTeacherSNP($idTeacher) {
 $q2=mysql_query(sql([
  'select' => [
   'Name',
   'Surname',
   'Patronymic'
  ],
  'from' => ['teachers'],
  'where' => ['idTeacher='.$idTeacher]
 ]));
 $q2=mysql_fetch_array($q2,MYSQL_ASSOC);
 return shortName($q2['Surname'],$q2['Name'],$q2['Patronymic']);
}
?>
