<?php
function set_title($s=''){
	if($s!='')$GLOBALS['page_title']=$s.' - ';
	else $GLOBALS['page_title']=NULL;
}
function set_page_type($s,$sub=''){
	$GLOBALS['page_type'][0]=$s;
	$GLOBALS['page_type'][1]=$sub;
}
function set_page_power($s=array(0)){
	$GLOBALS['page_power']=array();
	$GLOBALS['page_power']=$s;
}
function the_title($title2=''){
	global $option;
	if(!isset($GLOBALS['page_title']))$GLOBALS['page_title']=NULL;
	if($title2!='')$title2=$title2.' - ';
	echo $GLOBALS['page_title'].$title2.$option->arr['sitename'];
}
function get_admin_header(){
	if(!isset($GLOBALS['page_type'][0]))$GLOBALS['page_type'][0]=1;
	if(!isset($GLOBALS['page_type'][1]))$GLOBALS['page_type'][1]=1;
	include_once(ROOT.'/template/header.php');
	if(!check_power())get_forbid_page();
}
function get_admin_footer(){
	global $option;
	include_once(ROOT.'/template/footer.php');
}
function get_admin_menu(){
	global $option;
	include_once(ROOT.'/template/menu.php');
}
function get_forbid_page(){
	echo "<div id=\"forbidden\"><p>Forbidden</p></div>";
	get_admin_footer();
}
function print_err_notice($s='ERROR',$url=''){
	if(empty($s))$s='ERROR';
	echo "<p class=\"error\">$s<p>\n";
	add_footer_str('<script language="javascript">error_notic("'.$s.'","'.$url.'");</script>');
}
function print_successful_notice($s='successful',$url=''){
	if(empty($s))$s='SUCCESSFUL';
	echo "<p class=\"successful\">$s<p>\n";
	add_footer_str('<script language="javascript">error_notic("'.$s.'","'.$url.'");</script>');
}
function print_err_arr_notice($s='ERROR',$arr=array(),$url=''){
	if(empty($s))$s='ERROR';
	echo "<p class=\"error\">$s<p>\n";
	$c='';
	if(!empty($arr)){
		echo "<div class=\"error\">";
		foreach($arr as $v){
			echo '<p>',$v,'</p>';
			$c.='\n'.$v;
		}
		echo "</div>";
	}
	add_footer_str('<script language="javascript">error_notic("'.$s.'\n'.$c.'","'.$url.'");</script>');
}
function add_footer_str($s){
	if(!isset($GLOBALS['footer']))$GLOBALS['footer']=array();
	array_push($GLOBALS['footer'],$s);
}
function show_one_menu($id,$title,$sub_menu=array()){
	//$sub_menu=array(array('name'=>'','url'=>'','id'=>'','power'=>array()))
	//$title=array('url'=>'','name'=>'','power'=>array());
	//print_r($arr);
	if(!in_array($GLOBALS['user']['power'],$title['power']))return 'No Permissions';
	if($GLOBALS['page_type'][0]==$id){
		echo "<li class=\"menu\" onClick =\"javascript:menu_chang('",$id,"');\">\n<div class=\"menu-title\" style=\"background-color:#333;\"><span><a href=\"",$title['url'],"\">",$title['name'],"</a></span></div>\n<div class=\"sub-menu\" id=\"",$id,"\" style=\"display:inline;\">\n";
	}else{
		echo "<li class=\"menu\" onMouseOver =\"javascript:menu_show('",$id,"');\" onMouseOut=\"javascript:menu_hide('",$id,"');\">\n<div class=\"menu-title\"><span><a href=\"",$title['url'],"\">",$title['name'],"</a></span></div>\n<div class=\"sub-menu\" id=\"",$id,"\">\n";
	}
	//子菜单
	if(!empty($sub_menu)){
		echo "<ul>\n";
		foreach($sub_menu as $v){
			if(!in_array($GLOBALS['user']['power'],$v['power']))continue;
			if($GLOBALS['page_type'][1]==$v['id'])echo '<li style="background-color: #C4E1D8;"><a href="',$v['url'],'">',$v['name'],'</a></li>',"\n";
			else echo '<li><a href="',$v['url'],'">',$v['name'],'</a></li>',"\n";
		}
		echo "</ul>\n";
	}
	echo "</div></li>\n\n";
}
?>