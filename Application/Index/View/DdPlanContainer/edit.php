<head>
<title>拆箱系统_预报计划_新增配箱</title>
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__" method="post">
			<input type="hidden" name="id" value="{$msg['id']}">
			<h5 class="hh">修改预报配箱</h5>
			<hr style="width: 300px; margin: 5px auto; background: #abcdef; height: 2px;">
			<table>
				<tr>
					<td height="25" align="right" valign="middle">箱号：</td>
					<td><input type="text" class="article" style="width:180px;" name="CTNNO" value="{$msg['ctnno']}"/></td>
				</tr>
				<tr>
					<td height="25" align="right" valign="middle">尺寸：</td>
					<td><input type="text" class="article" style="width:180px;" name="CTNSIZE" value="{$msg['ctnsize']}"/></td>
				</tr>
				<tr>
					<td height="25" align="right" valign="middle">箱型：</td>
					<td><input type="text" class="article" style="width:180px;" name="CTNTYPE" value="{$msg['ctntype']}"/></td>
				</tr>
				<tr>
					<td height="25" align="right" valign="middle">铅封号：</td>
					<td><input type="text" class="article" style="width:180px;" name="SEALNO" value="{$msg['sealno']}"/></td>
				</tr>
				<tr>
					<td height="25" align="right" valign="middle">件数：</td>
					<td><input type="text" class="article" style="width:180px;" name="NUMBERSOFPACKAGES" value="{$msg['numbersofpackages']}"/></td>
				</tr>
				<tr>
					<td height="25" align="right" valign="middle">重量：</td>
					<td><input type="text" class="article" style="width:180px;" name="WEIGHT" value="{$msg['weight']}"/></td>
				</tr>
				<tr>
					<td height="25" align="right" valign="middle">体积：</td>
					<td><input type="text" class="article" style="width:180px;" name="VOLUME" value="{$msg['volume']}"/></td>
				</tr>
				<tr>
					<td height="25" align="right" valign="middle">集装箱状态：</td>
					<td>&nbsp;<span class="help-inline col-xs-12 col-sm-7"> <label
							class="middle"> <input id="id-disable-check" name="FLFLAG"
								type="radio" value="F" <?php echo ($msg['flflag'] == 'F' ) ? 'checked' : '';?>></label> <span style="color:black">整箱</span>
						</label>&nbsp;
					</span> <span class="help-inline col-xs-12 col-sm-7"> <label
							class="middle"> <input id="id-disable-check" name="FLFLAG"
								type="radio" value="L" <?php echo ($msg['flflag'] == 'L' ) ? 'checked' : '';?> > <span style="color:black">拼箱</span>
						</label>
					</span></td>

				</tr>
				<tr>
					<td height="25" align="right" valign="middle">危险品等级：</td>
					<td><input type="text" class="article" style="width:180px;" name="CLASSES" value="{$msg['classes']}"/></td>
				</tr>
				<tr>
					<td height="25" align="right" valign="middle">联合国编号：</td>
					<td><input type="text" class="article" style="width:180px;" name="UNDGNO" value="{$msg['undgno']}"/></td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
						<input type="reset" class="qr" value="重&nbsp;置" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>