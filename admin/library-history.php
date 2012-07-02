<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('library','library_history');
set_page_power(array(0,1));
set_title("个人借书历史");
$all_user=new user_info();
$all_user->get_id_list();
get_admin_header();
?>
<div id="library-lent">
<h2 class="center">个人借书历史</h2>
<form method="get">
<?php
	if(get_power()==1){
		echo '用户:<select name="id">',"\n";
		foreach($all_user->id_list as $n => $v){
			if(isset($_GET['id']) && $_GET['id']==$n)$c=' selected';else $c=NULL;
			echo "<option value=\"$n\"$c>$v ($n)</option>\n";
		}
		echo '</select>',"\n";
	}
?>
类别<select name="type"><option value="all">全部</option><option value="lent">借出</option><option value="return">归还</option></select>
<button type="submit">查看</button>
</form>

<table>
<?php
if(!(isset($_GET['id']) && get_power()==1 && isset($all_user->id_list[$_GET['id']])))$_GET['id']=NULL;
if(!isset($_GET['type']))$_GET['type']=NULL;
$history=new library_book_history($_GET['id'],$_GET['type']);
$history->get_list();

if(count($history->list)==0)echo "<tr>数据为空</tr>";
foreach($history->list as $n => $v){
?>
<tr><td><?php echo $v['time']?></td><td><?php echo $v['content']?></td></tr>
<?php }?>
</table>
</div>
<?php get_admin_footer();?>