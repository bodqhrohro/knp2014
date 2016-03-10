<?php
include_once('../../header.php');

function getDeglab($degree) {
 $q2=mysql_query(sql([
  'select' => [
   'deglab'
  ],
  'from' => ['prices'],
  'where' => ['degree='.$degree]
 ]));
 $q2=mysql_fetch_array($q2,MYSQL_ASSOC);
 return $q2['deglab'];
}
?>
