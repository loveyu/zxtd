<?php
function library_option(){
	if(isset($GLOBALS['library_option']))return;
	global $mysql;
	$arr=$mysql->get_mysql_arr("options",array('name','value'),'`name` like "library_%"');
	$GLOBALS['library_option']=array();
	foreach($arr as $v){
		$GLOBALS['library_option'][str_replace('library_','',$v['name'])]=$v['value'];
	}
}
function library_category_up_all(){
	$up_num=new library_category_up_number();
	$up_num->up_all();
}
?>