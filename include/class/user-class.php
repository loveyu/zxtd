<?php
class password{
	var $md5_pwd;
	var $user_type;
	var $user;
	var $pwd;
	var $login_pwd;
	function __construct($user,$pwd){
		$this->user=$user;
		$this->md5_pwd=md5($pwd);
		$this->pwd=hash("sha512",$this->mix_pwd($this->md5_pwd,PASSWORD_KEY));
		if($user==NULL)return;
		$this->check_user($user);
		//生成全局用户信息
		$GLOBALS['user']=array();
		$GLOBALS['user']['login']=FALSE;
	}
	function mix_pwd($pwd,$key){
		$pwd=md5($pwd);
		$pwd.=$key;
		return md5($pwd);
	}
	function check_user($user){
		if(is_numeric($user))
		{
			if(strlen($user)<11)$this->user_type="id";
			else $this->user_type="tel";
		}else if(is_mail($user))$this->user_type="email";
		else $this->user_type="user";
	}
	function up_pwd($pwd){
		global $mysql;
		$pwd=hash("sha512",$this->mix_pwd(md5($pwd),PASSWORD_KEY));
		$arr=FALSE;
		switch($this->user_type){
			case "tel":
			$arr=$mysql->up_sql_arr("user",array('password'=>$pwd),'`tel`="'.$this->user.'"');
			break;
			case "email":
			$arr=$mysql->up_sql_arr("user",array('password'=>$pwd),'`email`="'.$this->user.'"');
			break;
			case "id":
			$arr=$mysql->up_sql_arr("user",array('password'=>$pwd),'`id`='.$this->user);
			break;			
			default:
			$arr=$mysql->up_sql_arr("user",array('password'=>$pwd),'`user`="'.$this->user.'"');
		}
		return $arr;
	}
	function check_pwd(){
		global $mysql;
		$arr;
		switch($this->user_type){
			case "tel":
			$arr=$mysql->get_mysql_arr("user",array('password'),'`tel`="'.$this->user.'"');
			break;
			case "email":
			$arr=$mysql->get_mysql_arr("user",array('password'),'`email`="'.$this->user.'"');
			break;
			case "id":
			$arr=$mysql->get_mysql_arr("user",array('password'),'`id`='.$this->user);
			break;			
			default:
			$arr=$mysql->get_mysql_arr("user",array('password'),'`user`="'.$this->user.'"');
		}
		if(isset($arr[0]['password'])&&$arr[0]['password']==$this->pwd)return TRUE;
		else return FALSE;
	}
	function mysql_pwd(){
		global $mysql;
		$arr;
		switch($this->user_type){
			case "tel":
			$arr=$mysql->get_mysql_arr("user",'*','`tel`="'.$this->user.'"');
			break;
			case "email":
			$arr=$mysql->get_mysql_arr("user",'*','`email`="'.$this->user.'"');
			break;
			case "id":
			$arr=$mysql->get_mysql_arr("user",'*','`id`='.$this->user);
			break;			
			default:
			$arr=$mysql->get_mysql_arr("user",'*','`user`="'.$this->user.'"');
		}
		if(isset($arr[0]['password'])&&$arr[0]['password']==$this->pwd){
			$GLOBALS['user']['login']=TRUE;
			$GLOBALS['user']['user']=$arr[0]['user'];
			$GLOBALS['user']['name']=$arr[0]['name'];
			$GLOBALS['user']['email']=$arr[0]['email'];
			$GLOBALS['user']['class']=$arr[0]['class'];
			$GLOBALS['user']['major']=$arr[0]['major'];
			$GLOBALS['user']['group']=$arr[0]['group'];
			$GLOBALS['user']['grade']=$arr[0]['grade'];
			$GLOBALS['user']['tel']=$arr[0]['tel'];
			$GLOBALS['user']['qq']=$arr[0]['qq'];
			$GLOBALS['user']['id']=$arr[0]['id'];
			$GLOBALS['user']['power']=$arr[0]['power'];
			$GLOBALS['user']['active']=$arr[0]['active'];
			return TRUE;
		}else return FALSE;
	}
}
class mycookies{
	var $user;
	var $mysql_cookie;
	var $browser_cookie;
	var $ver;
	var $have_set;
	var $key;
	function __construct($user,$key='',$decode=NULL){
		$this->key=$key;
		if(!$decode)$this->user=$this->authcode($user,'');
		else $this->user=$user;
		$this->ver=FALSE;
		$this->have_set=FALSE;
	}
	function new_cookie(){
		$this->browser_cookie=$this->authcode($this->randomkeys(32),'');
		$this->mysql_cookie=md5($this->browser_cookie.$_SERVER['HTTP_USER_AGENT']);
	}
	function Verification($cookies,$time=10){
		global $mysql;
		$arr=$mysql->get_mysql_arr("user",'*','`user`="'.$this->authcode($this->user,'DECODE').'"');
		if(isset($arr[0]['cookie']) && $arr[0]['cookie']==md5($cookies.$_SERVER['HTTP_USER_AGENT']) && $arr[0]['cookieTime']+$time*3600>$_SERVER['REQUEST_TIME']){
			$this->ver=TRUE;
			$GLOBALS['user']['login']=TRUE;
			$GLOBALS['user']['user']=$arr[0]['user'];
			$GLOBALS['user']['name']=$arr[0]['name'];
			$GLOBALS['user']['email']=$arr[0]['email'];
			$GLOBALS['user']['class']=$arr[0]['class'];
			$GLOBALS['user']['major']=$arr[0]['major'];
			$GLOBALS['user']['group']=$arr[0]['group'];
			$GLOBALS['user']['grade']=$arr[0]['grade'];
			$GLOBALS['user']['tel']=$arr[0]['tel'];
			$GLOBALS['user']['qq']=$arr[0]['qq'];
			$GLOBALS['user']['id']=$arr[0]['id'];
			$GLOBALS['user']['power']=$arr[0]['power'];
			$GLOBALS['user']['active']=$arr[0]['active'];
			return TRUE;
		}else{
			$this->ver=FALSE;
			return FALSE;
		}
	}
	function set($save=360000,$path="/"){
		if($save==0){
			setcookie(COOKIE_PR."user",$this->user,0,$path);
			setcookie(COOKIE_PR."pwd",$this->browser_cookie,0,$path);
		}else{
			setcookie(COOKIE_PR."user",$this->user,$_SERVER['REQUEST_TIME']+$save,$path);
			setcookie(COOKIE_PR."pwd",$this->browser_cookie,$_SERVER['REQUEST_TIME']+$save,$path);			
		}
		$this->have_set=TRUE;
	}
	function up_data(){
		if(!$this->have_set)return FALSE;
		global $mysql;
		if($mysql->query("UPDATE `".$mysql->db_rp."user` SET `cookie`=\"".$this->mysql_cookie."\",`cookieTime`=".$_SERVER['REQUEST_TIME']." WHERE `user`=\"".$this->authcode($this->user,'DECODE')."\""))
			return TRUE;
		else return FALSE;
	}
	/*生成随机字符串*/
	function randomkeys($length=16){
		$pattern='0123456789qwertyuiopasdfghjklzxcvbnmMNBVCXZLKJHGFDSAPOIUYTREWQ';
		$key=null;
		$len=strlen($pattern);
		for($i=0;$i<$length;$i++)
			$key.=$pattern{mt_rand(0,$len-1)};
		return $key;
	}
	/** 加密与解密 */
	function authcode($string, $operation = '') {
		$key=$this->key;
		
		$key = md5($key.$_SERVER['HTTP_USER_AGENT']);
		$key_length = strlen($key);
		$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
		$string_length = strlen($string);
		$rndkey = $box = array();
		$result = '';
	
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($key[$i % $key_length]);
			$box[$i] = $i;
		}
	
		/**  
		*$box数组打散供加密用  
		*/  
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
	
		/**
		*$box继续打散,并用异或运算实现加密或解密
		*/
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
		if($operation == 'DECODE') {
			if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
			} else {
			return '';
			}
		} else {
			return str_replace('=', '', base64_encode($result));
		}
	}
	
}
class user{
	private $type;
	var $user_info;
	function __construct(){
		$this->user_info=array();
	}
	function new_user($arr){
		$this->user_info=$arr;
		$this->type='new';
	}
	function check_user($user){
		if(is_numeric($user))
		{
			if(strlen($user)<11)$this->type="id";
			else $this->type="tel";
		}else if(is_mail($user))$this->type="email";
		else $this->type="user";
	}
	function get_user_info($user){
		global $mysql;
		$this->check_user($user);
		$arr;$return=array();
		switch($this->type){
			case "tel":
			$arr=$mysql->get_mysql_arr("user",'*','`tel`="'.$user.'"');
			break;
			case "email":
			$arr=$mysql->get_mysql_arr("user",'*','`email`="'.$user.'"');
			break;
			case "id":
			$arr=$mysql->get_mysql_arr("user",'*','`id`='.$user);
			break;			
			default:
			$arr=$mysql->get_mysql_arr("user",'*','`user`="'.$user.'"');
		}
		if(isset($arr[0]['password'])){
			$return['login']=TRUE;
			$return['user']=$arr[0]['user'];
			$return['name']=$arr[0]['name'];
			$return['email']=$arr[0]['email'];
			$return['class']=$arr[0]['class'];
			$return['major']=$arr[0]['major'];
			$return['group']=$arr[0]['group'];
			$return['grade']=$arr[0]['grade'];
			$return['tel']=$arr[0]['tel'];
			$return['qq']=$arr[0]['qq'];
			$return['id']=$arr[0]['id'];
			$return['power']=$arr[0]['power'];
			$return['active']=$arr[0]['active'];
		}
		return $return;
	}
	function check_post_info($old_info,$new_info){
		foreach($old_info as $n => $v)
			if(!isset($new_info[$n]))unset($old_info[$n]);
		foreach($new_info as $n => $v)
			if(!isset($old_info[$n]))unset($new_info[$n]);
			
		foreach($new_info as $n => $v)
			if($v==$old_info[$n] || $v==''){
				unset($new_info[$n]);
				unset($old_info[$n]);
			}
		$this->user_info=$new_info;
		$status=$this->post_info_array_check($new_info);
		if($status!='OK')return $status;
		$status=$this->key_mysql_check();
		if($status!='OK')return $status;
		return 'OK';
	}
	function post_info_array_check($arr){
		if(isset($arr['name']))if(strlen($arr['name'])>20)return "姓名有误";
		if(isset($arr['email']))if(!is_mail($arr['email']))return "邮箱有误";
		if(isset($arr['tel']))if(!(strlen($arr['tel'])==11 && $arr['tel']{0}==1))return "电话错误";
		
		if(isset($arr['class']))if(!is_numeric($arr['class']) || $arr['class']<1)return "班级错误";
		if(isset($arr['major']))if(strlen($arr['major'])>20)return "专业有误";
		if(isset($arr['qq']))if(!is_numeric($arr['qq']))return "QQ有误";
		return 'OK';
	}
	function up_user_data($while=''){
		global $mysql;
		if($mysql->up_sql_arr("user",$this->user_info,$while))return TRUE;
		else return FALSE;
	}
	function key_mysql_check(&$err_info=''){
		$sql=NULL;
		global $mysql;
		if(isset($this->user_info['user']))$sql.="SUM(IF(`user`='".$this->user_info['user']."',1,0)),";
		if(isset($this->user_info['tel']))$sql.="SUM(IF(`tel`='".$this->user_info['tel']."',1,0)),";
		if(isset($this->user_info['email']))$sql.="SUM(IF(`email`='".$this->user_info['email']."',1,0)),";
		if($sql=='')return 'OK';
		$sql=substr($sql,0,-1);
		$sql="SELECT ".$sql." FROM ".$mysql->db_rp."user";
		$temp=$mysql->sql_to_arr($sql);
		$sql=array();
		foreach($temp as $v)foreach($v as $name => $value)$sql[$name]=$value;
		$arr=array();
		if(isset($this->user_info['user']))$arr['user']=$sql["SUM(IF(`user`='".$this->user_info['user']."',1,0))"];
		if(isset($this->user_info['tel']))$arr['tel']=$sql["SUM(IF(`tel`='".$this->user_info['tel']."',1,0))"];
		if(isset($this->user_info['email']))$arr['email']=$sql["SUM(IF(`email`='".$this->user_info['email']."',1,0))"];
		unset($temp);
		if($err_info!=NULL)$err_info=$arr;
		$sql='';
		if(isset($arr['user'])&&$arr['user']==1)$sql.="用户已存在,";
		if(isset($arr['tel'])&&$arr['tel']==1)$sql.="电话已存在,";
		if(isset($arr['email'])&&$arr['email']==1)$sql.="邮箱已存在,";
		if($sql!='')return $sql;
		return 'OK';
	}
	function insert_new_user(){
		if($this->type!='new')return FALSE;
		$password=new password(NULL,$this->user_info['pwd']);
		global $mysql;
		$arr=array();
		foreach($this->user_info as $n=>$v)
			if($n!='pwd' && $n!='pwd2')$arr[$n]=$v;
		$arr['password']=$password->pwd;
		if($mysql->inster("user",$arr))return TRUE;
		else return FALSE;
	}
	function change_pwd($id,$pwd,$old){
		$user_id=$GLOBALS['user']['id'];
		if($id!=$user_id)if($GLOBALS['user']['power']!=1)return '权限不够';
		
		$new_pwd=new password($id,$old);
		if(!$new_pwd->check_pwd())return "密码错误";
		if(!$new_pwd->up_pwd($pwd))return "修改密码错误";
		if(!$this->clear_mysql_cookie($id))return "更新数据失败";
		return 'OK';
	}
	function clear_mysql_cookie($id){
		global $mysql;
		if(!$mysql->up_sql_arr("user",array("cookie"=>''),'`id`='.$id))return FALSE;
		else return TRUE;
	}
	function up_power($id,$power){
		global $mysql;
		if($id==get_user_id())return '不允许修改自己的权限';
		$user=$this->get_user_info($id);
		if(!(isset($user['id']) && $user['id']==$id))return 'ID检测失败';
		if(!in_array($power,array_flip(get_power_array())))return '该权限不存在';
		if($mysql->up_sql_arr("user",array('power'=>$power),'`id`='.$id))return 'OK';
		else return '数据更新失败';
	}
	function change_active($id,$active){
		global $mysql;
		if($id==get_user_id())return '不允许修改自己的激活状态';
		$user=$this->get_user_info($id);
		if(!(isset($user['id']) && $user['id']==$id))return 'ID检测失败';
		if($mysql->up_sql_arr("user",array('active'=>$active?1:0),'`id`='.$id))return 'OK';
		else return '数据更新失败';
	}
	function info_check(){	
		if(!isset($this->user_info['user']) || empty($this->user_info['user']))return "用户名为空";
		if(strlen($this->user_info['user'])>20 || strlen($this->user_info['user'])<4)return "用户名长度不符";
		if(is_numeric($this->user_info['user']))return "用户名不能全为数字";
		if(is_numeric($this->user_info['user']{0}))return "用户名首位不能为数字";
		if(!isset($this->user_info['pwd']) || !isset($this->user_info['pwd2']) || $this->user_info['pwd']=='')return "密码为空";
		if(strlen($this->user_info['pwd'])<6)return "密码必须大于6位";
		if(strlen($this->user_info['pwd'])>16)return "密码必须不能大于16位";
		if($this->user_info['pwd']!=$this->user_info['pwd2'])return "两次密码不一致";
		if(isset($this->user_info['email']))if(!is_mail($this->user_info['email']))return "邮箱不正确";
		if(isset($this->user_info['tel']) && strlen($this->user_info['tel'])!=11)return "手机号码错误";
		return 'OK';
	}
}
class user_info{
	var $id_list;
	var $all;
	function __construct(){
		$this->id_list=array();
	}
	function get_id_list(){
		global $mysql;
		if(!empty($this->all))$arr=$this->all;
		else $arr=$mysql->get_mysql_arr("user",array('id','name'));
		foreach($arr as $v)$this->id_list[$v['id']]=$v['name'];
	}
	function get_all_user(){
		global $mysql;
		$this->all=$mysql->get_mysql_arr("user","*");
		return $this->all;
	}
}
class group{
	var $group;
	var $lader_id;
	var $id_list;
	var $group_list;
	function __construct(){
		$this->group=array();
	}
	function get_group(){
		if(!empty($this->group))return $this->group;
		global $mysql;
		$this->group=$mysql->get_mysql_all_array("group");
		$this->lader_id=array();
		$where='';	
		foreach($this->group as $v)$where.='id='.$v['lader'].' OR ';
		$where=substr($where,0,-4);
		$lader=$mysql->get_mysql_arr("user",array('id','name'),$where);
		foreach($lader as $v)$this->lader_id[$v['id']]=$v['name'];
	}
	function get_id_list(){
		$this->id_list=array();
		global $mysql;
		if(empty($this->group)){
			$arr=$mysql->get_mysql_arr("group",array('id','name'));
			foreach($arr as $v)$this->id_list[$v['id']]=$v['name'];
		}else{
			foreach($this->group as $v)$this->id_list[$v['id']]=$v['name'];
		}
		$this->id_list[0]='无分组';
		return $this->id_list;
	}
	function get_user_group(){
		global $mysql,$user_info;
		$this->group_list=array();
		if(count($user_info->all)==0)$all_user=$mysql->get_mysql_arr("user",array('id','name','group'));
		else $all_user=$user_info->all;
		foreach($all_user as $v){
			if(!isset($this->group_list[$v['group']]))$this->group_list[$v['group']]=array();
			array_push($this->group_list[$v['group']],array('id'=>$v['id'],'name'=>$v['name']));
		}
		return $this->group_list;
	}
	function new_group($name,$lader){
		if(empty($name))return '分组名称有误';
		$user_info=new user_info;
		$user_info->get_id_list();
		if(!isset($user_info->id_list[$lader]))return '该组长不存在';
		global $mysql;
		if($mysql->inster("group",array('name'=>$name,'lader'=>$lader)))return 'OK';
		else return '数据库插入失败';
	}
	function edit_group($id,$name,$lader){
		if(empty($name) || empty($lader) || empty($id))return '数据有误';
		global $mysql;
		$old=$mysql->get_mysql_arr("group","*",'`id`='.$id);
		if(!(isset($old[0]['id']) && $old[0]['id']==$id))return 'ID不存在或ID错误';
		if($old[0]['name']==$name && $old[0]['lader']==$lader)return '信息未改变';
		$user_info=new user_info;
		$user_info->get_id_list();
		if(!isset($user_info->id_list[$lader]))return '该组长不存在';
		if($mysql->up_sql_arr("group",array('name'=>$name,'lader'=>$lader),'`id`='.$id))return 'OK';
		else return '更新数据失败';
	}
	function del_group($id,$group){
		$this->get_id_list();
		if(!isset($this->id_list[$id]))return '要删除的分组不存在';
		if(!isset($this->id_list[$group]))return '要移动的分组不存在';
		global $mysql;
		if(!$mysql->up_sql_arr("user",array("group"=>$group),'`group`='.$id))return '移动分组失败';
		if(!$mysql->delete("group",'`id`='.$id))return '分组删除失败';
		return 'OK';
	}
}
class user_del extends user_info{
	var $del_list;
	var $where_id;
	function __construct(){
		$this->id_list=array();
		$this->del_list=array();
	}
	function add($id){
		if($id==get_user_id())return '不允许删除自己的账户';
		if(empty($this->id_list))$this->get_id_list();
		if(!isset($this->id_list[$id]))return '用户不存在';
		$this->del_list[$id]=array();
		$this->del_list[$id]['id']=$id;
		return 'OK';
	}
	function del(){
		$status='';
		$status=$this->other_action();
		if($status!='OK')return $status;
		else return $this->del_user();	
	}
	function del_user(){
		global $mysql;
		$id='';
		foreach($this->del_list as $v)$id.=$v['id'].',';
		$this->where_id=' in ('.substr($id,0,-1).')';
		if($mysql->delete("user",'`id`'.$this->where_id))return 'OK';
		else return '用户删除失败';
	}
	function other_action(){
		global $mysql;
		if(!$mysql->delete("library_history",'`user`'.$this->where_id))return '历史图书删除失败';
		if(!$mysql->delete("library_book",'`stuName`'.$this->where_id))return '用户所有的图书删除失败';
		if(!$mysql->up_sql_arr("library_book",array('nowBorrow'=>0),'`nowBorrow`'.$this->where_id))return '归还用户图书失败';
		if(!$mysql->up_sql_arr("group",array('lader'=>0),'`lader`'.$this->where_id))return '用户分组信息改变失败';
		library_category_up_all();
		return 'OK';
	}
}
?>