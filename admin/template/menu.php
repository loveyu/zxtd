<?php
show_one_menu('library',array('url'=>'library.php','name'=>'图书管理','power'=>array(1,0)),array(
array('url'=>'library-manage.php','name'=>'图书管理','id'=>'library_manage','power'=>array(1)),
array('url'=>'library-category.php','name'=>'分类管理','id'=>'library_category','power'=>array(1)),
array('url'=>'library-publish.php','name'=>'图书发布','id'=>'library_publish','power'=>array(1)),
array('url'=>'library-return.php','name'=>'图书归还','id'=>'library_return','power'=>array(1)),
array('url'=>'library-lent.php','name'=>'图书借出','id'=>'library_lent','power'=>array(1)),
array('url'=>'library-history.php','name'=>'个人借书历史','id'=>'library_history','power'=>array(1,0))
));

show_one_menu('user',array('url'=>'user.php','name'=>'用户中心','power'=>array(1,0)),array(
array('url'=>'user-manage.php','name'=>'用户管理','id'=>'user_manage','power'=>array(1)),
array('url'=>'user-group.php','name'=>'用户组管理','id'=>'user_group','power'=>array(1)),
array('url'=>'user-new-user.php','name'=>'新建用户','id'=>'new_user','power'=>array(1)),
array('url'=>'user-edit-info.php','name'=>'修改个人信息','id'=>'edit_info','power'=>array(1,0)),
array('url'=>'user-change-pwd.php','name'=>'修改密码','id'=>'change_pwd','power'=>array(1,0))
));

show_one_menu('option',array('url'=>'option.php','name'=>'设置','power'=>array(1)),array(
array('url'=>'library-option.php','name'=>'图书管理','id'=>'option_library','power'=>array(1))
));

show_one_menu('login',array('url'=>'logining.php?act=logout','name'=>'退出','power'=>array(0,1)),array(
));
?>