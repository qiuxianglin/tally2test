<head>
<title>起驳装箱_预报计划_新增配箱</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__" method="post">
			<input type="hidden" name="plan_id" value="{$plan_id}">
			<h5 class="hh">新增预报配箱</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			<table>
				<tr>
					<td width="195" height="46" align="right" valign="middle">箱型尺寸：</td>
					<td>
					  <select name="ctn_type_code" class="article" style="width: 137px">
						<volist name="contanierlist" id="cl">
							<option value="{$cl['ctn_type_code']}">{$cl['ctn_type_code']}</option>
						</volist>
					  </select>
					</td>
				</tr>

				<tr>
					<td height="36" align="right" valign="middle">箱子个数：</td>
					<td>
					   <input type="text" class="article" name="quantity" required="required" />
					</td>
				</tr>

				<tr>
					<td height="36" align="right" valign="middle">箱主：</td>
					<td>
					   <select name="ctn_master" class="article" style="width: 137px">
						 <volist name="cmlist" id="cm">
							<option value="{$cm['id']}">{$cm['ctn_master']}</option>
						  </volist>
					   </select>
					</td>
				</tr>

				<tr>
					<td height="36" align="right" valign="middle">整拼标志：</td>
					<td>
					   <select name="flflag" class="article" style="width: 137px">
							<option value="F">整箱</option>
							<option value="L">拼箱</option>
					   </select>
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td>
					   <input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
					   <input type="reset" class="qr" value="重&nbsp;置" />
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>