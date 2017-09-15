<!DOCTYPE HTML>
<html>
<head>
<title>起泊装箱_作业指令_修改铅封号</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__/operation_id/{$operation_id}" method="post">
			<h5 class="hh">修改铅封号</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			<table border="0">
				<tr>
					<td height="36" align="right" valign="middle">铅封号：</td>
					<td>
					   <input type="text" class="article" name="sealno" required="required" value="{$sealno}" />
					</td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">修改原因：</td>
					<td>
					    <textarea rows="" cols="" name="remark" class="article" style="width: 300px; height: 80px; border: 1px solid #888;"></textarea>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="qr" value="修&nbsp;改" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>