<?php 
// 引用状态配置文件
require './Public/inc/status.config.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
	<script src="__PUBLIC__/admin/js/jquery-1.js"></script>
	<script src="__PUBLIC__/js/jquery.SuperSlide.2.1.1.js"></script>
	<script src="__PUBLIC__/admin/js/base.js"></script>
	<script>
$(document).ready(function(){
	var innerHeight=window.innerHeight;
	var min_height=innerHeight-366;
	$("#wapper").css("min-height", min_height+'px');
});
</script>

</head>
<body>
	<include file="Public:header" />
	{__CONTENT__}
	<include file="Public:footer" />
</body>