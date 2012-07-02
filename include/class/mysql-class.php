<?php
function db_mysql_query($sql){
	$GLOBALS['QueryCount']++;
    return mysql_query($sql);
}
$QueryCount = 0;//统计数据库查询次数
class mysql{
	var $db_rp;
	private $db;
	private $db_charset;
	var $mysql_err;
	/** 初始化数据连接 */
	function __construct($hostname,$username,$password,$db,$dbcharset,$dbrp){
		$mysql_con=mysql_connect($hostname,$username,$password);
		$this->db_rp=$dbrp;
		$this->db=$db;
		$this->deb_charset=$dbcharset;
		if(!$mysql_con)die("无法链接数据库:".mysql_error());
		mysql_query("SET NAMES '$dbcharset'");
		mysql_query("SET CHARACTER_SET_CLIENT=$dbcharset");
		mysql_query("SET CHARACTER_SET_RESULTS=$dbcharset");		
/*		db_mysql_query("SET NAMES '$dbcharset'");
		db_mysql_query("SET CHARACTER_SET_CLIENT=$dbcharset");
		db_mysql_query("SET CHARACTER_SET_RESULTS=$dbcharset");
*/
		if(!mysql_select_db($db,$mysql_con))
			die("无法选择数据库，或数据库".$db."不存在:".mysql_error());
	}
	function query($sql){
		return db_mysql_query($sql);
	}
	function get_mysql_arr($table,$name,$while=1){
		$s=NULL;
		if($name[0]=='*')$s=' * ';
		else {
			foreach($name as $n => $v){
				$s.="`".$v."`,";
			}
			$s=substr($s,0,-1);
		}
		$sql="SELECT ".$s." FROM `".$this->db_rp.$table."` WHERE ".$while;
		if($s==' * ')return $this->sql_to_arr($sql);
		$result = db_mysql_query($sql);
		$return=array();
		$i=0;
		while($row = mysql_fetch_array($result)) {
			foreach($name as $n => $v)$return[$i][$v]=$row[$v];
			$i++;
		}
		return $return;
		
	}
	function count_sql($table,$while='1'){
		$sql="select count(*) from `".$this->db_rp.$table."` WHERE ".$while;
		$result=db_mysql_query($sql);
		$row=mysql_fetch_array($result);
		return $row[0];
	}
	function inster($table,$arr){
		$sql_name=null;
		$sql_value=null;
		foreach($arr as $name => $value){
			$sql_name.="`".$name."`,";
			$sql_value.="\"".$value."\",";
		}
		$sql_name=substr($sql_name,0,-1);
		$sql_value=substr($sql_value,0,-1);
		$sql="INSERT INTO `".$this->db_rp.$table."` ($sql_name) VALUES ($sql_value)";
		if(db_mysql_query($sql))return TRUE;
		else {
			$this->mysql_err=mysql_error();
			return FALSE;
		}
	}
	function get_mysql_all_array($table){
		$sql="SELECT * FROM `".$this->db_rp.$table."`";
		return $this->sql_to_arr($sql);
	}
	function up_sql_arr($table,$name_value,$while=1){
		$setStr=null;
		foreach($name_value as $name =>$value){
			$setStr.="`".$name."`='".$value."',";
		}
		$setStr=substr($setStr,0,-1);
		$sql="UPDATE `".$this->db_rp.$table."` SET $setStr WHERE ".$while;
		if(!db_mysql_query($sql))return false;
		return true;
	}
	function AUTO_INCREMENT($table,$now=1){
		$sql="SELECT Auto_increment FROM information_schema.tables  WHERE `table_name`='".$this->db_rp.$table."'";
		$result=db_mysql_query($sql);
		$row=mysql_fetch_array($result);
		if($now!=1)return $row[0]-1;
		else return $row[0];
	}
	function delete($table,$while=1){
		$sql='DELETE FROM `'.$this->db_rp.$table.'` WHERE '.$while;
		if(db_mysql_query($sql))return TRUE;
		else return FALSE;
	}
/*
	function get_mysql_arr($table,$arr,$name,$isnum=0){
		$s=NULL;
		if($name[0]=='*')$s=' * ';
		else {
			foreach($name as $n => $v){
				$s.="`".$v."`,";
			}
			$s=substr($s,0,-1);
		}
		$sql="SELECT ".$s." FROM `".$this->db_rp.$table."` WHERE ";
		$where=NULL;
		$i=0;
		if(!$isnum)$f="\"";
		else $f=NULL;
		foreach($arr as $id => $value){
			$i++;
			$where.=" `".$id."`=".$f.$value.$f." AND";
		}
		$where=substr($where,0,-4);
		$sql.="(".$where.")";
		if($s==' * ')return $this->sql_to_arr($sql);
		
		$result = db_mysql_query($sql);
		$return=array();
		$i=0;
		while($row = mysql_fetch_array($result)) {
			foreach($name as $n => $v)$return[$i][$v]=$row[$v];
			$i++;
		}
		return $return;
	}
	function get_mysql_more_sql($table,$c_arr,$w_arr,$s='"',$w_name='',$and='AND'){
		$w_str=NULL;
		$sql="SELECT ";
		if($c_arr[0]=='*')$w_str=' *';
		else {
			foreach($c_arr as $name => $value){
				$w_str.="`".$value."`,";
			}
			$w_str=substr($w_str,0,-1);
		}
		$sql.=" ".$w_str;
		$sql.=" FROM `".$this->db_rp.$table;
		$w_str=NULL;
		if($and!='AND')$and='OR';
		if($s!='"')$s=null;
		foreach($w_arr as $name => $value){
			if($w_name!='')$name=$w_name;
			$w_str.="`".$name."` = ".$s.$value.$s." ".$and." ";
		}
		if($and!='AND')$w_str=substr($w_str,0,-4);
		else $w_str=substr($w_str,0,-5);
		$w_str="(".$w_str.")";
		$sql.="` WHERE ".$w_str;
		return $sql;		
	}
	function get_mysql_option_arr($table,$name){
		$sql="SELECT * FROM `".$this->db_rp.$table."` WHERE `name` LIKE '$name"."_%'";
		$return=array();
		$result = db_mysql_query($sql);
		while($row = mysql_fetch_array($result)){
			$return[str_replace($name."_",NULL,$row['name'])]=$row['value'];
		}
		return $return;
	}
		
	function order_sql_str($table,$c_arr,$w_arr,$order_arr,$order,$begin,$end){
		//echo $sql="SELECT * FROM  `".MYSQL_PR."posts` WHERE (`post_type` = \"post\" AND `post_status` = \"publish\") ORDER  BY `post_date` DESC LIMIT ".$p_begin." , ".$p_end;
		$w_str=NULL;;
		$sql="SELECT ";
		if($c_arr[0]=='*')$w_str=' *';
		else {
			foreach($c_arr as $name => $value){
				$w_str.="`".$value."`,";
			}
			$w_str=substr($w_str,0,-1);
		}
		$sql.=" ".$w_str;
		$sql.=" FROM `".$this->db_rp.$table;
		$w_str=NULL;
		foreach($w_arr as $name => $value){
			$w_str.="`".$name."` = \"".$value."\" AND ";
		}
		$w_str=substr($w_str,0,-5);
		$w_str="(".$w_str.")";
		$sql.="` WHERE ".$w_str." ORDER BY ";
		
		$w_str=NULL;
		foreach($order_arr as $name => $value){
			$w_str.="`".$value."`,";
		}
		$w_str=substr($w_str,0,-1);
		if($order=="DESC" || $order==1)$order="DESC";
		else $order="ASC";
		$sql.=" ".$w_str." ".$order." LIMIT ".$begin." , ".$end;
		return $sql;
	}
	*/
	function sql_to_arr($sql){
		$return=array();
		$result = db_mysql_query($sql);
		$i=0;
		while($row = mysql_fetch_array($result)){
			$j=0;
			foreach($row as $name => $value)unset($row[$j++]);
			$return[$i]=$row;
			$i++;
		}
		return $return;	
	}
}
?>