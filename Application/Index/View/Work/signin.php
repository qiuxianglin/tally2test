<!DOCTYPE HTML>
<html>
<head>
<title>签到</title>
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__" method="post">
			<table width="100%" border="0">
				<tr>
					<td align="right" valign="middle">部门班组：</td>
					<td>
					   <select name="department_id" class="article" required="required"  style="width: 138px;">
							<option value="">--请选择--</option>
							<volist name="departmentList" id="dl">
							  <option <?php if ($dl['lvl']==1) echo 'disabled="disabled"';?> value="{$dl['id']}">{$dl['lefthtml']}{$dl['department_name']}</option>
							</volist>
					   </select>
					 </td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">签到日期：</td>
					<td>
					   <input type="text" class="article Wdate" value="<?php echo date('Y-m-d');?>" onClick="WdatePicker()" name="signdate" required="required" />
					</td>
				</tr>
				<tr>
					<td align="right" valign="middle">白班/夜班：</td>
					<td>
					   <select name="classes" class="article" required="required" style="width: 138px;">
							<option value="">--请选择--</option>
							<option value="1">白班</option>
							<option value="2">夜班</option>
					   </select>
					 </td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
					  <input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>