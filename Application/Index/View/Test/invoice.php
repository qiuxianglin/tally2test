<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>模拟发送门到门拆箱预报计划</title>
</head>
<body>

	<div class="wrapper_o">
		<div class="title">
			<span>模拟发送门到门拆箱预报计划</span>
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
							<td height="25" align="right" valign="middle">申报公司代码：</td>
							<td><input type="text" class="itext" name="APPLYCODE" /></td>
						</tr>
						<tr>
							<td  height="25" align="right" valign="middle">申报公司名称：</td>
							<td><input type="text" class="itext" name="APPLYNAME" /></td>
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
							<td  height="25" align="right" valign="middle">货物名称：</td>
							<td><input type="text" class="itext" name="CARGONAME" /></td>
						</tr>
						<tr>
							<td  height="25" align="right" valign="middle">件数：</td>
							<td><input type="text" class="itext" name="NUMBERSOFPACKAGES" /></td>
						</tr>
						<tr>
							<td  height="25" align="right" valign="middle">货物包装：</td>
							<td><input type="text" class="itext" name="PACKAGE" /></td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">标志：</td>
							<td><input type="text" class="itext" name="MARK" /></td>
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
							<td width="70" height="25" align="right" valign="middle">运输条款：</td>
							<td>	
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="TRANSIT" value="CY" type="radio" checked>
									<span class="lbl">CY</span>
									</label>
								</span>
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="TRANSIT" value="CFS" type="radio">
									<span class="lbl">CFS</span>
									</label>
								</span>
							</td>
							
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">拆箱类别：</td>
							<td>	
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="CATEGORY" value="1" type="radio">
									<span class="lbl">港内拆箱</span>
									</label>
								</span>
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="CATEGORY" value="2" type="radio" checked>
									<span class="lbl">港外拆箱</span>
									</label>
								</span>
								
							</td>
								
							
						</tr>
							<tr> <td style="border-bottom:1px solid #000;width:200px;">集装箱1清单</td><td style="border-bottom:1px solid #000;width:200px;"></td></tr>
						<tr>
							<td height="25" align="right" valign="middle">箱号：</td>
							<td><input type="text" class="itext" name="CTNNO_1" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">尺寸：</td>
							<td><input type="text" class="itext" name="CTNSIZE_1" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">箱型：</td>
							<td><input type="text" class="itext" name="CTNTYPE_1" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">铅封号：</td>
							<td><input type="text" class="itext" name="SEALNO_1" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">件数：</td>
							<td><input type="text" class="itext" name="NUMBERSOFPACKAGES_1" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">重量：</td>
							<td><input type="text" class="itext" name="WEIGHT_1" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">体积：</td>
							<td><input type="text" class="itext" name="VOLUME_1" /></td>
						</tr>
						<tr>
							<td  height="25" align="right" valign="middle">集装箱状态：</td>
							<td>	
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="FLFLAG_1" type="radio" value="F" checked>
									<span class="lbl">整箱</span>
									</label>
								</span>
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="FLFLAG_1" type="radio" value="L">
									<span class="lbl">拼箱</span>
									</label>
								</span>
							</td>

						</tr>
						<tr>
							<td height="25" align="right" valign="middle">危险品等级：</td>
							<td><input type="text" class="itext" name="CLASSES_1" /></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">联合国编号：</td>
							<td><input type="text" class="itext" name="UNDGNO_1" /></td>
						</tr>
		<tr> <td style="border-bottom:1px solid #000;width:200px;">集装箱2清单</td><td style="border-bottom:1px solid #000;width:200px;"></td></tr>
            <tr>
              <td height="25" align="right" valign="middle">箱号：</td>
              <td><input type="text" class="itext" name="CTNNO_2" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">尺寸：</td>
              <td><input type="text" class="itext" name="CTNSIZE_2" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">箱型：</td>
              <td><input type="text" class="itext" name="CTNTYPE_2" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">铅封号：</td>
              <td><input type="text" class="itext" name="SEALNO_2" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">件数：</td>
              <td><input type="text" class="itext" name="NUMBERSOFPACKAGES_2" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">重量：</td>
              <td><input type="text" class="itext" name="WEIGHT_2" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">体积：</td>
              <td><input type="text" class="itext" name="VOLUME_2" /></td>
            </tr>
            <tr>
              <td  height="25" align="right" valign="middle">集装箱状态：</td>
              <td>  
                <span class="help-inline col-xs-12 col-sm-7">
                  <label class="middle">
                  <input id="id-disable-check" name="FLFLAG_2" type="radio" value="F" checked>
                  <span class="lbl">整箱</span>
                  </label>
                </span>
                <span class="help-inline col-xs-12 col-sm-7">
                  <label class="middle">
                  <input id="id-disable-check" name="FLFLAG_2" type="radio" value="L">
                  <span class="lbl">拼箱</span>
                  </label>
                </span>
              </td>

            </tr>
            <tr>
              <td height="25" align="right" valign="middle">危险品等级：</td>
              <td><input type="text" class="itext" name="CLASSES_2" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">联合国编号：</td>
              <td><input type="text" class="itext" name="UNDGNO_2" /></td>
            </tr>
            
            <tr> <td style="border-bottom:1px solid #000;width:200px;">集装箱3清单</td><td style="border-bottom:1px solid #000;width:200px;"></td></tr>
            <tr>
              <td height="25" align="right" valign="middle">箱号：</td>
              <td><input type="text" class="itext" name="CTNNO_3" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">尺寸：</td>
              <td><input type="text" class="itext" name="CTNSIZE_3" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">箱型：</td>
              <td><input type="text" class="itext" name="CTNTYPE_3" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">铅封号：</td>
              <td><input type="text" class="itext" name="SEALNO_3" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">件数：</td>
              <td><input type="text" class="itext" name="NUMBERSOFPACKAGES_3" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">重量：</td>
              <td><input type="text" class="itext" name="WEIGHT_3" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">体积：</td>
              <td><input type="text" class="itext" name="VOLUME_3" /></td>
            </tr>
            <tr>
              <td  height="25" align="right" valign="middle">集装箱状态：</td>
              <td>  
                <span class="help-inline col-xs-12 col-sm-7">
                  <label class="middle">
                  <input id="id-disable-check" name="FLFLAG_3" type="radio" value="F" checked>
                  <span class="lbl">整箱</span>
                  </label>
                </span>
                <span class="help-inline col-xs-12 col-sm-7">
                  <label class="middle">
                  <input id="id-disable-check" name="FLFLAG_3" type="radio" value="L">
                  <span class="lbl">拼箱</span>
                  </label>
                </span>
              </td>

            </tr>
            <tr>
              <td height="25" align="right" valign="middle">危险品等级：</td>
              <td><input type="text" class="itext" name="CLASSES_3" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">联合国编号：</td>
              <td><input type="text" class="itext" name="UNDGNO_3" /></td>
            </tr>
            
            <tr> <td style="border-bottom:1px solid #000;width:200px;">集装箱4清单</td><td style="border-bottom:1px solid #000;width:200px;"></td></tr>
            <tr>
              <td height="25" align="right" valign="middle">箱号：</td>
              <td><input type="text" class="itext" name="CTNNO_4" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">尺寸：</td>
              <td><input type="text" class="itext" name="CTNSIZE_4" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">箱型：</td>
              <td><input type="text" class="itext" name="CTNTYPE_4" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">铅封号：</td>
              <td><input type="text" class="itext" name="SEALNO_4" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">件数：</td>
              <td><input type="text" class="itext" name="NUMBERSOFPACKAGES_4" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">重量：</td>
              <td><input type="text" class="itext" name="WEIGHT_4" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">体积：</td>
              <td><input type="text" class="itext" name="VOLUME_4" /></td>
            </tr>
            <tr>
              <td  height="25" align="right" valign="middle">集装箱状态：</td>
              <td>  
                <span class="help-inline col-xs-12 col-sm-7">
                  <label class="middle">
                  <input id="id-disable-check" name="FLFLAG_4" type="radio" value="F" checked>
                  <span class="lbl">整箱</span>
                  </label>
                </span>
                <span class="help-inline col-xs-12 col-sm-7">
                  <label class="middle">
                  <input id="id-disable-check" name="FLFLAG_4" type="radio" value="L">
                  <span class="lbl">拼箱</span>
                  </label>
                </span>
              </td>

            </tr>
            <tr>
              <td height="25" align="right" valign="middle">危险品等级：</td>
              <td><input type="text" class="itext" name="CLASSES_4" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">联合国编号：</td>
              <td><input type="text" class="itext" name="UNDGNO_4" /></td>
            </tr>
            
            <tr> <td style="border-bottom:1px solid #000;width:200px;">集装箱5清单</td><td style="border-bottom:1px solid #000;width:200px;"></td></tr>
            <tr>
              <td height="25" align="right" valign="middle">箱号：</td>
              <td><input type="text" class="itext" name="CTNNO_5" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">尺寸：</td>
              <td><input type="text" class="itext" name="CTNSIZE_5" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">箱型：</td>
              <td><input type="text" class="itext" name="CTNTYPE_5" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">铅封号：</td>
              <td><input type="text" class="itext" name="SEALNO_5" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">件数：</td>
              <td><input type="text" class="itext" name="NUMBERSOFPACKAGES_5" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">重量：</td>
              <td><input type="text" class="itext" name="WEIGHT_5" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">体积：</td>
              <td><input type="text" class="itext" name="VOLUME_5" /></td>
            </tr>
            <tr>
              <td  height="25" align="right" valign="middle">集装箱状态：</td>
              <td>  
                <span class="help-inline col-xs-12 col-sm-7">
                  <label class="middle">
                  <input id="id-disable-check" name="FLFLAG_5" type="radio" value="F" checked>
                  <span class="lbl">整箱</span>
                  </label>
                </span>
                <span class="help-inline col-xs-12 col-sm-7">
                  <label class="middle">
                  <input id="id-disable-check" name="FLFLAG_5" type="radio" value="L">
                  <span class="lbl">拼箱</span>
                  </label>
                </span>
              </td>

            </tr>
            <tr>
              <td height="25" align="right" valign="middle">危险品等级：</td>
              <td><input type="text" class="itext" name="CLASSES_5" /></td>
            </tr>
            <tr>
              <td height="25" align="right" valign="middle">联合国编号：</td>
              <td><input type="text" class="itext" name="UNDGNO_5" /></td>
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