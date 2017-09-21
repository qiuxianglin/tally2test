<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<title>起泊装箱_作业指令_修改关</title>
<link rel="stylesheet" type="text/css" href="/tally2test/Public/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="/tally2test/index.php?s=/CfsOperationContainer/editlevel" method="post">
			<input type="hidden" name="operation_id" value="<?php echo ($operation_id); ?>"> 
			<input type="hidden" name="level_id" value="<?php echo ($level_id); ?>">
			<h5 class="hh">修改关</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			<table border="0">
				<tr>
					<td height="36" align="right" valign="middle">货物件数：</td>
					<td>
					   <input type="text" class="article" name="cargo_number" required="required" value="<?php echo ($msg['num']); ?>" />
					</td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">残损件数：</td>
					<td>
					   <input type="text" class="article" name="damage_num" required="required" value="<?php echo ($msg['damage_num']); ?>" />
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