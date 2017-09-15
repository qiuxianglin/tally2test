<head>
<title>起驳装箱_预报计划录入</title>
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
					当前位置：<a href="__MODULE__/QbzxPlan/index">起驳装箱</a>&nbsp;&gt;&nbsp;<a
						href="__MODULE__/QbzxPlan/index">预报计划</a>&nbsp;&gt;&nbsp;预报计划录入
				</div>
			</div>
			<div class="right_t" style="text-align: center;">
				<form action="__ACTION__" method="post">
					<table border="" style="margin: 0 auto;">
						<tr>
							<td height="36" align="right" valign="middle">委托编号：</td>
							<td><input type="text" class="article" name="entrustno"
								required="required" /></td>

							<td height="36" align="right" valign="middle">委托单位：</td>
							<td><input type="text" class="article" name="entrust_company"
								id="entrust_company" required="required" autocomplete="off"
								style="text-transform: uppercase;" /></td>

							<td height="36" align="right" valign="middle">理货地点：</td>
							<td><input type="text" class="article" name="location_name"
								id="location_name" required="required" autocomplete="off"
								style="text-transform: uppercase;" /></td>
						</tr>

						<tr>
							<td height="36" align="right" valign="middle">船舶名称：</td>
							<td><input type="text" class="article" name="shipname"
								id="shipname" required="required" autocomplete="off"
								style="text-transform: uppercase;" /></td>

							<td height="36" align="right" valign="middle">航次：</td>
							<td><input type="text" class="article" name="voyage"
								required="required" /></td>

							<td height="36" align="right" valign="middle">总箱数：</td>
							<td><input type="text" class="article" name="total_ctn" value="" />
							</td>

						</tr>

						<tr>
							<td height="36" align="right" valign="middle">总票数：</td>
							<td><input type="text" class="article" name="total_ticket"
								required="required" /></td>

							<td height="36" align="right" valign="middle">总件数：</td>
							<td><input type="text" class="article" name="total_package" /></td>

							<td height="36" align="right" valign="middle">总重量：</td>
							<td><input type="text" class="article" name="total_weight"
								value="" /></td>

						</tr>

						<tr>
							<td height="36" align="right" valign="middle">货代：</td>
							<td><input type="text" class="article" name="cargo_agent"
								id="cargo_agent" required="required" autocomplete="off"
								style="text-transform: uppercase;" /></td>
						</tr>

						<tr>
							<td height="36" align="right" valign="top">装箱要求：</td>
							<td colspan="5"><textarea rows="" cols="" name="packing_require"
									class="article" style="width: 645px; height: 300px"></textarea>
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

	$("#entrust_company").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($customerlist as $c)
			{
				echo '{title:"'.$c['customer_code'].'",show:"'.$c['customer_name'].'"},';
				echo '{title:"'.$c['customer_name'].'",show:"'.$c['customer_name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});

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

	$("#cargo_agent").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($cargoAgentList as $cl)
			{
				echo '{title:"'.$cl['code'].'",show:"'.$cl['name'].'"},';
				echo '{title:"'.$cl['name'].'",show:"'.$cl['name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
})
</script>