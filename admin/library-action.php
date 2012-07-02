<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('library');
set_page_power(array(1));
set_title("图书操作");
$all_user=new user_info();
$all_user->get_id_list();
$category=new library_category();
$category->get_id_list();
get_admin_header();

$status=NULL;
if(!isset($_POST['action'])){
	if(isset($_GET['act'])){
		switch($_GET['act']){
			case 'del':{
				$book_del=new library_book_del();
				if(isset($_GET['id'])){
					$book_del->add($_GET['id']);
					$status=$book_del->del();
					if($status=='OK')print_successful_notice("图书删除成功");
					else print_err_notice($status);
				}
				library_category_up_all();
			}break;
			case 'return':{
				$book_return=new library_book_return($_GET['id'],$_GET['idtype']);
				$status=$book_return->check();
				if($status!='OK'){
					print_err_notice($status);
				}else{
					$status=$book_return->up_data();
					if($status!='OK')print_err_notice($status);
					else print_successful_notice("成功归还图书 ".$book_return->book['name']."  所有者:".$all_user->id_list[$book_return->book['nowBorrow']],'library-return.php?status=OK');
				}
			}break;
			default:print_err_notice('未知操作');
		}
	}else print_err_notice('未知操作','library.php');
}else{

	switch($_POST['action']){
		case 'publish':{
			$new_book=new library_book_publish();
			$status=$new_book->check($_POST);
			if($status!='OK'){
				print_err_notice($status);
			}else{
				$status=$new_book->book_inster();
				if($status!='OK')print_err_notice($status);
				print_successful_notice("成功发布图书 ".$_POST['bkn'],'library-publish.php?status=OK&id='.$new_book->get_post_id());
			}
			library_category_up_all();
		}break;
		case 'edit':{
			$edit_book=new library_book_edit($_POST);
			$status=$edit_book->check_parameter();
			if($status!='OK')print_err_arr_notice($status,$edit_book->err);
			else {
				$status=$edit_book->updata();
				if($status=='OK')print_successful_notice("成功修改图书 ".$_POST['name']." 信息",'library-edit.php?status=OK&id='.$_POST['id']);
				else print_err_arr_notice($status);
			}
			library_category_up_all();
		}break;
		case 'lent':{
			if(isset($_POST['id']) && isset($_POST['user'])){
				$book_lent=new library_lent($_POST['id'],$_POST['user']);
				$status=$book_lent->check();
				if($status!='OK')print_err_notice($status);
				else{
					$status=$book_lent->up_data();
					if($status!='OK')print_err_notice($status);
					else print_successful_notice("成功借出图",'library-lent.php?status=OK');
				}
			}else{
				print_err_notice('借出参数有误');
			}
		}break;
		case 'all-edit':{
			$all_edit=new library_book_all_edit();
			$arr=$all_edit->get_select_id($_POST,'select');
			$all_edit->get_content();
?>
<div id="library-all-edit">
<form action="library-action.php" method="post">
<table>
<?php
if(count($all_edit->content)>0)echo "<tr><td>ID</td><td>名字</td><td>图书编号</td><td>ISBN</td><td>出版社</td><td>作者</td><td>出版时间</td><td>价格</td><td>分类</td><td>所有者</td><td>当前借阅</td></tr>\n";
foreach($all_edit->content as $book){
?>
<tr>
<td><input name="id-<?php echo $book['id']?>" value="<?php echo $book['id']?>" readonly="readonly"></td>
<td><input name="name-<?php echo $book['id']?>" value="<?php echo $book['name']?>"></td>
<td><input name="libNum-<?php echo $book['id']?>" value="<?php echo $book['libNum']?>"></td>
<td><input name="ISBN-<?php echo $book['id']?>" value="<?php echo $book['ISBN']?>"></td>
<td><input name="press-<?php echo $book['id']?>" value="<?php echo $book['press']?>"></td>
<td><input name="editor-<?php echo $book['id']?>" value="<?php echo $book['editor']?>"></td>
<td><input name="publishTime-<?php echo $book['id']?>" value="<?php echo $book['publishTime']?>"></td>
<td><input name="pricing-<?php echo $book['id']?>" value="<?php echo $book['pricing']?>"></td>
<td>
<select name="category-<?php echo $book['id']?>">
<?php
foreach($category->id_list as $n => $v){
	if($book['category']==$n)$c=' selected';else $c=NULL;
	echo "<option value=\"$n\"$c>$v ($n)</option>\n";
}
?></select>
</td>
<td><select name="stuName-<?php echo $book['id']?>">
<?php
if($book['stuName']==0)echo "<option value=\"0\">无</option>\n";
foreach($all_user->id_list as $n => $v){
	if($book['stuName']==$n && $book['stuName']!=0)$c=' selected';else $c=NULL;
	echo "<option value=\"$n\"$c>$v ($n)</option>\n";
}
?>
</select></td>
<td><select name="nowBorrow-<?php echo $book['id']?>">
<?php
if($book['nowBorrow']==0)echo "<option value=\"0\">无</option>\n";
foreach($all_user->id_list as $n => $v){
	if($book['nowBorrow']==$n && $book['nowBorrow']!=0)$c=' selected';else $c=NULL;
	echo "<option value=\"$n\"$c>$v ($n)</option>\n";
}
?></select>
</td>
</tr>
<?php
}
?>
</table>
<input name="action" value="all-succ-edit" type="hidden">
<button type="submit">更新</button>
</form>
</div>
<?php
		}break;
		case 'all-del':{
			$all_del=new library_book_del();
			$all_del->get_select_id($_POST,'select');
			$status=$all_del->del_all();
			if($status!='OK')print_err_notice($status);
			else print_successful_notice("成功删除图书",'library-manage.php?status=OK&Stype=all-del');
			library_category_up_all();
		}break;
		case 'all-succ-edit':{
			$all_succ_edit=new library_book_all_edit();
			$all_succ_edit->get_post_arr($_POST);
			$all_succ_edit->edit();
			if(count($all_succ_edit->err)==0)print_successful_notice("成功更新选定图书",'library-manage.php?status=OK&Stype=all-edit');
			else{
				$err=array();
				foreach($all_succ_edit->status as $n=>$v){
					array_push($err,'ID '.$n.'  编辑 '.$v);
				}
				foreach($all_succ_edit->err as $n=>$v){
					array_push($err,'ID :'.$n.'  编辑错误');
					foreach($v as $v2)array_push($err,$v2);
				}
				print_err_arr_notice("编辑出现错误",$err);
			}
			library_category_up_all();
		}break;
		default:print_err_notice('未知操作');
	}
}
get_admin_footer();
?>