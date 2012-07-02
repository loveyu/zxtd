<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('library','library_publish');
set_page_power(array(1));
set_title("图书发布");

$category=new library_category();
$category->get_id_list();
$all_user=new user_info();
$all_user->get_id_list();
get_admin_header();

?>
<div id="library-publish">
<h2 align="center">图书发布</h2>
<?php
if(isset($_GET['status'])){
	if($_GET['status']=='OK')
		echo '<p class="status blue center">图书发布成功</p>';
	else echo '<p class="status red center">',$_GET['status'],'</p>';
}
?>
<form action="library-action.php" method="post">
<table>
<tr><th></th><th></th><th width="100px"></th>
<tr><td>图书名:</td><td><input type="text" name="bkn"></td><td></td></tr>
<tr><td>所有者:</td><td><select name="owner">
<?php 
foreach($all_user->id_list as $n => $v)
	echo "<option value=\"$n\">$v ($n)</option>\n";
?></select></td><td></td></tr>
<tr><td>图书馆编号:</td><td><input type="text" name="libNum"></td><td></td></tr>
<tr><td>借来时间:</td><td>
<SELECT name="years" onChange="setDay(this);"><script>forto(2012,<?php echo date("Y");?>)</script></SELECT>年
<SELECT name="months" onChange="setDay(this);"><script>forto(1,12)</script></SELECT>月
<SELECT name="days"></SELECT>日
</td><td></td></tr>
<tr><td>公共图书:</td><td><select name="publicBook"><option value="0">否</option><option value="1">是</option></select></td><td>为公共时 图书馆编号、所有者、借来时间 无效</td></tr>
<tr><td>私人图书:</td><td><select name="privateBook"><option value="0">否</option><option value="1">是</option></select></td><td>为私人时  图书馆编号、借来时间  无效</td></tr>
<tr><td>分类名:</td><td><select name="cat">
<?php
foreach($category->id_list as $n => $v)
	echo "<option value=\"$n\">$v ($n)</option>\n";
?></select></td><td></td></tr>
<tr><td>ISBN:</td><td><input type="text" name="isbn"></td><td></td></tr>
<tr><td>出版社:</td><td><input type="text" name="press"></td><td></td></tr>
<tr><td>出版时间:</td><td><input type="text" name="publishTime"></td><td>(如201008)</td></tr>
<tr><td>定价:</td><td><input type="text" name="pricing"></td><td>为数字</td></tr>
<tr><td>作者:</td><td><input type="text" name="editor"></td><td>(逗号分隔)</td></tr>
<tr><td>简介:</td><td><textarea name="content" cols="" rows="3"></textarea></td><td>不超过500字</td></tr>
<tr><td></td><td><button type="submit">提交</button></td><td><input type="hidden" name="action" value="publish"></td></tr>
</table>
</form>
</div>
<?php get_admin_footer();?>