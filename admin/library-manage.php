<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('library','library_manage');
set_page_power(array(1));
set_title("图书管理");

$category=new library_category();
$category->get_id_list();
$all_user=new user_info();
$all_user->get_id_list();
	
library_option();
get_admin_header();
$library=new library();
$library->check_parameter();
$library->get_book();

?>
<div id="library">
<div class="manage">
<h2 align="center">图书管理</h2>
<form name="tableform" action="library-action.php" method="post">
<table border="0" align="center" cellspacing="0">
<?php
if(empty($library->list))echo "<tr class=\"title\"><td>没有搜索到相关图书</td></tr>\n";
else{
	echo "<tr class=\"title\"><th>ID</th><th>图书名</th><th>图书馆<br>编号</th><th>ISBN</th><th>分类</th><th>所有者</th><th>借来时间</th><th>借阅者</th><th>借出时间</th><th>距到期</th><th>电子书</th><th>操作</th><th><a href=\"javascript:selectAll('select');\" title=\"全选\\取消全选\">选择</a></th></tr>\n";
	$i=0;
	foreach($library->list as $v){
		echo "<tr title=\"";
		echo "作者或编辑:",$v['editor'],"\n出版社:",$v['press'],"\n出版时间:",$v['publishTime'],"\n内容简介:",$v['content'];
		echo "\" class=\"tr-",$i++%2,"\" onClick =\"javascript:checkbox_change('tableform','checkbox-",$v['id'],"');\">\n<td>",$v['id'],"</td>\n";
		echo '<td><a href="#">',$v['name'],"</a></td>\n";

if(!empty($v['libNum']))echo "<td>",$v['libNum'],"</td>\n";
else echo "<td>非图书馆</td>";	
	
		echo "<td><a href=\"http://book.douban.com/isbn/",$v['ISBN'],"/\" target=\"_blank\" title=\"在豆瓣搜索\">",$v['ISBN'],"</a></td>\n";
		echo '<td><a href="library-manage.php?cat=',$v['category'],'" title="分类图书信息">',$category->id_list[$v['category']],"</a></td>\n";

if(!empty($v['stuName']))echo '<td><a href="user.php?id=',$v['stuName'],'" title="查看用户信息">',$all_user->id_list[$v['stuName']],"</a></td>\n";
else echo "<td>公共图书</td>";

if($v['beginTime']!='0000-00-00')echo "<td>",$v['beginTime'],"</td>\n";
else echo '<td>无效</td>';

if(!empty($v['nowBorrow']))echo '<td><a href="user.php?id=',$v['nowBorrow'],'" title="查看用户信息">',$all_user->id_list[$v['nowBorrow']],"</a></td>\n";
else echo "<td>无人借阅</td>";

		echo "<td>",$v['borrowTime'],"</td>\n";
		
if($v['beginTime']!='0000-00-00' && !empty($v['nowBorrow']))echo '<td>',$GLOBALS['library_option']['borrow_day']-round((strtotime(date("Y-m-d"))-strtotime($v['beginTime']))/86400),' 天</td>';
else echo '<td>无效</td>';

		echo "<td>",$v['ebook'],"</td>\n";
		echo '<td><a href="library-edit.php?id=',$v['id'],'">编辑</a>|<a href="library-action.php?act=del&id=',$v['id'],'">删除</a></td>',"\n";
		echo "<td><input type=\"checkbox\" onClick =\"javascript:checkbox_change('tableform','checkbox-",$v['id'],"');\" id=\"checkbox-",$v['id'],"\" name=\"select",$v['id'],"\" value=\"",$v['id'],"\"></td>\n</tr>\n\n";
	}
	echo '<tr class="act"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><button onclick="if(confirm(&#39;你确定要编辑这些分组么?&#39;)){document.tableform.action.value = &#39;all-edit&#39;;document.tableform.submit();}" class="botton">批量编辑</button></td><td><button onclick="if(confirm(&#39;你确定删除这些分组么?&#39;)){document.tableform.action.value = &#39;all-del&#39;;document.tableform.submit();}" class="botton">删除</button></td></tr>';
}
?>
</table>
<?php //for($i=0;$i<80;$i++)echo "$i<br>\n";?>
<input type="hidden" name="action" value="">
</form>

<form action="" method="get">
图书名:<input type="text" name="bkn"><br>
所有者:<input type="text" name="owner"><br>
借书者:<input type="text" name="borrow"><br>
分类名:<input type="text" name="cat"><br>
图书编号:<input type="text" name="libNum"><br>
ISBN:<input type="text" name="isbn"><br>
出版社:<input type="text" name="press"><br>
出版时间:<input type="text" name="publishTime">(如201008)<br>
作者:<input type="text" name="editor"><br>
简介:<input type="text" name="content"><br>
定价:<input type="text" name="pricing">元(支持 >,<,= )<br>
距到期:<input type="text" name="day">天(支持 >,<,= )<br>
公共书籍:<select name="ispublicbook"><option value=""></option><option value="1">是</option><option value="2">否</option></select><br>
私人图书::<select name="privateBook"><option value=""></option><option value="0">否</option><option value="1">是</option></select><br>
是否借出:<select name="isborrow"><option value=""></option><option value="1">借出</option><option value="2">未借出</option></select><br>
是否有电子书:<select name="haveEbook"><option value=""></option><option value="1">有</option><option value="2">无</option></select><br>
借来时间:<select name="comYear"><option></option><?php
$year=date("Y");
for($i=2012;$i<=$year;$i++)echo "<option>$i</option>";
?></select>
<select name="comMonth"><option></option><?php
for($i=1;$i<=12;$i++)echo "<option>$i</option>";
?></select>
<select name="comDay"><option></option><?php
for($i=1;$i<=31;$i++)echo "<option>$i</option>";
?></select><br>
借出时间:<select name="toYear"><option></option><?php
for($i=2012;$i<=$year;$i++)echo "<option>$i</option>";
?></select>
<select name="toMonth"><option></option><?php
for($i=1;$i<=12;$i++)echo "<option>$i</option>";
?></select>
<select name="toDay"><option></option><?php
for($i=1;$i<=31;$i++)echo "<option>$i</option>";
?></select><br>
<button type="submit">查询</button>
</form>

</div>
</div>
<?php get_admin_footer();?>