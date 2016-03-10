<?php
define("PRICE_TRUNK","10");
error_reporting(E_ALL);
openlog("courses2014",LOG_PERROR,LOG_LOCAL0);
include('config.php');
include('lib/auth.php');
$lp=isset($login_page);
if (!userstate(USER_VWR, !$lp) && !$lp) {
  header('Location: //'.HOSTNAME.'/login.php');
}
function connectDB(){
 $db=mysql_connect(DB_HOST,DB_LOGIN,DB_PASS);
 mysql_query('SET NAMES utf8');
 mysql_select_db('course_payments_2014',$db);
 return $db;
}
function trimComma($request){
 $last_sym=strlen($request)-1;
 $last_sym2=$last_sym-1;
 if ($request{$last_sym}===','){
  $request=substr($request,0,$last_sym).' ';
 } else if (($request{$last_sym}===' ')&&($request{$last_sym2}===',')){
  $request=substr($request,0,$last_sym2).' ';
 }
 return $request;
}
function date_from_kendo($kendo){
 $arr=preg_split('/ /',$kendo);
 return $arr[3]."-".date('m',strtotime($arr[1]))."-".$arr[2];
}
function sql($param){
 $request='';
 switch (gettype($param)){
  case 'array':
   foreach($param as $key => $value){
    $request.=$key.' ';
    switch (gettype($value)){
     case 'array':
      if ($key=='values') $request.='(';
      foreach($value as $key2 => $value2){
       switch (gettype($value2)){
        case 'string':
	case 'integer':
	 if (strpos($value2,'b\'')===0) {
	  $request.=$value2.',';
	  break;
	 }
	 switch ($key){
	  case 'values':
	  case 'set':
	   if (strpos($value2,"GMT")===false){
	    $request.=($key=='set'?$key2.'=':'').'\''.$value2.'\', ';
	   } else {
	    $request.=($key=='set'?$key2.'=':'').'DATE(\''.date_from_kendo($value2).'\'), ';
	   }
	  break;
	  default:
	   $request.=$value2.',';
	  break;
	 }
        break;
	case 'array':
	 foreach($value2 as $value3){
	  $request.=$value2.'.'.$value3.',';
	 }
	break;
	case 'NULL':
	 if ($key==='set'){
	  $request.=$key2.'=NULL, ';
	 } else {
	  $request.='NULL, ';
	 }
	break;
	case 'boolean':
	 $request.="b'".($value2?"1":"0")."', ";
	break;
       }
      }
      $request=trimComma($request);
      if ($key=='values') $request.=')';
     break;
     case 'string':
      if ($value==='*'){
       $request.=$value;
      } else if ($value=='join'){
       $request.='('.$value.')';
      } else {
       $request.=$value;
      }
      $request.=' ';
     break;
    }
   }
  break;
  case 'string':
   $request=$param;
  break;
 }
 return $request;
}
function shortName($surname,$name,$patronymic){
 return $surname." ".mb_substr($name,0,1,"UTF-8").($name?". ":"").mb_substr($patronymic,0,1,"UTF-8").($patronymic?".":"");
}
function getSettings(){
 $settings=array();
 $q1=mysql_query(sql([
  'select' => [
   'name',
   'value'
  ],
  'from' => ['settings']
 ]));
 while ($result=mysql_fetch_array($q1)){
  $settings[$result['name']]=$result['value'];
 }
 return $settings;
}
function getCoursePrice($courseid,$full,$verif=true){
 $db=connectDB();
 $q2=mysql_query(sql([
  'select' => ['price'],
  'from' => ['courses'],
  'where' => 'idCourse='.$courseid
 ]));
 $q2=mysql_fetch_array($q2,MYSQL_ASSOC);
 if ($q2['price']==0) {
  $q1=mysql_query(sql([
   'select' => ['count(idCL)'],
   'from' => ['Course_Listeners'],
   'where' => 'idCourse='.$courseid
  ]));
  $q1=mysql_fetch_array($q1,MYSQL_NUM);
  $q3=mysql_query(sql([
   'select' => ['salary'],
   'from' => ['prices'],
   'where' => 'degree in ('.sql([
    'select' => 'degree',
    'from' => 'teachers',
    'where' => 'idTeacher in ('.sql([
     'select' => 'idTeacher',
     'from' => 'courses',
     'where' => 'idCourse='.$courseid
    ]).')'
   ]).')'
  ]));
  $q3=mysql_fetch_array($q3,MYSQL_ASSOC);
  $q4=mysql_query(sql([
   'select' => ['hours'],
   'from' => ['courses'],
   'where' => 'idCourse='.$courseid
  ]));
  $q4=mysql_fetch_array($q4,MYSQL_ASSOC);
  $st=getSettings();
  $price=floatval($q3['salary'])*
         floatval($st['personal'])*
         floatval($st['bonus'])*
	 floatval($st['others'])*
         intval($q4['hours'])/
         intval($q1[0]);
  $price=floor($price/PRICE_TRUNK)*PRICE_TRUNK;
  if ($full) {
   $price*=$q1[0];
  }
 } else {
  $price=$q2['price'];
 }
 return $price;
}
$key='123';
?>
