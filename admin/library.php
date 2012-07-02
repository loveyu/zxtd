<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));
set_page_type('library');
set_page_power(array(1,0));
get_admin_header();
?>

<p>图书</p>

<?php get_admin_footer();?>