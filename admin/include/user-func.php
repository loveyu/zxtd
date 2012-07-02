<?php
function is_login(){
	if(!isset($GLOBALS['user']))return FALSE;
	return $GLOBALS['user']['login'];
}
function is_mail($field){
	if(filter_var($field,  FILTER_VALIDATE_EMAIL ))return TRUE;
	else return FALSE;
}
function check_login(){
	global $cookie,$option;
	if(!(isset($_COOKIE[COOKIE_PR."user"]) && $_COOKIE[COOKIE_PR."user"]!=NULL && isset($_COOKIE[COOKIE_PR.'pwd']))){
		$GLOBALS['user']['login']=FALSE;
		return FALSE;
	}
	$cookie=new mycookies($_COOKIE[COOKIE_PR.'user'],$option->arr['cookie_key'],TRUE);
	$cookie->Verification($_COOKIE[COOKIE_PR.'pwd'],$option->arr['cookie_time']);
	if($cookie->ver)$GLOBALS['user']['login']=TRUE;
	else $GLOBALS['user']['login']=FALSE;
	return $GLOBALS['user']['login'];
}
function clear_cookie(){
	setcookie(COOKIE_PR.'user','',0,'/');
	setcookie(COOKIE_PR.'pwd','',0,'/');
}
function check_power($arr=array()){
	if(empty($arr)){
		if(!isset($GLOBALS['page_power']))return TRUE;
		else if(in_array($GLOBALS['user']['power'],$GLOBALS['page_power']))return TRUE;
		else return FALSE;
	}else{
		if(in_array($GLOBALS['user']['power'],$arr))return TRUE;
		else return FALSE;
	}
}
function get_power(){
	return $GLOBALS['user']['power'];
}
function get_user_id(){
	return $GLOBALS['user']['id'];	
}
function get_power_array(){
	return array(
		0=>'普通用户',
		1=>'管理员'
	);
}
function get_power_name($power){
	$power_arr=get_power_array();
	if(isset($power_arr[$power]))return $power_arr[$power];
	else return '未知';
}
?>