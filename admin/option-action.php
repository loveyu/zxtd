<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));
set_page_type('option');
set_page_power(array(1));
set_title("更新设置");
get_admin_header();

$status=NULL;
if(isset($_POST['type'])){
	switch($_POST['type']){
		case 'option':{
			$up_option=new up_option();
			$status=$up_option->get_post($_POST);
			if($status!='OK')print_err_notice($status);
			else {
				$status=$up_option->check();
				if($up_option->status==FALSE)print_err_arr_notice("数据检查出错",$status);
				else{
					$status=$up_option->up();
					if(count($status)!=0)print_err_arr_notice("数据更新出错",$status);
					else print_successful_notice("成功更新设置信息","option.php?status=OK");
				}
			}
		}break;
		case 'library':{
			library_option();//加载图书管理设置
			$up_option=new library_option_up();
			$status=$up_option->get_post($_POST);
			if($status!='OK')print_err_notice($status);
			else {
				$status=$up_option->check();
				if($up_option->status==FALSE)print_err_arr_notice("数据检查出错",$status);
				else{
					$status=$up_option->up();
					if(count($status)!=0)print_err_arr_notice("数据更新出错",$status);
					else print_successful_notice("成功更新设置信息","library-option.php?status=OK");
				}
			}
		}break;
		default:print_err_notice("未知操作");
	}
}else print_err_notice("错误的请求");

get_admin_footer();
?>