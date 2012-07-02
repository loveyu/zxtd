<?php
function html_jump($url,$time=0){
	return '<html><META HTTP-EQUIV="Refresh" CONTENT="'.$time.'; URL='.$url.'"></html>';
}
function replace_br($str){
	return str_replace("\n","<br>\n",$str);
}
function get_runtime(){
	global $runtime;
	$runtime->stop();
	echo $runtime->spent();
}
function get_sql_query_count(){
	return $GLOBALS['QueryCount'];
}
function date_secect_print($s,$p=array(),$show_zero=0){
	$year=substr($s,0,4);
	$month=substr($s,5,2);
	$day=substr($s,8,2);
	echo "\n<select name=\"",$p['year'],"\">\n";
	if($show_zero){
		if($year=='0000')$c=' selected';else $c=NULL;
		echo "<option value=\"0000\"$c>0000</option>\n";
	}
	for($i=2012;$i<=date("Y");$i++){
		if($year==$i)$c=' selected';else $c=NULL;
		echo "<option value=\"$i\"$c>$i</option>\n";
	}
	echo "</select>\n";
	
	echo "\n<select name=\"",$p['month'],"\">\n";
	if($show_zero){
		if($year=='00')$c=' selected';else $c=NULL;
		echo "<option value=\"00\"$c>00</option>\n";
	}
	for($i=1;$i<=12;$i++){
		if($month==$i)$c=' selected';else $c=NULL;
		echo "<option value=\"$i\"$c>$i</option>\n";
	}
	echo "</select>\n";
	
	echo "\n<select name=\"",$p['day'],"\">\n";
	if($show_zero){
		if($day=='00')$c=' selected';else $c=NULL;
		echo "<option value=\"00\"$c>00</option>\n";
	}
	for($i=1;$i<=31;$i++){
		if($day==$i)$c=' selected';else $c=NULL;
		echo "<option value=\"$i\"$c>$i</option>\n";
	}
	echo "</select>\n\n";	
}
function is_url($url){
	if(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))return TRUE;
	else return FALSE;
}
function is_number($s){
	if(is_numeric($s) && $s==round($s))return TRUE;
	else return FALSE;
}
?>