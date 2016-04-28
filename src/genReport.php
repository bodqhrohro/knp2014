<?php
include('header.php');
$postfix='?dateBegin='.$_POST['reportBeginDate'].'&dateEnd='.$_POST['reportEndDate'];
switch ($_POST['reportType']) {
 case 'course':
  header('Location: reports/report_courses.php'.$postfix);
 break;
 case 'course_sim':
  header('Location: reports/report_courses_sim.php'.$postfix);
 break;
}
?>