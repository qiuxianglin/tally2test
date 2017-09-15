<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>支付结果回执</title>
</head>
<body>

	<div class="wrapper_o">
		<div class="title">
			<span>支付结果回执</span>
		</div>
		<div class="xian"></div>
		<div class="content" style="min-height: 400px;">
			<div class="dl_div">
				<form action="__ACTION__" method="post">
					<table style="width: 485px">
						<tr>
							<td width="120" height="25" align="right" valign="middle">委托编号：</td>
							<td><input type="text" class="itext" name="ORDERID" /></td>
						</tr>
						<tr>
							<td width="120" height="25" align="right" valign="middle">委托日期：</td>
							<td><input type="text" class="itext" name="ORDER_DATE" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">船名：</td>
							<td><input type="text" class="itext" name="VSLNAME" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">航次：</td>
							<td><input type="text" class="itext" name="VOYAGE" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">提单号：</td>
							<td><input type="text" class="itext" name="BLNO" /></td>
						</tr>
						
						<tr>
							<td  height="25" align="right" valign="middle">付费方代码：</td>
							<td><input type="text" class="itext" name="PAYCODE" /></td>
						</tr>
						<tr>
							<td  height="25" align="right" valign="middle">付费方名称：</td>
							<td><input type="text" class="itext" name="PAYMEN" /></td>
						</tr>
						<tr>
							<td  height="25" align="right" valign="middle">金额：</td>
							<td><input type="text" class="itext" name="AMOUNT" /></td>
						</tr>
						<tr>
							<td  height="25" align="right" valign="middle">货物名称：</td>
							<td><input type="text" class="itext" name="CARGONAME" /></td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">件数：</td>
							<td><input type="text" class="itext" name="NUMBERSOFPACKAGES" /></td>
						</tr>
						<tr>
							<td  height="25" align="right" valign="middle">拼箱状态：</td>
							<td>	
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="LCL" value="N" type="radio" checked>
									<span class="lbl">整箱</span>
									</label>
								</span>
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="LCL" value="Y" type="radio">
									<span class="lbl">拼箱</span>
									</label>
								</span>
							</td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">收货人：</td>
							<td><input type="text" class="itext" name="CONSIGNEE" /></td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">拆箱地点：</td>
							<td><input type="text" class="itext" name="UNPACKAGINGPLACE" /></td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">危险品等级：</td>
							<td><input type="text" class="itext" name="CLASSES" /></td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">联合国编号：</td>
							<td><input type="text" class="itext" name="UNDGNO" /></td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">联系人：</td>
							<td><input type="text" class="itext" name="CONTACTUSER" /></td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">联系方式：</td>
							<td><input type="text" class="itext" name="CONTACT" /></td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">备注：</td>
							<td><textarea  name="NOTE" class="itext" style="height: 100px;" ;></textarea></td>
						</tr>		
						<tr>
							<td></td>
							<td><input type="submit" class="dl" value="提交" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>
</html>