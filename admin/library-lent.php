<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('library','library_lent');
set_page_power(array(1));
set_title("图书借出");

$all_user=new user_info();
$all_user->get_id_list();
get_admin_header();

?>
<div id="library-lent">
<h2 class="center">图书借出</h2>
<?php
if(isset($_GET['status'])){
	if($_GET['status']=='OK')
		echo '<p class="status blue center">成功借出图书</p>';
	else echo '<p class="status red center">',$_GET['status'],'</p>';
}
?>
<form action="library-action.php" method="post">
图书ID:<input name="id" type="text" value="<?php if(isset($_GET['id']))echo $_GET['id']?>"><br>
借书人:<select name="user">
<?php 
foreach($all_user->id_list as $n => $v)
	echo "<option value=\"$n\">$v ($n)</option>\n";
?></select><br>
<input type="hidden" name="action" value="lent">
<button type="submit">提交</button>
</form>
</div>
<?php get_admin_footer();?>