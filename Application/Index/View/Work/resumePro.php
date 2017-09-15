<!DOCTYPE HTML>
<html>
<head>
<title>工班恢复</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__" method="post">
		<input type="hidden" name="shift_id" value="{$shift_id}">
			<table>
				<tr>
					<td align="right" valign="middle">恢复原因：</td>
					<td colspan="5">
					   <textarea name="reason" class="article" style="width: 350px; height: 240px"></textarea>
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="qr" value="恢复工班" onclick="return confirm('您确定要恢复该工班吗！');" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>