<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('library','library_manage');
set_page_power(array(1));
set_title("编辑信息");
get_admin_header();
$category=new library_category();
$category->get_id_list();
$all_user=new user_info();
$all_user->get_id_list();
$library = new library(); 
$book=$library->get_now_book();
if(!is_array($book)){

}else{
?>
<div id="library-edit">
<h2 align="center">图书信息编辑</h2>
<?php
if(isset($_GET['status'])){
	if($_GET['status']=='OK')
		echo '<p class="status blue center">成功编辑图书信息</p>';
	else echo '<p class="status red center">',$_GET['status'],'</p>';
}
?>
<form action="library-action.php" method="post">
<table border="1" align="center" cellspacing="0">
<tr><td>图书ID</td><td><input name="id" value="<?php echo $book['id']?>" readonly="readonly"></td><td>不可编辑</td></tr>
<tr><td>图书名</td><td><input name="name" value="<?php echo $book['name']?>"></td><td></td></tr>
<tr><td>图书馆编号</td><td><input name="libNum" value="<?php echo $book['libNum']?>"></td><td></td></tr>
<tr><td>ISBN</td><td><input name="ISBN" value="<?php echo $book['ISBN']?>"></td><td></td></tr>
<tr><td>出版社</td><td><input name="press" value="<?php echo $book['press']?>"></td><td></td></tr>
<tr><td>编辑、作者</td><td><input name="editor" value="<?php echo $book['editor']?>"></td><td></td></tr>
<tr><td>简介</td><td><textarea name="content" cols="" rows="4"><?php echo $book['content']?></textarea></td><td></td></tr>
<tr><td>出版时间</td><td><input name="publishTime" value="<?php echo $book['publishTime']?>"></td><td></td></tr>
<tr><td>定价</td><td><input name="pricing" value="<?php echo $book['pricing']?>"></td><td></td></tr>
<tr><td>分类</td><td>
<select name="category">
<?php
foreach($category->id_list as $n => $v){
	if($book['category']==$n)$c=' selected';else $c=NULL;
	echo "<option value=\"$n\"$c>$v ($n)</option>\n";
}
?></select>
</td><td></td></tr>
<tr><td>图书所有者</td><td><select name="stuName">
<?php
if($book['stuName']==0)echo "<option value=\"0\">无</option>\n";
foreach($all_user->id_list as $n => $v){
	if($book['stuName']==$n && $book['stuName']!=0)$c=' selected';else $c=NULL;
	echo "<option value=\"$n\"$c>$v ($n)</option>\n";
}
?>
</select></td><td></td></tr>
<tr><td>当前借阅</td><td><select name="nowBorrow">
<?php
if($book['nowBorrow']==0)echo "<option value=\"0\">无</option>\n";
foreach($all_user->id_list as $n => $v){
	if($book['nowBorrow']==$n && $book['nowBorrow']!=0)$c=' selected';else $c=NULL;
	echo "<option value=\"$n\"$c>$v ($n)</option>\n";
}
?></select>
</td><td></td></tr>

<tr><td>借来时间</td><td>
<?php
date_secect_print($book['beginTime'],array(
'year'=>'beginYear',
'month'=>'beginMonth',
'day'=>'beginDay'
),1);
?></td><td>全为零为私人<br>或公共图书</td></tr>
<tr><td>借出时间</td><td><?php
date_secect_print($book['borrowTime'],array(
'year'=>'borrowYear',
'month'=>'borrowMonth',
'day'=>'borrowDay'
),1);
?>
</td><td>全为零表示未借出</td></tr>
<tr><td>电子书ID</td><td><input name="ebook" value="<?php echo $book['ebook']?>"></td><td></td></tr>
<tr><td></td><td><button type="submit">更新</button></td><td></td></tr>
</table>
<input type="hidden" name="action" value="edit">
</form>
</div>
<?php
}
get_admin_footer();
?>