<!DOCTYPE HTML>
<html>
<head>
<title>收工交班</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__" method="post">
			<table>
				<tr>
				<td>&nbsp;&nbsp;&nbsp;</td>
					<td colspan="5">
					   交班留言：<br>
					 <textarea name="note" class="article" style="width: 410px; height: 200px"></textarea>
					</td>
				</tr>

				<tr>
				    <td>&nbsp;&nbsp;&nbsp;</td>
					<td><input type="submit" class="qr" value="收&nbsp;工" onclick="return confirm('你确认要交班吗？交班后本工班将无法继续操作！');" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>