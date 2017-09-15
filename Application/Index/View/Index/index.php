<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首页</title>
</head>
<body>

	<div class="wrapper_o">
		<div class="title" style="min-height: 460px"><br/>
			<center><span style="color:skyblue;">测试专用:您好！ 欢迎{$u['user_name']}登录南京中理云服务平台</span></center><br><br>
			<center><span style="color:skyblue;">最近签到信息</span>
			<p style="color:#000000;margin-left:30px;font-size:17px;margin-top:15px;">签到班组:{$userMsg['shift']['department']}</p>
			<p style="color:#000000;margin-left:30px;font-size:17px;">签到班次:{$userMsg['shift']['sign_in_date']}&nbsp;{$userMsg['shift']['classes']}</p>
			<p style="color:#000000;margin-left:30px;font-size:17px;">签到时间:{$userMsg['shift']['time']}</p>
			<span style="margin-top:10px;color:skyblue;">如需签入其他班组，请使用工班签到功能</span></center>
		</div>
	</div>
</body>
</html>