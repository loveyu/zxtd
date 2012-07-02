<?php
class option{
	var $arr;
	function __construct($type=NULL){
		$this->arr=array();
		switch($type){
			case NULL:{
				$this->auto_lading();
			}	
		}
	}
	function auto_lading(){
		global $mysql;
		$a=$mysql->get_mysql_arr("options",array("name","value"),"`lading`='yes'");
		foreach($a as $n => $v)$this->arr[$v['name']]=$v['value'];
	}
}
class up_option{
	var $post_arr;
	var $status;
	function __construct(){
		$this->post_arr=array();
		$this->status=FALSE;
	}
	function get_post($arr){
		if(isset($arr['site-title']))$this->post_arr['sitename']=$arr['site-title'];
		if(isset($arr['site-url']))$this->post_arr['siteurl']=$arr['site-url'];
		if(isset($arr['cookie-time']))$this->post_arr['cookie_time']=$arr['cookie-time'];
		if(isset($arr['cookie-key']))$this->post_arr['cookie_key']=$arr['cookie-key'];
		if(empty($this->post_arr))return '未提交正常数据';
		else return 'OK';
	}
	function filter(){
		global $option;
		$arr=$option->arr;
		foreach($arr as $n => $v){
			if(isset($this->post_arr[$n])){
				if($v==$this->post_arr[$n])unset($this->post_arr[$n]);
			}else unset($arr[$n]);
		}
		foreach($this->post_arr as $n => $v)if(!isset($arr[$n]))unset($this->post_arr[$n]);
	}
	function check(){
		$this->filter();
		$err=array();
		if(count($this->post_arr)==0)return array('信息未改变');
		if(isset($this->post_arr['sitename'])){
			if($this->post_arr['sitename']=='')array_push($err,"站点名不能为空");
		}
		if(isset($this->post_arr['siteurl'])){
			if(!is_url($this->post_arr['siteurl']))array_push($err,"站点名不能为空");
			while(substr($this->post_arr['siteurl'],-1,1)=='/')$this->post_arr['siteurl']=substr($this->post_arr['siteurl'],0,-1);
		}		
		if(isset($this->post_arr['cookie_time'])){
			if(!(is_numeric($this->post_arr['cookie_time']) && $this->post_arr['cookie_time']==round($this->post_arr['cookie_time'])))array_push($err,"Cookies时间设置有误");
		}
		if(isset($this->post_arr['cookie_key'])){
			if($this->post_arr['cookie_key']=='')array_push($err,"Cookies key有误");
		}
		if(count($err)==0)$this->status=TRUE;
		return $err;
	}
	function up(){
		if($this->status==FALSE)return array('验证失败');
		global $mysql;
		$err=array();
		foreach($this->post_arr as $n => $v){
			$status=$mysql->up_sql_arr("options",array("value"=>$v),'`name`="'.$n.'"');
			if($status!=TRUE)array_push($err,$n." 更新失败，值 ：".$v);
		}
		$this->status=FALSE;
		return $err;
	}
}
?>