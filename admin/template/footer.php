
</div>
<div id="footer">
<p class="left">共执行 <?php echo get_sql_query_count()?> 次查询， 页面加载 <?php echo get_runtime()?> 秒。</p>
<p class="right">知行团队</p>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
</div>
<?php
if(isset($GLOBALS['footer']))foreach($GLOBALS['footer'] as $v)echo $v,"\n";
?>
</body>
</html>
<?php exit;?>