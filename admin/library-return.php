<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('library','library_return');
set_page_power(array(1));
set_title("图书归还");

$all_user=new user_info();
$all_user->get_id_list();
get_admin_header();

?>
<div id="library-lent">
<h2 class="center">图书归还</h2>
<?php
if(isset($_GET['status'])){
	if($_GET['status']=='OK')
		echo '<p class="status blue center">成功归还图书</p>';
	else echo '<p class="status red center">',$_GET['status'],'</p>';
}
?>
<form action="library-action.php" method="get">
图书ID:<input name="id" value="<?php if(isset($_GET['id']))echo $_GET['id'];?>" type="text"><br>
ID类型:<select name="idtype">
<option value="id">数据ID</option>
<option value="lib">图书馆ID</option>
</select><br>
<input type="hidden" name="act" value="return">
<button type="submit">归还</button>
</form>
</div>
<?php get_admin_footer();?>