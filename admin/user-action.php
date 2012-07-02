<?php
if(!isset($_SERVER['HTTP_REFERER']))die("Forbidden");
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

$form_url=$_SERVER['HTTP_REFERER'];
$form_url=parse_url($form_url);
$form_url=$form_url['scheme'].'://'.$form_url['host'].$form_url['path'].'?';
if(isset($_POST['act'])){
switch($_POST['act']){

//新建用户
	case 'new_user':{
		if(!check_power(array(1)))die(html_jump($form_url."&status=非法操作"));
		$user=new user;
		if(isset($_POST['user']))$arr['user']=$_POST['user'];
		if(isset($_POST['pwd']))$arr['pwd']=$_POST['pwd'];
		if(isset($_POST['pwd']))$arr['pwd2']=$_POST['pwd2'];
		if(isset($_POST['email']) && !empty($_POST['email']))$arr['email']=$_POST['email'];
		if(isset($_POST['tel']) && !empty($_POST['tel']))$arr['tel']=$_POST['tel'];
		$user->new_user($arr);
		$status=$user->info_check();
		if($status!='OK')die(html_jump($form_url."&status=".$status));
		$status=$user->key_mysql_check();
		if($status!='OK')die(html_jump($form_url."&status=".$status));
		if($user->insert_new_user())die(html_jump($form_url."&status=OK"));
		else die(html_jump($form_url."&status=创建用户失败"));
	}break;

//修改密码
	case 'changePwd':{
		if(!check_power(array(1,0)))die(html_jump($form_url."&status=非法操作"));
		Session_start();
		if(!isset($_POST['Checkcode']) || $_POST['Checkcode']!=$_SESSION['Checkcode'])
			die(html_jump($form_url."&status=验证码错误"));
		if(!isset($_POST['oldpwd']) || empty($_POST['oldpwd']) || strlen($_POST['oldpwd'])<6 || strlen($_POST['oldpwd'])>16)die(html_jump($form_url."&status=原密码格式有误"));
		if(!isset($_POST['pwd']) || empty($_POST['pwd'])  || strlen($_POST['pwd'])<6 || strlen($_POST['pwd'])>16)die(html_jump($form_url."&status=新密码格式有误"));
		if(!isset($_POST['pwd2']) || $_POST['pwd2']!= $_POST['pwd'])die(html_jump($form_url."&status=两次输入密码不一致"));
		if($_POST['pwd']==$_POST['oldpwd'])die(html_jump($form_url."&status=请修改为不同的密码"));
		$user2=new user();
		$status=$user2->change_pwd($GLOBALS['user']['id'],$_POST['pwd'],$_POST['oldpwd']);
		if($status!='OK')die(html_jump($form_url."&status=".$status));
		else die(html_jump($form_url."&status=OK"));
	}break;
	
//修改信息
	case 'editInfo':{
		if(!check_power(array(1,0)))die(html_jump($form_url."&status=非法操作"));
		//print_r($_POST);
		//$_POST['id']=2;
		if(!isset($_POST['id']))die(html_jump($form_url."&status=用户ID不正确"));
		
		$u=new user;
		if($_POST['id']!=$GLOBALS['user']['id'])
			if($GLOBALS['user']['power']!=1)
				die(html_jump($form_url."&status=权限不够"));
			else $other_user_arr=$u->get_user_info($_POST['id']);
			
		if(isset($other_user_arr))$status=$u->check_post_info($other_user_arr,$_POST);
		else $status=$u->check_post_info($GLOBALS['user'],$_POST);
		if($status!='OK')die(html_jump($form_url."&status=".$status));
		if(empty($u->user_info))die(html_jump($form_url."&status=没有需要修改的信息"));
		if(!$u->up_user_data('`id`='.$_POST['id']))die(html_jump($form_url."&status=信息修改失败"));
		if($_POST['id']==$GLOBALS['user']['id'])die(html_jump($form_url."&status=OK"));
		else die(html_jump($form_url."&id=".$_POST['id']."&status=OK"));
	}break;

//修改权限
	case 'chang-power':{
		if(!check_power(array(1)))die(html_jump($form_url."&status=非法操作"));
		if(isset($_POST['id']) && isset($_POST['power']) && $_POST['power']>=0){
			$u=new user;
			$status=$u->up_power($_POST['id'],$_POST['power']);
			if($status!='OK') die(html_jump($form_url."&status=".$status));
			else die(html_jump($form_url."&status=OK"));
		}else die(html_jump($form_url."&status=提交的信息有误"));
	}break;

//新建用户分组
	case 'new-user-group':{
		if(!check_power(array(1)))die(html_jump($form_url."&status=非法操作"));
		if(isset($_POST['name']) && isset($_POST['lader']) && is_number($_POST['lader'])){
			$group=new group;
			$status=$group->new_group($_POST['name'],$_POST['lader']);
			die(html_jump($form_url."&status=".$status));
		}else die(html_jump($form_url."&status=提交的参数有误"));
	}break;
	
//编辑用户分组信息
	case 'edit-user-group':{
		if(!check_power(array(1)))die(html_jump($form_url."&status=非法操作"));
		if(isset($_POST['name']) && isset($_POST['lader']) && isset($_POST['id']) && is_number($_POST['id']) && is_number($_POST['lader'])){
			$group=new group;
			$status=$group->edit_group($_POST['id'],$_POST['name'],$_POST['lader']);
			die(html_jump($form_url."&status=".$status));
		}else die(html_jump($form_url."&status=提交的参数有误"));
	}break;
	
//删除分组
	case 'del-user-group':{
		if(!check_power(array(1)))die(html_jump($form_url."&status=非法操作"));
		if(isset($_POST['id']) && isset($_POST['group']) && is_number($_POST['id']) && is_number($_POST['group']) && $_POST['id']>0 && $_POST['group']>0){
			$group=new group;
			$status=$group->del_group($_POST['id'],$_POST['group']);
			die(html_jump($form_url."&status=".$status));
		}else die(html_jump($form_url."&status=提交的参数有误"));
	}break;
	default:die(html_jump($form_url."&status=未知操作"));
}
}else if(isset($_GET['act'])){

switch($_GET['act']){

//取消激活
	case 'unactive':{
		if(!check_power(array(1)))die(html_jump($form_url."&status=非法操作"));
		if(isset($_GET['id']) && is_number($_GET['id'])){
			$u=new user;
			$status=$u->change_active($_GET['id'],0);
			if($status!='OK') die(html_jump($form_url."&status=".$status));
			else die(html_jump($form_url."&status=OK"));
		}else die(html_jump($form_url."&status=ID参数有误"));
	}break;

//激活
	case 'active':{
		if(!check_power(array(1)))die(html_jump($form_url."&status=非法操作"));
		if(isset($_GET['id']) && is_number($_GET['id'])){
			$u=new user;
			$status=$u->change_active($_GET['id'],1);
			if($status!='OK') die(html_jump($form_url."&status=".$status));
			else die(html_jump($form_url."&status=OK"));
		}else die(html_jump($form_url."&status=ID参数有误"));
	}break;


//删除账户
	case 'del':{
		if(!check_power(array(1)))die(html_jump($form_url."&status=非法操作"));
		if(isset($_GET['id']) && is_number($_GET['id'])){
			$user_del=new user_del;
			$status=$user_del->add($_GET['id']);
			if($status!='OK')die(html_jump($form_url."&status=".$status));
			else {
				$status=$user_del->del();
				die(html_jump($form_url."&status=".$status));
			}
		}else die(html_jump($form_url."&status=ID参数有误"));
	}break;

	default:die(html_jump($form_url."&status=未知操作"));
}
}else die(html_jump($form_url."&status=非法操作"));
?>