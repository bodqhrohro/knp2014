<?php
$paidsum=0; $debtsum=0;
include('../header.php');
include('../mpdf/mpdf.php');
$mpdf=new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
$mpdf->WriteHTML(file_get_contents('style1.css'),1);
$mpdf->WriteHTML("<h1>Курсы</h1>",2);
$mpdf->WriteHTML("<h5>(".date("d.m.y").")</h5>",2);
$mpdf->WriteHTML("<table><tr><th>№ п/п</th><th>Информация о курсе</th><th>Преподаватель</th><th>Долг</th><th>Оплаченная сумма</th></tr>",2);
$db=connectDB();
$courses=mysql_query(sql([
 'select' => [
  'courses.idCourse',
  'Name',
  'min(date) DateBegin',
  'max(date) DateEnd',
  'idTeacher',
  'count(date) hours'
 ],
 'from' => 'courses left join lessons on courses.idCourse = lessons.idCourse',
 'where' => 'isIndividual=0 AND (date BETWEEN TIMESTAMP(\''.$_GET['dateBegin'].'\') AND TIMESTAMP(\''.$_GET['dateEnd'].'\'))',
 'group by' => 'courses.idCourse',
 'order by' => 'Name'
]),$db);
for ($i=1;$tmp1=mysql_fetch_array($courses);$i++){
 $tmp1['price']=getCoursePrice($tmp1['idCourse'],false);
 $debt=0;
 $tmp2=mysql_query(sql([
  'select' => [
   'sum(havePaid)',
   'count(havePaid)'
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
 $tmp3=$tmp2[1]*$tmp1['price'];
 $mpdf->WriteHTML("<td>".$tmp1['Name']."<br>С ".date("d/m/Y",strtotime($tmp1['DateBegin']))." по ".date("d/m/Y",strtotime($tmp1['DateEnd']))."<br>".$tmp2[1]." чел. * ".$tmp1['hours']." час."." * ".$tmp1['price']." грн. =".$tmp3." грн.</td>",2);
 $tmp3-=$tmp2[0];
 $debtsum+=$tmp3;
 $mpdf->WriteHTML("<td class='cell1'>".shortName($tmp4['Surname'],$tmp4['Name'],$tmp4['Patronymic'])."</td>",2);
 $mpdf->WriteHTML("<td class='cell1'>".$tmp3."</td>");
 $mpdf->WriteHTML("<td class='cell1'>".$tmp2[0]."</td>",2);
 $mpdf->WriteHTML("</tr>",2);
}
$mpdf->WriteHTML("<tr>",2);
$mpdf->WriteHTML("<td colspan=3 class='resrow1'>Итог:</td>",2);
$mpdf->WriteHTML("<td class='cell1 resrow1'>".$debtsum."</td>",2);
$mpdf->WriteHTML("<td class='cell1 resrow1'>".$paidsum."</td>",2);
$mpdf->WriteHTML("</tr>",2);
$mpdf->WriteHTML("</table>",2);
$mpdf->Output();
exit;
?>
