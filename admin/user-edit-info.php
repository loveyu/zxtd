<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('user','edit_info');
set_page_power(array(0,1));

$all_group=new group;
$all_group->get_group();

if(isset($_GET['id']) && is_number($_GET['id']) && get_power()==1){
	$new_user=new user();
	$user_info=$new_user->get_user_info($_GET['id']);
	unset($new_user);
}else $user_info=$GLOBALS['user'];

set_title("编辑用户信息");
get_admin_header();
?>
<?php if(isset($_GET['status']))echo "<p>状态：".$_GET['status']."</p>";
if(count($user_info)==0)echo "<p>信息错误，请检查ID是否正确</p>";
else {
?>

<form action="user-action.php" method="post">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td>ID：</td>
<td><input name="id" value="<?php echo $user_info['id']?>" readonly="readonly"></td>
</tr>
<tr>
<td>用户名：</td>
<td><input value="<?php echo $user_info['user']?>" readonly="readonly"></td>
</tr>
<tr>
<td>姓名：</td>
<td><input name="name" type="text" value="<?php echo $user_info['name']?>"></td>
</tr>
<tr>
<td>专业：</td>
<td><input name="major" type="text" value="<?php echo $user_info['major']?>"></td>
</tr>
<tr>
<td>班级：</td>
<td><input name="class" type="text" value="<?php echo $user_info['class']?>"></td>
</tr>
<tr>
<td>年级：</td>
<td><select name="grade"><?php
$year=date("Y");
for($i=$year;$year+5>$i;$year--){
	if($year==$user_info['grade'])$check=' selected';
	else $check='';
	echo "<option".$check.">".$year."</option>";
}
?></select></td>
</tr>
<tr>
<td>分组：</td>
<td><select name="group"><?php
foreach($all_group->group as $v){
	if($v['id']==$user_info['group'])$check=' selected';
	else $check='';
	echo "<option value=\"".$v['id']."\"".$check.">".$v['id'].'-'.$v['name'].'-'.$all_group->lader_id[$v['lader']]."</option>";
}
?>
</select></td>
</tr>
<tr>
<td>电话：</td>
<td><input name="tel" type="text"  value="<?php echo $user_info['tel']?>"></td>
</tr>
<tr>
<td>QQ：</td>
<td><input name="qq" type="text"  value="<?php echo $user_info['qq']?>"></td>
</tr>
<tr>
<td>邮箱：</td>
<td><input name="email" type="text"  value="<?php echo $user_info['email']?>"></td>
</tr>
<tr>
<td><input name="act" value="editInfo" type="hidden"></td>
<td><button type="submit">确认修改</button></td>
</tr>
</table>
</form>
<?php }?>
<?php get_admin_footer();?>