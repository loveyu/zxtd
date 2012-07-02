<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('library','library_category');
set_page_power(array(1));

$category=new admin_library_category();
$category->get_all_list();
$category->get_id_list();
get_admin_header();
?>
<div id="library-category">
<div class="manage">
<h2 align="center">分组管理</h2>
<form name="tableform" action="library-category-action.php" method="post">
<table cellspacing="0">
<?php
if(empty($category->list))echo "<tr class=\"title\"><td>没有分类，请新建分类</td></tr>\n";
else{
	echo "<tr class=\"title\"><th>ID</th><th>名称</th><th>描述</th><th>统计</th><th>上级目录</th><th>操作</th><th><a href=\"javascript:selectAll('select');\" title=\"全选\\取消全选\">选择</a></th></tr>\n";
	$i=1;
	foreach($category->list as $v){
		echo "<tr class=\"tr-",$i++%2,"\" onClick =\"javascript:checkbox_change('tableform','checkbox-",$v['id'],"');\"><td>",$v['id'],"</td><td><a href=\"library-manage.php?cat=",$v['id'],'" title="查看该分类">',$v['name'],"</a></td><td>",$v['description'],"</td><td>",$v['count'],"</td><td>";

		if($v['parent']==0)echo "无";
		else echo '<a href="library-manage.php?cat=',$v['parent'],'" title="查看该分类">',$category->id_list[$v['parent']],'</a>';
		
		echo "</td><td><a href=\"library-category.php?action=edit&id=",$v['id'],"\">编辑</a>&nbsp;<a href=\"library-category-action.php?action=del&id=",$v['id'],"\">删除</a></td><td><input type=\"checkbox\" onClick =\"javascript:checkbox_change('tableform','checkbox-",$v['id'],"');\" id=\"checkbox-",$v['id'],"\" name=\"select",$v['id'],"\" value=\"",$v['id'],"\"></td></tr>\n";
	}
	echo '<tr class="act"><td></td><td></td><td></td><td></td><td></td><td><button onclick="if(confirm(&#39;你确定要编辑这些分组么?&#39;)){document.tableform.type.value = &#39;all-edit&#39;;document.tableform.submit();}" class="botton">批量编辑</button></td><td><button onclick="if(confirm(&#39;你确定删除这些分组么?&#39;)){document.tableform.type.value = &#39;all-del&#39;;document.tableform.submit();}" class="botton">删除</button></td></tr>';
}
?>
<input type="hidden" name="type" value="">
</table>
</form>
</div>
<div class="new-group">
<h3 align="center">新建分组</h3>
<?php
if(isset($_GET['type']) && $_GET['type']=='new'){
	if(isset($_GET['status'])){
		if($_GET['status']=='OK')
			echo '<p class="status blue center">成功新建分类</p>';
		else echo '<p class="status red center">',$_GET['status'],'</p>';
	}
}
?>
<form name="new-group" action="library-category-action.php" method="post">
<table>
<tr><td>分组名:</td><td><input name="name" type="text"></td></tr>
<tr><td>上级目录:</td><td><select name="parent">
<option value="0">无上级目录</option>
<?php
foreach($category->list as $v){
	if($v['parent']==0)
		echo "<option value=\"",$v['id'],"\">",$v['id']," - ",$v['name'],"</option>\n";
}
?></select></td></tr>
<tr><td>分组描述:</td><td><textarea name="description" rows="3" cols="30"></textarea></td></tr>
<tr><td><input type="hidden" name="type" value="new-group"></td><td><button type="submit" class="botton">创建</button></td></tr>
</table>
</form>
</div>
<?php
if(isset($_GET['action']) && $_GET['action']=='edit'){
	$arr=NULL;
	if(isset($_GET['id']))foreach($category->list as $v)
		if($v['id']==$_GET['id'])$arr=$v;
	if(!empty($arr)){?>
<div class="edit-group">
<h3 align="center">编辑分组</h3>
<?php
if(isset($_GET['type']) && $_GET['type']=='edit'){
	if(isset($_GET['status'])){
		if($_GET['status']=='OK')
			echo '<p class="status blue center">成功更新分类信息</p>';
		else echo '<p class="status red center">',$_GET['status'],'</p>';
	}
}
?>
<form name="edit-group" action="library-category-action.php" method="post">
<table>
<tr><td>分组名:</td><td><input name="name" type="text" value="<?php echo $arr['name']?>"></td></tr>
<tr><td>上级目录:</td><td><?php
$flag=0;
foreach($category->list as $value){
	if($value['parent']==$arr['id']){
		$flag=1;
		break;
	}
}
if($flag==0){
?><select name="parent">
<option value="0">无上级目录</option>
<?php
$c=NULL;
foreach($category->list as $v2){
	if($arr['parent']==$v2['id'])$c=' selected';else $c=NULL;
	if($v2['parent']==0)
		echo "<option value=\"",$v2['id'],"\"$c>",$v2['id']," - ",$v2['name'],"</option>\n";
}
?></select><?php
}else{
	echo "存在分类使用该做为顶级分类";
}
?></td></tr>
<tr><td>分组描述:</td><td><textarea name="description" rows="3" cols="30"><?php echo $arr['description']?></textarea></td></tr>
<tr><td><input type="hidden" name="type" value="edit-group"><input type="hidden" value="<?php echo $arr['id']?>" name="id"></td><td><button type="submit" class="botton">更新</button></td></tr>
</table>
</form>
</div>
<?php } }?>
<div class="clear"></div>
</div>
<?php get_admin_footer();?>