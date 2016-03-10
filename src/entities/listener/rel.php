<?php
include_once('../../header.php');

function getListenerSNP($idListener) {
 $q2=mysql_query(sql([
  'select' => [
   'Name',
   'Surname',
   'Patronymic'
  ],
  'from' => ['listeners'],
  'where' => ['idListener='.$idListener]
 ]));
 $q2=mysql_fetch_array($q2,MYSQL_ASSOC);
 return shortName($q2['Surname'],$q2['Name'],$q2['Patronymic']);
}
?>
