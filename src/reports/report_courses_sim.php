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
  'courses.Name',
  't.Surname tSurname',
  't.Name tName',
  't.Patronymic tPatronymic',
  't2.Surname t2Surname',
  't2.Name t2Name',
  't2.Patronymic t2Patronymic',
  'sum(havePaid) havePaid'
 ],
 'from' => 'courses left join teachers t on courses.idTeacher=t.idTeacher left join teachers t2 on courses.idTeacher2=t2.idTeacher left join Course_Listeners on courses.idCourse = Course_Listeners.idCourse',
 'where' => 'isIndividual=0 AND courses.idCourse in ('.sql([
  'select distinct' => 'idCourse',
  'from' => 'lessons',
  'where' => '(date BETWEEN TIMESTAMP(\''.$_GET['dateBegin'].'\') AND TIMESTAMP(\''.$_GET['dateEnd'].'\'))'
 ]).')',
 'group by' => 'courses.idCourse',
 'order by' => 'Name'
]),$db);
for ($i=1;$tmp1=mysql_fetch_array($courses);$i++){
 $paidsum+=$tmp1['havePaid'];
 $mpdf->WriteHTML("<tr>",2);
 $mpdf->WriteHTML("<td class='cell1'>".$i."</td>",2);
 $mpdf->WriteHTML("<td>".$tmp1['Name']."</td>",2);
 $mpdf->WriteHTML("<td class='cell1'>"
  .($tmp1['tSurname'] ? shortName($tmp1['tSurname'],$tmp1['tName'],$tmp1['tPatronymic']) : '')
  .($tmp1['t2Surname'] ? ",\n".shortName($tmp1['t2Surname'],$tmp1['t2Name'],$tmp1['t2Patronymic']) : '')
  ."</td>",2);
 $mpdf->WriteHTML("<td class='cell1'>".$tmp1['havePaid']."</td>",2);
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
