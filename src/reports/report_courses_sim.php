<?php
$paidsum=0;
include('../header.php');
include('../mpdf/mpdf.php');
$mpdf=new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
$mpdf->WriteHTML(file_get_contents('style1.css'),1);
$mpdf->WriteHTML("<h1>Курсы</h1>",2);
$mpdf->WriteHTML("<h5>(".date("d.m.y").")</h5>",2);
$mpdf->WriteHTML("<table><tr><th>№ п/п</th><th>Информация о курсе</th><th>Преподаватель</th><th>Оплаченная сумма</th></tr>",2);
$db=connectDB();
$courses=mysql_query(sql([
 'select' => [
  'idCourse',
  'Name',
  'idTeacher'
 ],
 'from' => 'courses',
 'where' => 'isIndividual=0 AND (DateBegin BETWEEN TIMESTAMP(\''.$_GET['dateBegin'].'\') AND TIMESTAMP(\''.$_GET['dateEnd'].'\'))',
 'order by' => 'Name'
]),$db);
for ($i=1;$tmp1=mysql_fetch_array($courses);$i++){
 $tmp2=mysql_query(sql([
  'select' => [
   'sum(havePaid)'
  ],
  'from' => 'Course_Listeners',
  'where' => 'idCourse='.$tmp1['idCourse']
 ]),$db);
 $tmp2=mysql_fetch_row($tmp2);
 $paidsum+=$tmp2[0];
 $tmp4=mysql_query(sql([
  'select' => [
   'Surname',
   'Name',
   'Patronymic'
  ],
  'from' => 'teachers',
  'where' => 'idTeacher='.$tmp1['idTeacher']
 ]),$db);
 $tmp4=mysql_fetch_array($tmp4);
 $mpdf->WriteHTML("<tr>",2);
 $mpdf->WriteHTML("<td class='cell1'>".$i."</td>",2);
 $mpdf->WriteHTML("<td>".$tmp1['Name']."</td>",2);
 $mpdf->WriteHTML("<td class='cell1'>".shortName($tmp4['Surname'],$tmp4['Name'],$tmp4['Patronymic'])."</td>",2);
 $mpdf->WriteHTML("<td class='cell1'>".$tmp2[0]."</td>",2);
 $mpdf->WriteHTML("</tr>",2);
}
$mpdf->WriteHTML("<tr>",2);
$mpdf->WriteHTML("<td colspan=3 class='resrow1'>Итог:</td>",2);
$mpdf->WriteHTML("<td class='cell1 resrow1'>".$paidsum."</td>",2);
$mpdf->WriteHTML("</tr>",2);
$mpdf->WriteHTML("</table>",2);
$mpdf->Output();
exit;
?>