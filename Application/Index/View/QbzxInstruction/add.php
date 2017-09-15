<!DOCTYPE HTML>
<html>
<head>
<title>起驳装箱_添加作业指令</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
<script src="__PUBLIC__/admin/js/jquery-1.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
<script>
$(function(){
	$("#location_name").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($locationlist as $l)
			{
				echo '{title:"'.$l['location_code'].'",show:"'.$l['location_name'].'"},';
				echo '{title:"'.$l['location_name'].'",show:"'.$l['location_name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
})
</script>
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__/plan_id/{$plan_id}" method="post">
			<h5 class="hh">新增作业指令</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			<table width="100%" border="0">
				<tr>
					<td width="40%" align="right" valign="middle">委托编号：</td>
					<td><font>{$planMsg['entrustno']}</font></td>
				</tr>
				<tr>
					<td align="right" valign="middle">委托单位：</td>
					<td><font>{$planMsg['customer']}</font></td>
				</tr>
				<tr>
					<td align="right" valign="middle">船名：</td>
					<td><font>{$planMsg['ship_name']}</font></td>
				</tr>
				<tr>
					<td align="right" valign="middle">航次：</td>
					<td><font>{$planMsg['voyage']}</font></td>
				</tr>
				<tr>
					<td align="right" valign="middle">总票数：</td>
					<td><font>{$planMsg['total_ticket']}</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;总箱数：<font>{$planMsg['total_ctn']}</font></td>
				</tr>
				<tr>
					<td align="right" valign="middle">操作人员：</td>
					<td><font>{$userMsg['user_name']}</font></td>
				</tr>
				<tr>
					<td align="right" valign="middle">当前班组：</td>
					<td><font>{$userMsg['shift']['department']}&nbsp;&nbsp;{$userMsg['shift']['sign_in_date']}{$userMsg['shift']['classes']}</font></td>
				</tr>
				<tr>
					<td align="right" valign="middle">装箱场地：</td>
					<td>
					<input type="text" class="article" name="location_name" id="location_name" required="required" autocomplete="off" style="text-transform: uppercase;"/>
					</td>
				</tr>
				<tr>
					<td align="right" valign="middle">装箱方式：</td>
					<td><select name="loadingtype" class="article" required="required">
							<option value="">--请选择--</option>
							<option value="0">人工</option>
							<option value="1">机械</option>
					</select></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;<input
						type="reset" class="qr" value="重&nbsp;置" />&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
		</form>
	</div>
</body>