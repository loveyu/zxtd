<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('user','user_group');
set_page_power(array(1));

set_title("用户组管理");

$group=new group();
$user_info=new user_info();

$group->get_group();
$user_info->get_all_user();
$user_info->get_id_list();
$group->get_user_group();

if(isset($_GET['status']) && $_GET['status']!='OK')add_footer_str('<script language="javascript">error_notic("'.$_GET['status'].'","");</script>');

get_admin_header();
?>
<div id="user-group">
<h2 class="center">用户组管理</h2>
<table border="0" align="center" cellpadding="0" cellspacing="0">
<tr class="title"><th>ID</th><th>分组名</th><th>组长</th><th>组员</th><th>操作</th></tr>
<?php
$i=0;
foreach($group->group as $v){
	echo '<tr class="list-',$i++%2,'"><td>',$v['id'],'</td>',
	'<td>',$v['name'],'</td>',
	'<td>';
	if(isset($user_info->id_list[$v['lader']]))echo '<a href="user.php?id=',$v['lader'],'">',$group->lader_id[$v['lader']],'(',$v['lader'],')</a>';
	echo "</td><td>";
	if(isset($group->group_list[$v['id']]))foreach($group->group_list[$v['id']] as $v2)echo '<a href="user.php?id=',$v2['id'],'">',$v2['name'],'</a> ';
	echo '</td><td><a href="user-group.php?act=edit&id=',$v['id'],'">编辑</a>|<a href="user-group.php?act=del&id=',$v['id'],'">删除</a></td>',"<tr>\n";
}
?>
</table>
</div>

<div id="new-user-group">
<h2 class="center">创建新用户分组</h2>
<form action="user-action.php" method="post">
<table>
<tr><th>分组名</th><td><input name="name" value="" type="text"></td></tr>
<tr><th>组长</th><td><select name="lader">
<?php
foreach($user_info->id_list as $n=>$v){
	echo '<option value="',$n,'">',$v,'(',$n,')',"</option>\n";
}
?>
</select></td></tr>
</table>
<input type="hidden" name="act" value="new-user-group">
<p class="submit"><button type="submit">创建分组</button></p>
</form>
</div>

<?php
if(isset($_GET['id']) && isset($_GET['act']) && $_GET['act']=='edit' && is_number($_GET['id'])){
	$group_arr=array();
	foreach($group->group as $v){
		if($v['id']==$_GET['id']){
			$group_arr=$v;
			break;
		}
	}
?>
<div id="edit-user-group">
<h2 class="center">编辑用户分组</h2>
<?php
if(!isset($group_arr['id']))echo '<p class="error">分组ID不存在或者ID有误</p>';
else{
?>
<form action="user-action.php" method="post">
<table>
<tr><th>分组名</th><td><input name="name" value="<?php echo $group_arr['name']?>" type="text"></td></tr>
<tr><th>组长</th><td><select name="lader">
<?php
foreach($user_info->id_list as $n=>$v){
	if($n==$group_arr['lader'])$c=' selected';else $c=NULL;
	echo '<option value="',$n,'"',$c,'>',$v,'(',$n,')',"</option>\n";
}
?>
</select></td></tr>
</table>
<input name="id" type="hidden" value="<?php echo $_GET['id']?>">
<input type="hidden" name="act" value="edit-user-group">
<p class="submit"><button type="submit">编辑分组</button></p>
</form>
<?php
}
?>
</div>
<?php
}
?>
<?php
if(isset($_GET['id']) && isset($_GET['act']) && $_GET['act']=='del' && is_number($_GET['id']) && $_GET['id']>0){
	$group->get_id_list();
?>
<div id="edit-user-group">
<h2 class="center">删除分组 <?php echo $group->id_list[$_GET['id']]?></h2>
<?php
if(!isset($group->id_list[$_GET['id']]))echo '<p class="error">分组ID不存在或者ID有误</p>';
else{
?>
<form action="user-action.php" method="post">
<table>
<tr><th>移动用户到</th><td><select name="group">
<option value=""></option>
<?php
foreach($group->id_list as $n=>$v){
	if($n!=$_GET['id'] && $n!=0)echo '<option value="',$n,'">',$v,'(',$n,')',"</option>\n";
}
?>
</select></td></tr>
</table>
<input name="id" type="hidden" value="<?php echo $_GET['id']?>">
<input type="hidden" name="act" value="del-user-group">
<p class="submit"><button type="submit">删除分组</button></p>
</form>
<?php }?>
</div>
<?php }?>
<div class="clear"></div>

<?php get_admin_footer();?>