<!DOCTYPE HTML>
<html>
<head>
<title>修改密码</title>
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__MODULE__/User/changepwd" method="post">
			<table width="800" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="36" align="right" valign="middle">原密码：</td>
					<td><input type="password" class="article" name="oldpwd"
						required="required" /></td>
				</tr>

				<tr>
					<td height="36" align="right" valign="middle">新密码：</td>
					<td><input type="password" class="article" name="newpwd"
						required="required" /></td>
				</tr>

				<tr>
					<td height="36" align="right" valign="middle">重复密码：</td>
					<td><input type="password" class="article" name="againpwd"
						required="required" /></td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="qr" value="修&nbsp;改" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>
