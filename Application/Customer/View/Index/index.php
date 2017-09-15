<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首页</title>
</head>
<body>
<?php if($_SESSION['id'] == '')
{
	echo "123";
}
?>
	<div class="wrapper_o">
		<div class="title" style="min-height: 460px"><br/>
			<center><span style="color:skyblue;">您好！ 欢迎{$customerMsg['customer_name']}登录南京中理客户系统</span></center><br><br>
		</div>
	</div>
</body>
</html>