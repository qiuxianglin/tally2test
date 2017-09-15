<!DOCTYPE HTML>
<html>
<head>
<title>起泊装箱_作业指令_编辑配箱</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__/id/{$id}" method="post">
			<h5 class="hh">修改指令配箱</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			   <table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="36" align="right" valign="middle">箱号：</td>
					<td>
					   <input type="text" class="article" name="ctnno" required="required" value="{$msg['ctnno']}" />
					</td>
				</tr>
				<tr>
					<td width="195" height="46" align="right" valign="middle">箱型尺寸：</td>
					<td>
					   <select name="ctn_type_code" class="article" style="width: 137px">
							<volist name="contanierlist" id="cl">
							<option <?php if ($msg['ctn_type_code']==$cl['ctn_type_code']) echo 'selected';?> value="{$cl['ctn_type_code']}" style="color:#000000;">{$cl['ctn_type_code']}</option>
							</volist>
					   </select>
					</td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">箱主：</td>
					<td>
					   <select name="ctn_master" class="article" style="width: 137px">
							<volist name="cmlist" id="cm">
							<option <?php if ($msg['ctn_master']==$cm['ctn_master'])echo 'selected';?> value="{$cm['id']}">{$cm['ctn_master']}</option>
							</volist>
					    </select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="qr" value="修&nbsp;改" />&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
		</form>
	</div>
</body>