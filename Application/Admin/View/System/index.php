<head>
<title>欢迎界面</title>
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/ad/css/system.css" />
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px" class="main_box">
			<div style="margin-top: 20px"></div>
			<div class="sinfo">
				<div class="head">
					个人信息：
					<font style="font-size: 12px">Profile Info</font>
				</div>
				<div class="cont">
					<div class="l">管理员名：</div>
					<div class="r">{$adminmsg['adminname']}</div>
				</div>
				<div class="cont">
					<div class="l">手机/EMAIL：</div>
					<div class="r">{$adminmsg['phone']} / {$adminmsg['email']}</div>
				</div>
				<div class="cont">
					<div class="l">所属管理员组：</div>
					<div class="r">{$adminmsg['group_title']}</div>
				</div>
				<div class="cont">
					<div class="l">注册时间：</div>
					<div class="r">{$adminmsg['register_time']}</div>
				</div>
				<div class="cont">
					<div class="l">最后登录时间：</div>
					<div class="r">{$adminmsg['last_login_time']}</div>
				</div>
				<div class="cont">
					<div class="l">最后登录IP：</div>
					<div class="r">{$adminmsg['last_login_ip']}</div>
				</div>
				<div class="cont">
					<div class="l">最后登录地址：</div>
					<div class="r">{$adminmsg['last_login_ip']}</div>
				</div>
				<div class="cont">
					<div class="l">登录次数：</div>
					<div class="r">{$adminmsg['login_num']}</div>
				</div>
			</div>

			<div class="sinfo">
				<div class="head">
					系统信息：
					<font style="font-size: 12px">System Info</font>
				</div>
				<div class="cont">
					<div class="l">操作系统：</div>
					<div class="r">{$info['system']}</div>
				</div>
				<div class="cont">
					<div class="l">运行环境：</div>
					<div class="r">{$info['server_software']}</div>
				</div>
				<div class="cont">
					<div class="l">PHP运行方式：</div>
					<div class="r">{$info['sapi']}</div>
				</div>
				<div class="cont">
					<div class="l">上传附件限制：</div>
					<div class="r">{$info['upload_max_filesize']}</div>
				</div>
				<div class="cont">
					<div class="l">执行时间限制：</div>
					<div class="r">{$info['max_execution_time']}</div>
				</div>
				<div class="cont">
					<div class="l">服务器域名/IP：</div>
					<div class="r">{$info['domain']}</div>
				</div>
				<div class="cont">
					<div class="l">磁盘剩余空间：</div>
					<div class="r">{$info['disk']}</div>
				</div>
				<div class="cont">
					<div class="l">服务器时间：</div>
					<div class="r">{$info['server_time']}</div>
				</div>
			</div>

		</div>
	</div>
	<include file="Public:systemleft" />
</div>