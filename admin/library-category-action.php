<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('library','library_category');
set_page_power(array(1));
set_title("分类管理操作");
$category=new admin_library_category();
$category->get_all_list();
$category->get_id_list();
get_admin_header();
?>
<div id="library-category">
<div class="manage">
<?php
if(isset($_GET['action'])){
	switch($_GET['action']){
		case 'del':{
$arr=array();
if(isset($_GET['id']))
foreach($category->list as $v)if($v['id']==$_GET['id'])$arr=$v;
if(empty($arr)){
	print_err_notice('分类ID未找到','library-category.php');
}else{
?>
<div class="del-one-table">
<h2 align="center">删除图书分类</h2>
<form action="library-category-action.php" method="post">
<table id="table" border="1" align="center" cellspacing="0">
<tr><td class="right">分类名：</td><td class="center"><?php echo $arr['name'];?></td></tr>
<tr><td class="right">图书数量统计：</td><td class="center"><?php echo $arr['count'];?></td></tr>
<tr><td class="right">上级分类：</td><td class="center"><?php
$s=NULL;
foreach($category->list as $v)if($v['id']==$arr['parent'])$s=$v['name'];
if($s==NULL)echo "无";
else echo $s;
?></td></tr>
<tr><td class="right">是否删除相应图书：</td><td class="left"><select id="category_del_act" name="deltype">
<option value="no">保留</option>
<option value="yes">删除</option>
</select></td></tr>
<tr><td class="right">移动图书到(删除时无效)：</td><td class="left"><select name="movegroup">
<option value="0">(保留时必须)</option>
<?php
foreach($category->list as $v){
	if($v['id']!=$arr['id'])
		echo "<option value=\"",$v['id'],"\">",$v['id']," - ",$v['name'],"</option>\n";
}
?>
</select></td></tr>
<tr><td class="right">移动分类上级目录：</td><td class="left"><select name="move-parent-group">
<option value="0">无</option>
<?php
foreach($category->list as $v){
	if($v['id']!=$arr['id'] && $v['parent']==0)
		echo "<option value=\"",$v['id'],"\">",$v['id']," - ",$v['name'],"</option>\n";
}
?>
</select></td></tr>
<tr><td align="center"><button type="submit">提交</button></td><td><input name="type" value="succdel" type="hidden"><input name="id" value="<?php echo $arr['id']?>" type="hidden"></td></tr>
</table>
</form>
</div>
<?php
}
		}break;
		default:print_err_notice('未知操作');
	}
}else if(isset($_POST['type'])){
	switch($_POST['type']){
		case 'new-group':{
			$new_group=new new_library_category($_POST);
			$status=$new_group->check_info();
			if($status!='OK')print_err_notice($status,'');
			else{
				$status=$new_group->mysql_inster();
				if($status!='OK')print_err_notice($status,'');
				else print_err_notice("成功新建分类 ".$_POST['name'],'library-category.php?type=new&status=OK');
			}
		}break;
		case 'edit-group':{
			$up_group=new up_library_category($_POST);
			$status=$up_group->check_info();
			if($status!='OK')print_err_notice($status,'');
			else{
				$status=$up_group->mysql_updata();
				if($status!='OK')print_err_notice($status,'');
				else print_err_notice("成功更新分类 ".$_POST['name'],'library-category.php?action=edit&id='.$_POST['id'].'&type=edit&status=OK');
			}
			library_category_up_all();
		}break;
		case 'succdel':{
			$cat_del=new library_category_del();
			$status=$cat_del->add($_POST);
			if(!empty($status))print_err_arr_notice('出现参数错误',$status);
			else{
				$status=$cat_del->del();
				$err_arr=array();
				foreach($status as $v)$err_arr+=$v;
				if(count($err_arr)>0)print_err_arr_notice('操作错误',$err_arr);
				else print_successful_notice("成功删除分类",'library-category.php?status=OK');
			}
			library_category_up_all();
		}break;
		case 'all-edit':{
			$all_edit=new library_category_all_edit();
			$all_edit->get_select_id($_POST,'select');
			$all_edit->get_content();
?>
<div class="edit-all-table">
<h2 align="center">编辑图书分类</h2>
<form action="library-category-action.php" method="post">
<table id="table" border="1" align="center" cellspacing="0">
<tr><th>分组名</th><th>上级目录</th><th>分组描述</th></tr>
<?php foreach($all_edit->content as $arr){?>
<tr>
<td><input name="name=<?php echo $arr['id']?>" type="text" value="<?php echo $arr['name']?>"><input type="hidden" value="<?php echo $arr['id']?>" name="id=<?php echo $arr['id']?>"></td>
<td><?php
$flag=0;
foreach($category->list as $value){
	if($value['parent']==$arr['id']){
		$flag=1;
		break;
	}
}
if($flag==0){
?><select name="parent=<?php echo $arr['id']?>">
<option value="0">无上级目录</option>
<?php
$c=NULL;
foreach($category->list as $v2){
	if($arr['parent']==$v2['id'])$c=' selected';else $c=NULL;
	if($v2['parent']==0)
		echo "<option value=\"",$v2['id'],"\"$c>",$v2['id']," - ",$v2['name'],"</option>\n";
}
?></select><?php
}else{
	echo "存在分类使用该做为顶级分类";
}
?></td>
<td><textarea name="description=<?php echo $arr['id']?>" rows="2" cols="30"><?php echo $arr['description']?></textarea></td>
<?php }?>
<tr><td><button type="submit">提交</button><input name="type" value="all-succ-edit" type="hidden"></td></tr>
</table>
</form>
</div>
<?php
		}break;
		case 'all-del':{
			$all_del=new library_category_del();
			$all_del->get_select_id($_POST,'select');
			$all_del->get_content();
?>
<div class="del-all-table">
<h2 align="center">删除图书分类</h2>
<form action="library-category-action.php" method="post">
<table id="table" border="1" align="center" cellspacing="0">
<tr><th>分类名</th><th>图书数量统计</th><th>上级分类</th><th>删除相应图书</th><th>移动图书到(删除时无效)</th><th>移动分类上级目录</th></tr>
<?php foreach($all_del->content as $arr){?>
<tr>
<td><?php echo $arr['name'];?><input name="id=<?php echo $arr['id']?>" value="<?php echo $arr['id']?>" type="hidden"></td>
<td><?php echo $arr['count'];?></td>
<td><?php
$s=NULL;
foreach($category->list as $v)if($v['id']==$arr['parent'])$s=$v['name'];
if($s==NULL)echo "无";
else echo $s;
?></td>
<td><select name="deltype=<?php echo $arr['id']?>">
<option value="no">保留</option>
<option value="yes">删除</option>
</select></td>
<td><select name="movegroup=<?php echo $arr['id']?>">
<option value="0">(保留时必须)</option>
<?php
foreach($category->list as $v){
	if($v['id']!=$arr['id'])
		echo "<option value=\"",$v['id'],"\">",$v['id']," - ",$v['name'],"</option>\n";
}
?>
</select></td>
<td><select name="move-parent-group=<?php echo $arr['id']?>">
<option value="0">无</option>
<?php
foreach($category->list as $v){
	if($v['id']!=$arr['id'] && $v['parent']==0)
		echo "<option value=\"",$v['id'],"\">",$v['id']," - ",$v['name'],"</option>\n";
}
?>
</select></td>
</tr>
<?php }?>
<tr><td><button type="submit">提交</button><input name="type" value="all-succ-del" type="hidden"></td></tr>
</table>
</form>
</div>
<?php 
		}break;
		case 'all-succ-edit':{
			$all_succ_edit=new library_category_all_edit();
			$all_succ_edit->get_post_arr($_POST);
			if(count($all_succ_edit->up())==0)print_successful_notice("成功更新分类",'library-category.php?status=OK');
			else print_err_arr_notice('操作错误',array_merge($all_succ_edit->ok,$all_succ_edit->err));
			library_category_up_all();
		}break;
		case 'all-succ-del':{
			
			$all_succ_del=new library_category_del();
			$all_succ_del->get_post_arr($_POST);
			$status=$all_succ_del->add_all();
			if(!empty($status))print_err_arr_notice('出现参数错误',$status);
			else{
				$status=$all_succ_del->del();
				$err_arr=array();
				foreach($status as $v)$err_arr+=$v;
				if(count($err_arr)>0)print_err_arr_notice('操作错误',$err_arr);
				else print_successful_notice("成功删除分类",'library-category.php?status=OK');
			}
			library_category_up_all();
		}break;
		default:print_err_notice('POST操作未知','');
	}
}else{
	print_err_notice('未知操作','library-category.php');
}
?>
</div>
</div>
<?php get_admin_footer();?>
