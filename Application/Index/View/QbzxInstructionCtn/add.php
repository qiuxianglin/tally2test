<!DOCTYPE HTML>
<html>
<head>
<title>起泊装箱_作业指令_新增配箱</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__/instruction_id/{$instruction_id}" method="post">
			<h5 class="hh">新增指令配箱</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			<table width="800" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="36" align="right" valign="middle">箱号：</td>
					<td>
					    <input type="text" class="article" name="ctnno" required="required" />
					</td>
				</tr>
				<tr>
					<td width="195" height="46" align="right" valign="middle">箱型尺寸：</td>
					<td>
					  <select name="ctn_type_code" class="article" style="width: 137px" required="required">
							<option value="">--请选择--</option>
							<volist name="contanierlist" id="c">
							<option value="{$c['ctn_type_code']}">{$c['ctn_type_code']}</option>
							</volist>
					  </select>
					</td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">箱主：</td>
					<td>
					   <select name="ctn_master" class="article"
						style="width: 137px" required="required">
							<option value="">--请选择--</option>
							<volist name="cmlist" id="cl">
							<option value="{$cl['id']}">{$cl['ctn_master']}</option>
							</volist>
					  </select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
					   <input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
					   <input type="reset" class="qr" value="重&nbsp;置" />&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>