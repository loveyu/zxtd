<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('user','user_manage');
set_page_power(array(1));

set_title("用户管理");

$all_user=new user_info();
$all_user->get_all_user();
$all_group=new group;
$all_group->get_id_list();

if(isset($_GET['status']) && $_GET['status']!='OK')add_footer_str('<script language="javascript">error_notic("'.$_GET['status'].'","");</script>');

get_admin_header();
?>
<div id="user-manage">
<h2 class="center">用户管理中心</h2>
<table border="0" align="center" cellpadding="0" cellspacing="0">
<tr class="title"><th>ID</th><th>用户名</th><th>姓名</th><th>专业</th><th>班级</th><th>年级</th><th>分组</th><th>电话</th><th>QQ</th><th>邮箱</th><th>权限</th><th>状态</th><th>操作</th></tr>
<?php
$i=0;
foreach($all_user->all as $id=>$v){
	echo "<tr class=\"list-",$i++%2,"\">",
	"<td>",$v['id'],"</td>",
	"<td>",$v['user'],"</td>",
	"<td>",$v['name'],"</td>",
	"<td>",$v['major'],"</td>",
	"<td>",$v['class'],"</td>",
	"<td>",$v['grade'],"</td>",
	"<td>",$all_group->id_list[$v['group']],'-',$v['group'],"</td>",
	"<td>",$v['tel'],"</td>",
	"<td>",$v['qq'],"</td>",
	"<td>",$v['email'],"</td>",
	'<td><a href="user-manage.php?act=chang-power&id=',$v['id'],'" title="修改权限">',get_power_name($v['power']),"</a></td>",
	"<td>",$v['active']==1?'<a href="user-action.php?act=unactive&id='.$v['id'].'" title="取消激活">激活</a>':'<a href="user-action.php?act=active&id='.$v['id'].'" title="激活">未激活</a>',"</td>",
	'<td><a href="user-edit-info.php?id=',$v['id'],'">编辑</a>&nbsp;<a href="user-action.php?act=del&id=',$v['id'],'">删除</a></td>',
	"</tr>\n";
}
?>
</table>
</div>
<?php if(isset($_GET['act']) && isset($_GET['id']) && $_GET['act']=='chang-power'){?>

<div class="chang-power">
<h2 class="left">修改权限</h2>
<?php
$all_user->get_id_list();
if(!isset($all_user->id_list[$_GET['id']]))echo '<p class="error">ID有误</p>';
else{
	if($_GET['id']==get_user_id())echo '<p  class="error">不允许修改自己的权限</p>';
	else{
		foreach($all_user->all as $v)
			if($v['id']==$_GET['id']){
?>
<form action="user-action.php" method="post">
<table>
<tr><th>姓名</th><td><?php echo $v['name'],'(',$v['id'],')';?></td></tr>
<tr><th>原权限</th><td><?php echo get_power_name($v['power']);?></td></tr>
<tr><th>新权限</th><td><select name="power">
<option value=""></option>
<?php
$power_arr=get_power_array();
foreach($power_arr as $n=>$v2)echo '<option value="',$n,'">',$v2,'</option>';
?>
</select></td></tr>
</table>
<input type="hidden" name="act" value="chang-power">
<input type="hidden" name="id" value="<?php echo $v['id']?>">
<p class="submit"><button type="submit">更新</button></p>
</form> 
<?php
				break;
			}	
	}
}
?>
</div>
<?php }?>
<?php get_admin_footer();?>