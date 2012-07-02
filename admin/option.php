<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));
set_page_type('option');
set_page_power(array(1));
set_title("网站设置");
get_admin_header();
?>
<div id="option-setting">
<h2 class="left">站点设置</h2>
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
<th>网站标题</th><td><input name="site-title" value="<?php echo $option->arr['sitename'];?>" type="text">
<span>设置网站标题</span></td>
</tr>

<tr>
<th>网站URL</th><td><input name="site-url" value="<?php echo $option->arr['siteurl'];?>" type="text">
<span>设置网站URL,后缀无斜杠 '/'</span></td>
</tr>

<tr>
<th>Cookies有效期（小时）</th><td><input name="cookie-time" value="<?php echo $option->arr['cookie_time'];?>" type="text">
<span>网站Cookies 有效期，单位为小时</span></td>
</tr>

<tr><th>Cookies密钥</th><td><input name="cookie-key" value="<?php echo $option->arr['cookie_key'];?>" type="text">
<span>网站Cookies密钥，更新后所有用户Cookie失效，包括管理员</span></td>
</tr>

</table>
<input type="hidden" name="type" value="option">
<p class="center"><button type="submit"  class="button">更新</button></p>
</form>
</div>

<?php get_admin_footer();?>