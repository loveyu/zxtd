<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));
get_admin_header();
?>

<p>首页</p>

<?php get_admin_footer();?>