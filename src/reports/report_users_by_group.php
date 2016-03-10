<?php
$paidsum=0; $debtsum=0;
include("../lib/error.php");
include "../header.php";
include('../mpdf/mpdf.php');
$mpdf=new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
$mpdf->charset_in='cp1251';
$mpdf->WriteHTML(file_get_contents('style1.css'),1);
$db=connectDB();
$gq1=mysql_query("SELECT courses.GroupTitle, courses.GroupPrice FROM courses WHERE courses.idGroup=".$_GET['id'],$db);
$gq1=mysql_fetch_array($gq1);
$mpdf->WriteHTML("<h5>Перечень слушателей курса \"".$gq1['GroupTitle']."\" (".$gq1['GroupPrice']." грн.)</h5>",2);
$mpdf->WriteHTML("<table><tr><td class='cell1'>№ п/п</td><td class='cell1'>Слушатель</td><td class='cell1'>Оплачено</td><td class='cell1'>Задолженость</td><td>&nbsp;</td></tr>",2);
$q1="SELECT Group_Listeners.idListener,Group_Listeners.havePaid FROM Group_Listeners INNER JOIN listeners ON Group_Listeners.idListener=listeners.idListener WHERE Group_Listeners.idGroup=".$_GET['id']." ORDER BY listeners.Surname";
$gq2=mysql_query($q1,$db);
$tmp2=mysql_query("SELECT SUM(havePaid), COUNT(havePaid) FROM Group_Listeners WHERE idGroup=".$_GET['id']);
$tmp2=mysql_fetch_array($tmp2);
for ($i=1;$result=mysql_fetch_array($gq2);$i++) {
 $dbt=false;
 $paidsum+=intval($gq1['GroupPrice']);
 $debtsum+=$tmp2[1]*$gq1['GroupPrice']-$tmp2[0];
 $fio=mysql_query("SELECT Name,Surname,Patronymic FROM listeners WHERE listeners.idListener=".$result['idListener'],$db);
 $fio=mysql_fetch_array($fio);
 if (intval($result['havePaid'])<intval($gq1['GroupPrice'])) $dbt=intval($gq1['GroupPrice'])-intval($result['havePaid']);
 if ($dbt) {$mpdf->WriteHTML("<tr class='debt'>",2);} else {$mpdf->WriteHTML("<tr>",2);}
 $mpdf->WriteHTML("<td class='cell1'>".$i."</td>",2);
 $mpdf->WriteHTML("<td>".$fio['Surname']." ".substr($fio['Name'],0,1).". ".substr($fio['Patronymic'],0,1).".</td>",2);
 $mpdf->WriteHTML("<td class='cell1'>".$result['havePaid']."</td>",2);
 if ($dbt) {$mpdf->WriteHTML("<td class='cell1'>".$dbt." грн.</td>",2);} else {$mpdf->WriteHTML("<td class='cell1'>Нет</td>",2);}
 $mpdf->WriteHTML("<td></td></tr>",2);
}
$mpdf->WriteHTML("<tr><td colspan=2 class='resrow1'>Итог:</td><td class='cell1 resrow1'>".$paidsum."</td><td class='cell1 resrow1'>".$debtsum."</td><td></td></tr>",2);
$mpdf->WriteHTML("</table>",2);
$mpdf->Output();
exit;
?>