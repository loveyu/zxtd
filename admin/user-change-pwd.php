<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('user','change_pwd');
set_page_power(array(0,1));

get_admin_header();
?>
<?php if(isset($_GET['status']))echo "<p>状态：".$_GET['status']."</p>";?>
<form action="user-action.php" method="post">
原密码：<input name="oldpwd" type="password"><br>
新密码:<input name="pwd" type="password"><br>
再次输入:<input name="pwd2" type="password"><br>
<script language="javascript">function RefreshCode(obj){
            obj.src = obj.src + "?code=" + Math.random();
}</script>
验证码：<input name="Checkcode" type="text"><img src="../include/ver-code.php" align="absmiddle" onclick="RefreshCode(this)"><br>
<input name="act" value="changePwd" type="hidden">
<button type="submit">提交</button>
</form>
<?php get_admin_footer();?>