<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));
set_page_type('option','option_library');
set_page_power(array(1));
set_title("网站设置");

library_option();//加载图书管理设置

get_admin_header();
?>
<div id="option-setting">

<h2 class="left">图书管理设置</h2>
<?php
if(isset($_GET['status'])){
	if($_GET['status']=='OK')
		echo '<p class="status blue center">成功更新设置信息</p>';
	else echo '<p class="status red center">',$_GET['status'],'</p>';
}
?>
<form method="post" action="option-action.php">
<table align="center" cellspacing="1" class="list">
<tr>
<th>图书每页显示数目</th>
<td><input name="library-one-page" value="<?php echo $GLOBALS['library_option']['one_page'];?>" type="text">
<span>天数</span></td>
</tr>

<tr>
<th>图书馆借书有效期目</th>
<td><input name="library-borrow-day" value="<?php echo $GLOBALS['library_option']['borrow_day'];?>" type="text">
<span>天数</span></td>
</tr>

</table>
<input type="hidden" name="type" value="library">
<p class="center"><button type="submit" class="button">保存更改</button></p>

</form>
</div>

<?php get_admin_footer();?>