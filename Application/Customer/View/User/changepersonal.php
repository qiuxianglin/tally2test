<!DOCTYPE HTML>
<html>
<head>
<title>修改客户信息</title>
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__MODULE__/User/changepersonal" method="post">
			<table width="1200" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="36" align="right" valign="middle">客户代码：</td>
					<td><input type="text" class="article" name="customer_code"
						value="{$customerMsg['customer_code']}" readonly='readonly' style="width:180px"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">客户名称：</td>
					<td><input type="text" class="article" name="customer_name"
						value="{$customerMsg['customer_name']}" readonly='readonly' style="width:180px"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">客户简称：</td>
					<td><input type="text" class="article" name="customer_shortname"
						value="{$customerMsg['customer_shortname']}" readonly='readonly' style="width:180px"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">客户类别：</td>
					<td><input type="text" class="article" name="customer_category"
						value="{$customerMsg['customer_category']}" readonly='readonly' style="width:180px"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">联系人*：</td>
					<td><input type="text" class="article" name="linkman"
						value="{$customerMsg['linkman']}" style="width:180px"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">联系电话*：</td>
					<td><input type="text" class="article" name="telephone"
						value="{$customerMsg['telephone']}" style="width:180px"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">合同有效期：</td>
					<td><input type="text" class="article" name="contract_life"
						value="{$customerMsg['contract_life']}" readonly='readonly' style="width:180px"/></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="qr" value="修&nbsp;改" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>
