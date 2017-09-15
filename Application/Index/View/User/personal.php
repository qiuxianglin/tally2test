<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>个人信息</title>
</head>
<body>

	<div class="wrapper_o">
		<div class="title">
			<span>个人信息</span>
		</div>
		<div class="xian"></div>
		<div class="content" style="min-height: 400px;">
			<div class="dl_div">
				<table>
					<tr>
						<td width="70" height="25" align="right" valign="middle">工号：</td>
						<td>{$userMsg['staffno']}</td>
					</tr>
					<tr>
						<td width="70" height="25" align="right" valign="middle">姓名：</td>
						<td>{$userMsg['user_name']}</td>
					</tr>
					<tr>
						<td width="70" height="25" align="right" valign="middle">职务：</td>
						<td>{$userMsg['position']}</td>
					</tr>
					<tr>
						<td width="70" height="25" align="right" valign="middle">登录时间：</td>
						<td>{$userMsg['last_logintime']}</td>
					</tr>
					<tr>
						<td width="70" height="25" align="right" valign="middle">签到班组：</td>
						<td>{$userMsg['shift']['department']}</td>
					</tr>
					<tr>
						<td width="70" height="25" align="right" valign="middle">签到班次：</td>
						<td>{$userMsg['shift']['sign_in_date']}&nbsp;{$userMsg['shift']['classes']}</td>
					</tr>
					<tr>
						<td width="70" height="25" align="right" valign="middle">签到时间：</td>
						<td>{$userMsg['shift']['time']}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</body>
</html>