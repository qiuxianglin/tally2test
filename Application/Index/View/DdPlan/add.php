<head>
<title>拆箱预报计划录入</title>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css"
	type="text/css" />
<style>
#wapper, .right, .right_t {
	width: 1000px
}
</style>
</head>
<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/DdInstruction/index">拆箱系统</a>&nbsp;&gt;&nbsp;拆箱预报计划录入
				</div>
			</div>
			<div class="right_t" style="text-align: center;">
				<form action="__ACTION__" method="post">
					<table border="" style="margin: 0 auto;width:900px;">
						<tr>
							<td height="36" align="right" valign="middle">委托编号：</td>
							<td><input type="text" class="article" name="ORDERID" required="required" /></td>

							<td height="36" align="right" valign="middle">船名：</td>
							<td><input type="text" class="article" name="VSLNAME" id="shipname" required="required" autocomplete="off" style="text-transform: uppercase;" /></td>

							<td height="36" align="right" valign="middle">航次：</td>
							<td><input type="text" class="article" name="VOYAGE" required="required"/></td>
						</tr>

						<tr>
							<td height="36" align="right" valign="middle">申报公司代码：</td>
							<td><input type="text" class="article" name="APPLYCODE" required="required" value="0000"/></td>

							<td height="36" align="right" valign="middle">申报公司名称：</td>
							<td><input type="text" class="article" name="APPLYNAME" value="南京中理" />
							</td>
							
							<td height="36" align="right" valign="middle">拆箱地点：</td>
							<td><input type="text" class="article" name="LOCATION_NAME" id="location_name" required="required" autocomplete="off" style="text-transform: uppercase;" /></td>
							
						</tr>
						<tr>
							<td height="36" align="right" valign="middle">业务系统：</td>
							<td>
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="BUSINESS" value="dd" type="radio" checked>
									<span class="lbl" style="color:black">门到门拆箱</span>
									</label>
								</span>&nbsp;&nbsp;
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="BUSINESS" value="cfs" type="radio">
									<span class="lbl" style="color:black">CFS拆箱</span>
									</label>
								</span>
							</td>
							<td height="36" align="right" valign="middle">运输条款：</td>
							<td>
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="TRANSIT" value="CY" type="radio" checked>
									<span class="lbl" style="color:black">CY</span>
									</label>
								</span>&nbsp;&nbsp;
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="TRANSIT" value="CFS" type="radio">
									<span class="lbl" style="color:black">CFS</span>
									</label>
								</span>&nbsp;&nbsp;
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="TRANSIT" value="公路转关" type="radio">
									<span class="lbl" style="color:black">公路转关</span>
									</label>
								</span>
							</td>

							<td height="36" align="right" valign="middle">拆箱类别：</td>
							<td>
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="CATEGORY" value="1" type="radio">
									<span class="lbl" style="color:black">港内拆箱</span>
									</label>
								</span>&nbsp;&nbsp;
								<span class="help-inline col-xs-12 col-sm-7">
									<label class="middle">
									<input id="id-disable-check" name="CATEGORY" value="2" type="radio" checked>
									<span class="lbl" style="color:black">港外拆箱</span>
									</label>
								</span>
							</td>
						</tr>
						<tr>
							<td height="36" align="right" valign="top">备注：</td>
							<td colspan="5">
								<textarea rows="" cols="" name="NOTE" class="article" style="width: 645px; height: 150px"></textarea>
							</td>
						</tr>
						<tr style="text-align: right;">
							<td colspan="6"><input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
								<input type="reset" class="qr" value="重&nbsp;置" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>
<script>
$(function(){
	$("#shipname").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($shiplist as $s)
			{
				echo '{title:"'.$s['ship_code'].'",show:"'.$s['ship_name'].'"},';
				echo '{title:"'.$s['ship_name'].'",show:"'.$s['ship_name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
	$("#location_name").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($locationlist as $s)
			{
				echo '{title:"'.$s['location_code'].'",show:"'.$s['location_name'].'"},';
				echo '{title:"'.$s['location_name'].'",show:"'.$s['location_name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
})
</script>