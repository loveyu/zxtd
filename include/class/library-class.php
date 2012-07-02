<?php
class library_parameter_check{
	function check_book_name($bkname=NULL){
		if($bkname==NULL && isset($_GET['bkn']))$bkname=$_GET['bkn'];
		if($bkname!='')return $bkname;
		return NULL;
	}
	function check_isbn($isbn=NULL){
		if($isbn==NULL && isset($_GET['isbn']))$isbn=$_GET['isbn'];
		if($isbn!='' && is_numeric($isbn))return $isbn;
		return NULL;
	}
	function check_libNum($libNum=NULL){
		if($libNum==NULL && isset($_GET['libNum']))$libNum=$_GET['libNum'];
		if($libNum!='' && is_numeric($libNum))return $libNum;
		return NULL;
	}
	function check_priavte($privateBook=NULL){
		if($privateBook==NULL && isset($_GET['privateBook']))$privateBook=$_GET['privateBook'];
		if($privateBook!=''){
			if($privateBook=='1')return '=0';
			if($privateBook=='0')return '<>0';
		}
		return NULL;
	}
	function check_press($press=NULL){
		if($press==NULL && isset($_GET['press']))$press=$_GET['press'];
		if($press!='' && $press!=',')return $press;
		return NULL;
	}	
	function check_editor($editor=NULL){
		if($editor==NULL && isset($_GET['editor']))$editor=$_GET['editor'];
		if($editor!='' && $editor!=',')return $editor;
		return NULL;
	}
	function check_content($content=NULL){
		if($content==NULL && isset($_GET['content']))$content=$_GET['content'];
		if( $content!='')return $content;
		return NULL;
	}
	function check_publishTime($publishTime=NULL){
		if($publishTime==NULL && isset($_GET['publishTime']))$publishTime=$_GET['publishTime'];
		if( $publishTime!='' && is_numeric($publishTime))return $publishTime;
		return NULL;
	}
	function check_ebook($haveBook=NULL){
		if($haveBook==NULL && isset($_GET['haveEbook']))$haveBook=$_GET['haveEbook'];
		if( $haveBook!=''){
			if($haveBook=='1')return '<>0';
			else if($haveBook=='2')return '=0';			
		}
		return NULL;
	}	
	function check_pricing($pricing=NULL){
		if($pricing==NULL && isset($_GET['pricing']))$pricing=$_GET['pricing'];
		if($pricing!=''){
			if($pricing{0}=='>' || $pricing{0}=='<' || $pricing{0}=='='){
				if(is_numeric(substr($pricing,1)))return $pricing;
			}else{
				if(is_numeric($pricing))return '='.$pricing;
			}
		}
		return NULL;
	}
	function check_day($postDay=NULL){
		if($postDay==NULL && isset($_GET['day']))$postDay=$_GET['day'];
		if($postDay!=''){
			$type='';$day;
			if($postDay{0}=='>' || $postDay{0}=='<' || $postDay{0}=='='){
				if(is_numeric(substr($postDay,1))){
					$type=$postDay{0};
					$day=substr($postDay,1);
				}
			}else{
				if(is_numeric($postDay)){
					$type='=';
					$day=$postDay;					
				}
			}
			if($type!=''){
				$date=date("Y-m-d",strtotime(date("Y-m-d"))-($GLOBALS['library_option']['borrow_day']-$day)*86400);
				return $type.'"'.$date.'"';
			}
		}
		return NULL;		
	}		
	function check_category($act=NULL){
		if($act==NULL && isset($_GET['cat']))$act=$_GET['cat'];
		$cat=array();
		if( $act!=''){
			global $category;
			if(is_numeric($act)){
				if(isset($category->id_list[$act])){
					array_push($cat,$act);
				}
			}else{
				foreach($category->id_list as $id => $name){
					if(!(strrpos(strtoupper($name),strtoupper($act))===false)){
						array_push($cat,$id);
					}
				}
			}
		}
		return $cat;
	}
	function check_borrow($isBorrow=NULL){
		if($isBorrow==NULL && isset($_GET['isborrow']))$isBorrow=$_GET['isborrow'];
		$arr=array();
		if( $isBorrow!='')
			if($isBorrow=='2')return array(0);
		$arr[0]=-1;
		$arr[1]=$this->check_stu('borrow');
		return $arr;
	}
	function check_public_owner($ispublicbook=NULL){
		if($ispublicbook==NULL && isset($_GET['ispublicbook']))$ispublicbook=$_GET['ispublicbook'];
		$arr=array();
		if( $ispublicbook!='')
			if($ispublicbook=='1')return array(0);
		$arr[0]=-1;
		$arr[1]=$this->check_stu('owner');
		return $arr;	
	}
	function check_stu($s){
		//检测$s参数的GET参数中的 姓名或ID是否存在 返回ID数组 为空返回空
		$arr=array();
		if($s=='')return $arr;
		if(isset($_GET[$s]) && $_GET[$s]!=''){
			global $all_user;
			if(is_numeric($_GET[$s])){
				if(isset($all_user->id_list[$_GET[$s]])){
					array_push($arr,$_GET[$s]);
				}
			}else{
				foreach($all_user->id_list as $id => $name){
					if(!(strrpos(strtoupper($name),strtoupper($_GET[$s]))===false)){
						array_push($arr,$id);
					}
				}
			}
		}		
		return $arr;
	}
	function check_post_beginTime($date=array()){
		if(empty($date))$date=$_GET;
		if(isset($date['comYear']) && $date['comYear']>=2012 && $date['comYear']<=date("Y"))$comYear=$date['comYear'];
		else $comYear='';
		if(isset($date['comMonth']) && $date['comMonth']>0 && $date['comMonth']<=12)$comMonth=$date['comMonth'];
		else $comMonth='';	
		if(isset($date['comDay']) && $date['comDay']>0 && $date['comDay']<=31)$comDay=$date['comDay'];
		else $comDay='';	
		
		if($comYear=='' && $comMonth=='' && $comDay=='')return NULL;
		else return'(`beginTime` like "%'.$comYear.'-%'.$comMonth.'-%'.$comDay.'")';
	}
	function check_post_borrowTime($date=array()){
		if(empty($date))$date=$_GET;		
		if(isset($date['toYear']) && $date['toYear']>=2012 && $date['toYear']<=date("Y"))$toYear=$date['toYear'];
		else $toYear='';
		if(isset($date['toMonth']) && $date['toMonth']>0 && $date['toMonth']<=12)$toMonth=$date['toMonth'];
		else $toMonth='';	
		if(isset($date['toDay']) && $date['toDay']>0 && $date['toDay']<=31)$toDay=$date['toDay'];
		else $toDay='';		
		if( $toYear=='' && $toMonth=='' && $toDay=='' )return NULL;	
		else return '(`borrowTime` like "%'.$toYear.'-%'.$toMonth.'-%'.$toDay.'")';
	}
	function check_id($id=NULL){
		if($id==NULL && isset($_GET['id']))$id=$_GET['id'];
		if( is_numeric($id) && $id>0)return $id;
		else return NULL;
	}
}
class library extends library_parameter_check{
	var $list;
	private $sql;
	var $parameter;
	function __construct(){
		$this->list=array();
		$this->parameter=array();
	}
	function check_parameter(){
		$temp=$this->check_category();//分类检测
		if(!empty($temp))$this->parameter['category']=$temp;
		
		$temp=$this->check_public_owner();//图书所有者检测
		if($temp[0]==-1)
			if(empty($temp[1]))unset($temp[1]);
			else $this->parameter['stuName']=$temp;
		else $this->parameter['stuName']=$temp;
		
		$temp=$this->check_borrow();//检测图书在谁手
		if($temp[0]==-1)
			if(empty($temp[1]))unset($temp[1]);
			else $this->parameter['nowBorrow']=$temp;
		else $this->parameter['nowBorrow']=$temp;
		
		$temp=$this->check_book_name();
		if($temp!=NULL)$this->parameter['name']=$temp;
		
		$temp=$this->check_isbn();
		if($temp!=NULL)$this->parameter['ISBN']=$temp;

		$temp=$this->check_libNum();
		if($temp!=NULL)$this->parameter['libNum']=$temp;

		$temp=$this->check_press();
		if($temp!=NULL)$this->parameter['press']=$temp;

		$temp=$this->check_editor();
		if($temp!=NULL)$this->parameter['editor']=$temp;

		$temp=$this->check_publishTime();
		if($temp!=NULL)$this->parameter['publishTime']=$temp;

		$temp=$this->check_content();
		if($temp!=NULL)$this->parameter['content']=$temp;	
				
		$temp=$this->check_pricing();
		if($temp!=NULL)$this->parameter['pricing']=$temp;			
			
		$temp=$this->check_ebook();
		if($temp!=NULL)$this->parameter['ebook']=$temp;	
		
		$temp=$this->check_day();
		if($temp!=NULL)$this->parameter['beginTime']=$temp;
			
		$temp=$this->check_post_beginTime();
		if($temp!=NULL)$this->parameter['post_beginTime']=$temp;	
		
		$temp=$this->check_post_borrowTime();
		if($temp!=NULL)$this->parameter['post_borrowTime']=$temp;				
		
		$temp=$this->check_priavte();
		if($temp!=NULL)$this->parameter['priavteBook']=$temp;
		
		$this->parameter_to_sql();
	}
	function parameter_to_sql(){
		$sql_arr=array();
		$sql;
		if(isset($this->parameter['category'])){
			$sql='';
			foreach($this->parameter['category'] as $v)$sql.="`category`=".$v." OR ";
			$sql="( ".substr($sql,0,-4)." )";
			array_push($sql_arr,$sql);
		}
		if(isset($this->parameter['name']))array_push($sql_arr,'( `name` like "%'.$this->parameter['name'].'%" )');
		if(isset($this->parameter['ISBN']))array_push($sql_arr,'( `ISBN` like "%'.$this->parameter['ISBN'].'%" )');
		if(isset($this->parameter['libNum']))array_push($sql_arr,'( `libNum` like "%'.$this->parameter['libNum'].'%" )');
		if(isset($this->parameter['publishTime']))array_push($sql_arr,'( `publishTime` like "%'.$this->parameter['publishTime'].'%" )');		
		if(isset($this->parameter['press']))array_push($sql_arr,'( `press` like "%'.$this->parameter['press'].'%" )');
		if(isset($this->parameter['editor']))array_push($sql_arr,'( `editor` like "%'.$this->parameter['editor'].'%" )');
		if(isset($this->parameter['content']))array_push($sql_arr,'( `content` like "%'.$this->parameter['content'].'%" )');
		if(isset($this->parameter['pricing']))array_push($sql_arr,'( `pricing`'.$this->parameter['pricing'].' )');
		if(isset($this->parameter['ebook']))array_push($sql_arr,'( `ebook`'.$this->parameter['ebook'].' )');
		if(isset($this->parameter['beginTime']))array_push($sql_arr,'( `beginTime`'.$this->parameter['beginTime'].' )');
		if(isset($this->parameter['priavteBook']))array_push($sql_arr,'( `libNum`'.$this->parameter['priavteBook'].' )');
		if(isset($this->parameter['post_beginTime']))array_push($sql_arr,$this->parameter['post_beginTime']);
		if(isset($this->parameter['post_borrowTime']))array_push($sql_arr,$this->parameter['post_borrowTime']);
		
		if(isset($this->parameter['nowBorrow'])){
			if($this->parameter['nowBorrow'][0]==0)array_push($sql_arr,'( `nowBorrow` = 0 )');
			else {
				$sql='';
				foreach($this->parameter['nowBorrow'][1] as $v)$sql.="`nowBorrow`=".$v." OR ";
				$sql="( ".substr($sql,0,-4)." )";
				array_push($sql_arr,$sql);				
			}
		}
		if(isset($this->parameter['stuName'])){
			if($this->parameter['stuName'][0]==0)array_push($sql_arr,'( `stuName` = 0 )');
			else {
				$sql='';
				foreach($this->parameter['stuName'][1] as $v)$sql.="`stuName`=".$v." OR ";
				$sql="( ".substr($sql,0,-4)." )";
				array_push($sql_arr,$sql);				
			}
		}
		$sql='';
		foreach($sql_arr as $v)$sql.=$v." AND ";
		if($sql!='')$sql="( ".substr($sql,0,-4)." )";
		else $sql=1;
		$this->sql=$sql;
	}
	function get_all_book(){
		global $mysql;
		$this->list=$mysql->get_mysql_arr("library_book",'*');
	}
	function get_book(){
		global $mysql;
		$this->list=$mysql->get_mysql_arr("library_book",'*',$this->sql);
	}
	function get_now_book(){
		//获取当前参数的书籍
		$id=$this->check_id();
		if($id>0){
			global $mysql;
			$arr=$mysql->get_mysql_arr("library_book","*",'`id`='.$id);
			if(isset($arr[0]) && !empty($arr[0]))return $arr[0];
		}
		return NULL;
	}
}
class library_book_del extends library_all_action{
	private $list;
	function __construct(){
		$this->list=array();
	}
	function add($id){
		$this->check_id($id);
		if(is_array($id))$this->list+=$id;
		else array_push($this->list,$id);	
		array_flip(array_flip($this->list));	
	}
	function check_id(&$id){
		if(is_array($id)){
			foreach($id as $n => $v)
				if(!(is_numeric($v) && $v==round($v)))unset($id[$n]);
		}else{
			if(!(is_numeric($id) && $id==round($id)))$id=0;
		}
	}
	function del(){
		if(empty($this->list))return '待删除列表不存在';
		$sql='';
		foreach($this->list as $v)$sql.=$v.',';
		$sql=substr($sql,0,-1);
		$sql="`id` in ($sql)";
		global $mysql;
		if($mysql->delete("library_book",$sql))return 'OK';
		else return '删除失败';
	}
	function del_all(){
		foreach($this->arr as $v)$this->add($v);
		return $this->del();
	}
}
class library_category{
	var $id_list;
	function get_id_list(){
		global $mysql;
		$arr=$mysql->get_mysql_arr("library_category",array('id','name'));
		foreach($arr as $v)$this->id_list[$v['id']]=$v['name'];
	}
}
class admin_library_category extends library_category{
	var $list;
	function __construct(){
		$this->list=array();
	}
	function get_id_list(){
		foreach($this->list as $v)$this->id_list[$v['id']]=$v['name'];
	}
	function get_all_list(){
		global $mysql;
		$this->list=$mysql->get_mysql_arr("library_category",'*');
	}
}
class library_check{
	function name_check($s=''){
		global $category;
		if($s=='')return "分类名为空";
		if(strlen($s)>20)return "分类名过长，最长20";
		$flag=0;
		foreach($category->list as $v)if($v['name']==$s)$flag=1;
		if($flag==1)return "分类已存在";
		return 'OK';
	}
	function parent_check($s=''){
		global $category;
		if($s!=0){
			$flag=0;
			foreach($category->list as $v)if($v['id']==$s && $v['parent']==0){
				$flag=1;
			}
			if($flag==0)return "上级分类不存在";
		}
		return 'OK';
	}
	function description_check($s){
		if(strlen($s)>200)return "描述名过长，最长200";
		return 'OK';
	}
}
class new_library_category extends library_check{
	private $arr;
	private $status;
	private $inster;
	function __construct($arr){
		$this->arr=$arr;
		$this->status=FALSE;
		$this->inster=FALSE;
	}
	function check_info(){
		global $category;
		if(!(isset($this->arr['type']) && $this->arr['type']=='new-group'))return 'POST信息错误';
		if(isset($this->arr['name'])){
			$status=$this->name_check($this->arr['name']);
			if($status!='OK')return $status;
		}else return "分类名提交错误";
		if(isset($this->arr['parent'])){
			$status=$this->parent_check($this->arr['parent']);
			if($status!='OK')return $status;
		}else return "上级分类提交错误";
		if(!(isset($this->arr['description']) && strlen($this->arr['description'])<200))return "分组描述不正确，最长200";
		$this->status=TRUE;
		return 'OK';
	}
	function mysql_inster(){
		if($this->inster==TRUE)return "该信息已经提交";
		if($this->status==FALSE)return "必须先检查信息是否正确";
		global $mysql;
		if($mysql->inster("library_category",array(
		'name'=>$this->arr['name'],
		'parent'=>$this->arr['parent'],
		'description'=>$this->arr['description'],
		))){
			$this->inster=TRUE;
			return 'OK';
		}else return '插入失败';
	}
}
class up_library_category extends library_check{
	private $arr;
	private $id;
	private $status;
	function __construct($arr){
		$this->arr=$arr;
		$this->status=FALSE;
	}
	function check_info(){
		if(!isset($this->arr['id']))return "分组ID提交错误";
		global $category;
		$old_info=array();
		
		foreach($category->list as $v)if($v['id']==$this->arr['id']){$old_info=$v;break;}
		if(empty($old_info))return "要更新的分组不存在";
		$this->id=$this->arr['id'];
		foreach($old_info as $n => $v){
			if(!isset($this->arr[$n])){unset($old_info[$n]);continue;}
			if($this->arr[$n]==$v)unset($this->arr[$n]);
		}
		foreach($this->arr as $n => $v){
			if(!isset($old_info[$n])){unset($this->arr[$n]);continue;}
			if($old_info[$n]==$v)unset($this->arr[$n]);			
		}
		if(empty($this->arr))return '分类信息未改变';
		if(isset($this->arr['name'])){
			$status=$this->name_check($this->arr['name']);
			if($status!='OK')return $status;
		}
		if(isset($this->arr['parent'])){
			$status=$this->parent_check($this->arr['parent']);
			if($status!='OK')return $status;
			else{
				$flag=0;
				foreach($category->list as $value){
					if($value['parent']==$this->id){
						$flag=1;
						break;
					}
				}
				if($flag==1)return '该顶级分类使用中';
			}
		}
		if(isset($this->arr['description'])){
			$status=$this->description_check($this->arr['description']);
			if($status!='OK')return $status;		
		}
		$this->status=TRUE;
		return 'OK';
	}
	function mysql_updata(){
		if(!$this->status)return '数据未成功验证';
		global $mysql;
		if($mysql->up_sql_arr("library_category",$this->arr,'`id`='.$this->id))return 'OK';
		else return '数据更新失败';
	}
}
class library_category_del extends library_all_action{
	var $arr_list;
	var $err_list;
	var $post_arr;
	function __construct(){
		$this->arr_list=array();
		$this->err_list=array();
	}
	function add($arr=array()){
		global $category;
		$arr_info;
		$err=array();
		if(isset($arr['id']) && isset($arr['move-parent-group']) && isset($arr['deltype']) && isset($arr['movegroup'])){
			if(!isset($category->id_list[$arr['id']]))array_push($err,'要删除的分组不存在');
			$arr_info['id']=$arr['id'];
			if($arr['deltype']=='no'){
				if(isset($category->id_list[$arr['movegroup']])){
					if($arr['movegroup']==$arr['id'])array_push($err,'要移动到的分类与删除的分类相同不存在');
					else{
						$arr_info['move']=$arr['movegroup'];
					}
				}else array_push($err,'要移动到的分类不存在');
			}else if($arr['deltype']=='yes'){
				$arr_info['move']=0;
			}else array_push($err,'是否删除书籍参数出现错误');
			
			if(isset($category->id_list[$arr['move-parent-group']])){
				$flag=0;
				foreach($category->list as $value){
					if($value['id']==$arr['move-parent-group'] && $value['parent']!=0){
						array_push($err,'上级分类非顶级分类');
						$flag=1;
						break;
					}
				}
				if($flag==0){
					if($arr['move-parent-group']==$arr['id'])array_push($err,'移动上级分类重复');
					else $arr_info['parent']=$arr['move-parent-group'];
				}else{
					$arr_info['parent']=0;
				}
			}else $arr_info['parent']=0;
			
			if(empty($err))array_push($this->arr_list,$arr_info);
			array_push($this->err_list,$err);
			return $err;
		}else{
			array_push($this->err_list,array('参数不全'));
			return array('参数不全');
		}
	}
	function get_post_arr($post){
		$this->post_arr=array();
		foreach($post as $name => $value){
			$a=explode("=",$name);
			if(isset($a[1]))$this->post_arr[$a[1]][$a[0]]=$value;
		}
	}
	function add_all(){
		$err=array();
		foreach($this->post_arr as $v)array($err,$this->add($v));
		return $err;
	}
	function del(){
		if(!empty($this->arr_list))foreach($this->arr_list as $n => $v){
			$err=array();
			if($v['move']==0){
				if(!$this->del_book($v['id']))array_push($err,'ID '.$v['id'].' :'.'删除图书失败');
			}else{
				if(!$this->move_book($v['id'],$v['move']))array_push($err,'ID '.$v['id'].' :'.'移动图书失败');
			}
			if(!$this->move_parent($v['id'],$v['parent']))array_push($err,'ID '.$v['id'].' :'.'上级分类移动失败');
			if(!$this->del_category($v['id']))array_push($err,'ID '.$v['id'].' :'.'分类删除出现失败');
			array_push($this->err_list,$err);
		}
		return $this->err_list;
	}
	function del_book($category){
		global $mysql;
		return $mysql->delete("library_book",'`category`='.$category);
	}
	function move_book($category,$new){
		global $mysql;
		return $mysql->up_sql_arr("library_book",array('category'=>$new),'`category`='.$category);		
	}
	function move_parent($category,$new){
		global $mysql;
		return $mysql->up_sql_arr("library_category",array('parent'=>$new),'`parent`='.$category);
	}
	function del_category($category){
		global $mysql;
		return $mysql->delete("library_category",'`id`='.$category);
	}
}
class library_book_check{
	function name_check($str){
		if(empty($str))return NULL;
		return $str;
	}
	function owner_check($id){
		if(empty($id))return NULL;
		global $all_user;		
		if(!isset($all_user->id_list[$id]))return NULL;
		return $id;
	}
	function libNumer_check($number){
		if(empty($number))return NULL;
		if(!(is_numeric($number) && $number==round($number)))return NULL;
		global $mysql;
		if($mysql->count_sql("library_book","`libNum`=".$number)!=0)return ' - 该编号的书籍已存在';
		return $number;		
	}
	function category_check($id){
		if(empty($id))return NULL;
		global $category;
		if(!isset($category->id_list[$id]))return NULL;
		return $id;
	}
	function ISBN_check($number){
		if(empty($number))return NULL;
		if(!(is_numeric($number) && $number==round($number)))return NULL;	
		if(!(strlen($number)==13 || strlen($number)==10))return NULL;
		return $number;
	}
	function publishTime_check($number){
		if(empty($number))return NULL;
		if(!(is_numeric($number) && $number==round($number)))return ' - 不是整型年份';
		if(strlen($number)!=6)return NULL;
		if(substr($number,0,4)<1900 || substr($number,0,4)>date("Y") || substr($number,4)>12 || substr($number,4)<1)return '时间区间有误';
		return $number;
	}
	function tiem_check($year,$month,$day){
		if(!checkdate($month,$day,$year))return NULL;
		return date("Y-m-d",strtotime($year.'-'.$month.'-'.$day));
	}
}
class library_book_edit extends library_book_check{
	private $post;
	private $book;
	private $id;
	var $err;
	private $check;
	function __construct($arr){
		$this->post=$arr;
		$this->err=array();
		$this->check=FALSE;
	}
	function check_parameter(){
		if(empty($this->post['id']))return 'ID错误';
		else $this->id=$this->post['id'];
		$status=$this->get_old_book();
		if($status!='OK')return $status;
		if(isset($this->post['beginYear']) && isset($this->post['beginMonth']) && isset($this->post['beginDay'])){
			$status=$this->tiem_check($this->post['beginYear'],$this->post['beginMonth'],$this->post['beginDay']);
			if($status!=NULL)$this->post['beginTime']=$status;
			else if($this->post['beginYear']=='0000' && $this->post['beginMonth']=='00' && $this->post['beginDay']=='00')$this->post['beginTime']='0000-00-00';
		}
		if(isset($this->post['borrowYear']) && isset($this->post['borrowMonth']) && isset($this->post['borrowDay'])){
			$status=$this->tiem_check($this->post['borrowYear'],$this->post['borrowMonth'],$this->post['borrowDay']);
			if($status!=NULL)$this->post['borrowTime']=$status;
			else if($this->post['borrowYear']=='0000' && $this->post['borrowMonth']=='00' && $this->post['borrowDay']=='00')$this->post['borrowTime']='0000-00-00';
		}
		$this->info_filter();
		if(empty($this->post))array_push($this->err,"没有信息需要修改");
		else foreach($this->post as $n => $v){
			$status=$this->post_check($n);
			if($status!='OK')array_push($this->err,$status);
		}	
		if(!empty($this->err))return '参数有误';
		$this->check=TRUE;
		return 'OK';
	}
	function updata(){
		if($this->check==FALSE)return '请先检测数据';
		global $mysql;
		if($mysql->up_sql_arr("library_book",$this->post,'`id`='.$this->id))return 'OK';
		else '数据更新失败';
	}
	function post_check($p){
		$status='';
		switch($p){
			case 'name'://名字
				$status=$this->name_check($this->post['name']);
				if($this->post['name']==$status)return 'OK';
				else return '图书名有误:'.$status;
			break;
			case 'libNum'://图书馆编号
				$status=$this->libNumer_check($this->post['libNum']);
				if($this->post['libNum']==$status || $this->post['libNum']==0)return 'OK';
				else return "图书馆编号有误:".$status;
			break;
			case 'ISBN'://ISBN编号
				$status=$this->ISBN_check($this->post['ISBN']);
				if($this->post['ISBN']==$status)return 'OK';
				else return "ISBN编号有误：".$status;			
			break;
			case 'press'://出版社
				if(strlen($this->post['press'])>2 && strlen($this->post['press'])<50)return 'OK';
				return '出版社长度不符';
			break;
			case 'editor'://编辑
				 if(strlen($this->post['editor'])>4 && strlen($this->post['editor'])<100)return 'OK';
				 else '编辑名长度不符';
			break;
			case 'publishTime'://出版社时间
				$status=$this->publishTime_check($this->post['publishTime']);
				if($this->post['publishTime']==$status)return 'OK';
				else return "出版时间有误:".$status;				
			break;
			case 'pricing'://价格
				if(!is_numeric($this->post['pricing']))return '错误的价格';
				else return 'OK';
			break;
			case 'category'://分类判断
				$status=$this->category_check($this->post['category']);
				if($this->post['category']==$status)return 'OK';
				else return "分类有误".$status;			
			break;
			case 'stuName'://所有者检测
				$status=$this->owner_check($this->post['stuName']);
				if($this->post['stuName']==$status)return 'OK';
				else return "图书所有者有误：".$status;			
			break;
			case 'nowBorrow':
				$status=$this->owner_check($this->post['nowBorrow']);
				if($this->post['nowBorrow']==$status)return 'OK';
				else return "当前借阅者有误:".$status;				
			break; 
			default:return 'OK';
		}
	}
	function get_old_book(){
		global $mysql;
		$this->book=$mysql->get_mysql_arr("library_book","*","`id`=".$this->id);
		if(isset($this->book[0]['id']) && $this->book[0]['id']==$this->id){
			$this->book=$this->book[0];
			return 'OK';
		}else return '该图书不存在';
	}
	function info_filter(){
		foreach($this->book as $n => $v){
			if(!isset($this->post[$n]))unset($this->book[$n]);
			else if($this->post[$n]==$v){
				unset($this->book[$n]);
				unset($this->post[$n]);
			}
		}
		foreach($this->post as $n => $v){
			if(!isset($this->book[$n]))unset($this->post[$n]);
		}
	}
}
class library_lent{
	var $id;
	var $user;
	var $book;
	function __construct($id,$user){
		$this->id=$id;
		$this->user=$user;
	}
	function check(){
		if(!(is_numeric($this->id) && $this->id==round($this->id) && !empty($this->id)))return "图书ID有误";
		global $mysql,$all_user;
		$s=$mysql->get_mysql_arr("library_book","*",'`id`='.$this->id);
		if(!isset($s[0]['nowBorrow']))return '图书不存在';
		if($s[0]['nowBorrow']!=0)return '该书已借给 '.$all_user->id_list[$s[0]['nowBorrow']];
		if(!isset($this->user))return '用户不存在';
		$this->book=$s[0];
		return 'OK';
	}
	function up_data(){
		global $mysql;
		$s1=$mysql->up_sql_arr("library_book",array('nowBorrow'=>$this->user,'borrowTime'=>date("Y-m-d")),'`id`='.$this->id);
		if(!$s1)return "图书借出失败";
		$s2=$this->up_history();
		if($s2!='OK')return '图书借出成功，更新历史失败';
		return 'OK';
	}
	function up_history(){
		global $mysql;
		$s=$mysql->inster("library_history",array("user"=>$this->user,'content'=>"借出书籍".$this->book['name']."(".$this->book['id'].")",'type'=>'lent'));
		if($s)return 'OK';
		return '更新历史失败';
	}
}
class library_book_return{
	private $id;
	private $type;
	private $user;
	var $book;
	function __construct($id,$type){
		$this->id=$id;
		$this->type=$type;	
	}
	function check(){
		global $mysql;
		$s=NULL;
		if($this->type=='id'){
			$s=$mysql->get_mysql_arr("library_book","*",'`id`='.$this->id);
		}else if($this->type=='lib' && $this->id>1){
			$s=$mysql->get_mysql_arr("library_book","*",'`libNum`='.$this->id);
		}else return '类型有误';
		if(count($s)==1 && !isset($s[0]['nowBorrow']))return '图书不存在';
		if($s[0]['nowBorrow']==0)return '该书未借出';
		$this->user=$s[0]['nowBorrow'];
		$this->book=$s[0];
		return 'OK';
	}
	function up_data(){
		global $mysql;
		if($this->type=='id'){
			$s=$mysql->up_sql_arr("library_book",array('nowBorrow'=>0,'borrowTime'=>'0000-00-00'),'`id`='.$this->id);
		}else if($this->type=='lib'){
			$s=$mysql->up_sql_arr("library_book",array('nowBorrow'=>0,'borrowTime'=>'0000-00-00'),'`libNum`='.$this->id);
		}
		if(!$s)return '还书 '.$this->book['name'].' 失败';
		$s=$this->up_history();
		if($s!='OK')return "图书归还成功 ,".$s;
		return 'OK';
	}
	function up_history(){
		global $mysql;
		$s=$mysql->inster("library_history",array('user'=>$this->user,'content'=>'归还书籍'.$this->book['name'],'type'=>'return'));
		if($s)return 'OK';
		return '历史写入失败';
	}
}
class library_book_history{
	var $type;
	var $user;
	var $list;
	function __construct($user=NULL,$type=NULL){
		if($user!=NULL){
			if(is_numeric($user) && $user==round($user) && $user>0)$this->user=$user;
		}else{
			$this->user=$GLOBALS['user']['id'];
		}
		if($type==NULL)$this->type='ALL';
		else{
			if(in_array($type,array("lent","return")))$this->type=$type;
			else $this->type='ALL';
		}
		$this->list=array();
		
	}
	function get_list(){
		global $mysql;
		if(!empty($this->list))return $this->list;
		if($this->type=='ALL')
			$this->list=$mysql->get_mysql_arr("library_history","*",'`user`='.$this->user);
		else
			$this->list=$mysql->get_mysql_arr("library_history","*",'`user`='.$this->user.' AND `type`="'.$this->type.'"');
		
		return $this->list;
	}
}
class library_book_publish extends library_book_check{
	private $sql_arr;
	var $status;
	function __construct(){
		$this->sql_arr=array();
		$this->status=FALSE;
	}
	function book_inster(){
		if(!$this->status)return '数据检查失败，无法执行操作';
		global $mysql;
		if(!$mysql->inster("library_book",$this->sql_arr))return '插入书籍失败';
		
		return 'OK';
	}
	function get_post_id(){
		$id=0;
		if($this->status=TRUE){
			global $mysql;
			$id=$mysql->AUTO_INCREMENT("library_book",0);
		}
		return $id;
	}
	function check($arr){
		$temp;
		if(isset($arr['bkn']) && $arr['bkn']!=''){
			$temp=$this->name_check($arr['bkn']);
			if($temp==$arr['bkn'])$this->sql_arr['name']=$arr['bkn'];
			else return '名字错误'.$temp;
		}else return '名字错误';
		if(!(isset($arr['publicBook']) && $arr['publicBook']==1)){
			if(isset($arr['owner'])){
				$temp=$this->owner_check($arr['owner']);
				if($temp==$arr['owner'])$this->sql_arr['stuName']=$arr['owner'];
				else return '图书所有者不正确'.$temp;
			}else return "未提交图书所有者参数";
		}
		
		if(isset($arr['cat'])  && $arr['cat']!=''){
			$temp=$this->category_check($arr['cat']);
			if($temp==$arr['cat'])$this->sql_arr['category']=$temp;
			else return '分类有误'.$temp;
		}else return '请提交一个分类';
		
		if(!((isset($arr['privateBook']) && $arr['privateBook']==1) || (isset($arr['publicBook']) && $arr['publicBook']==1))){
			if(isset($arr['libNum'])){
				$temp=$this->libNumer_check($arr['libNum']);
				if($temp==$arr['libNum'])$this->sql_arr['libNum']=$arr['libNum'];
				else return '图书编号参数有误'.$temp;
			}else return '未提交图书编号参数';
			if(isset($arr['years']) && isset($arr['months']) && isset($arr['days']) ){
				$temp=$this->tiem_check($arr['years'],$arr['months'],$arr['days']);
				if($temp!=NULL)$this->sql_arr['beginTime']=$temp;
				else return '请检查提交的时间';
			}else return '未提时间参数数';			
		}
		
		if(isset($arr['publishTime']) && $arr['publishTime']!=''){
			$temp=$this->publishTime_check($arr['publishTime']);
			if($temp==$arr['publishTime'])$this->sql_arr['publishTime']=$temp;
			else return '请提交一个正确的出版时间'.$temp;
		}else return '请提交出版时间';
		
		if(isset($arr['press']) && strlen($arr['press'])>2 && strlen($arr['press'])<50)$this->sql_arr['press']=$arr['press'];
		else return '请检查出版社';
		
		if(isset($arr['isbn']) && $arr['isbn']!=''){
			$temp=$this->ISBN_check($arr['isbn']);
			if($temp==$arr['isbn'])$this->sql_arr['ISBN']=$temp;
			else return '请提交一个正确的ISBN编号'.$temp;
		}else return '请提交ISBN编号';
		
		if(isset($arr['pricing']) && is_numeric($arr['pricing']))$this->sql_arr['pricing']=$arr['pricing'];
		else return '请检查价格';
		if(isset($arr['editor']) && strlen($arr['editor'])>4 && strlen($arr['editor'])<100)$this->sql_arr['editor']=$arr['editor'];
		else return '检查作者';
		
		if(isset($arr['content']) && strlen($arr['content'])<1000)$this->sql_arr['content']=$arr['content'];
		
		$this->status=TRUE;
		return 'OK';
	}
}
class library_category_up_number{
	var $old;
	var $new;
	var $num;
	function __construct(){

	}
	function up_all(){
		$this->get_all();
		$this->analyse_all();
		$this->up_all_mysql();
	}
	private function get_all(){
		global $mysql;
		$this->old=$mysql->get_mysql_arr("library_book",array('category'));
		$this->num=count($this->old);
	}
	private function analyse_all(){
		if($this->num==0)return;
		asort($this->old);
		$list=array();
		foreach($this->old as $v){
			if(!isset($list[$v['category']]))$list[$v['category']]=array();
			array_push($list[$v['category']],0);
		}
		foreach($list as $id=>$v){
			$this->new[$id]=count($v);
		}
	}
	private function up_all_mysql(){
		if($this->num==0)return;
		global $mysql;
		$mysql->up_sql_arr("library_category",array('count'=>0));
		foreach($this->new as $n => $v)
			$mysql->up_sql_arr("library_category",array('count'=>$v),'`id`='.$n);
	}
}
class library_all_action{
	var $arr;
	var $content;
	function get_select_id($post,$s='select'){
		$this->arr=array();
		foreach($post as $n => $v){
			if($n==$s.$v)array_push($this->arr,$v);
		}
		array_flip(array_flip($this->arr));
		return $this->arr;
	}
	function get_content($table='library_category'){
		global $mysql;
		$where='';
		foreach($this->arr as $v)$where.=$v.',';
		$where=substr($where,0,-1);
		$this->content=$mysql->get_mysql_arr($table,"*","`id` in ($where)");
	}
}
class library_book_all_edit extends library_all_action{
	var $post_arr;
	var $err;
	var $status;
	function __construct(){
		$this->err=array();
		$this->status=array();
	}
	function get_content(){
		return parent::get_content("library_book");
	}
	function get_post_arr($post){
		foreach($post as $name => $value){
			$a=explode("-",$name);
			if(isset($a[1]))$this->post_arr[$a[1]][$a[0]]=$value;
		}
	}
	function edit(){
		foreach($this->post_arr as $value){
			$edit_book=new library_book_edit($value);
			$status=$edit_book->check_parameter();
			if($status!='OK')$this->err[$value['id']]=$edit_book->err;
			else {
				$status=$edit_book->updata();
				if($status=='OK')$this->status[$value['id']]='OK';
				else array_push($this->err[$value['id']],$status);
			}
		}
	}
}
class library_category_all_edit extends library_all_action{
	var $post_arr;
	var $err;
	var $ok;
	function get_post_arr($post){
		foreach($post as $name => $value){
			$a=explode("=",$name);
			if(isset($a[1]))$this->post_arr[$a[1]][$a[0]]=$value;
		}
	}
	function up(){
		$this->ok=array();
		$this->err=array();
		foreach($this->post_arr as $v){
			$up_group=new up_library_category($v);
			$status=$up_group->check_info();
			if($status!='OK')$this->err[$v['id']]='ID '.$v['id'].' : '.$status;
			else{
				$status=$up_group->mysql_updata();
				if($status!='OK')$this->err[$v['id']]='ID '.$v['id'].' : '.$status;
				else $this->ok[$v['id']]='ID '.$v['id'].' : 成功更新';
			}
		}
		return $this->err;
	}
}
class library_option_up{
	var $post_arr;
	var $status;
	function __construct(){
		$this->post_arr=array();
		$this->status=FALSE;
	}
	function get_post($arr){
		if(isset($arr['library-one-page']))$this->post_arr['one_page']=$arr['library-one-page'];
		if(isset($arr['library-borrow-day']))$this->post_arr['borrow_day']=$arr['library-borrow-day'];
		if(empty($this->post_arr))return '未提交正常数据';
		else return 'OK';
	}
	function filter(){
		$arr=$GLOBALS['library_option'];
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
		if(isset($this->post_arr['one_page'])){
			if(!is_number($this->post_arr['one_page']) || $this->post_arr['one_page']<=0)array_push($err,"每页显示数量有误");
		}
		if(isset($this->post_arr['borrow_day'])){
			if(!is_number($this->post_arr['borrow_day']) && $this->post_arr['borrow_day']>0)array_push($err,"图书馆时间有效期有误");
		}
		if(count($err)==0)$this->status=TRUE;
		return $err;
	}
	function up(){
		if($this->status==FALSE)return array('验证失败');
		global $mysql;
		$err=array();
		foreach($this->post_arr as $n => $v){
			$status=$mysql->up_sql_arr("options",array("value"=>$v),'`name`="library_'.$n.'"');
			if($status!=TRUE)array_push($err,$n." 更新失败，值 ：".$v);
		}
		$this->status=FALSE;
		return $err;
	}
}
?>