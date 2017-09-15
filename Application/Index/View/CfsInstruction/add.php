<head>
<title>CFS装箱_添加指令</title>
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
					当前位置：<a href="__MODULE__/CfsInstruction/index">CFS装箱</a>&nbsp;&gt;&nbsp;<a
						href="__MODULE__/CfsInstruction/index">指令</a>&nbsp;&gt;&nbsp;添加指令
				</div>
			</div>
			<div class="right_t" style="text-align: center;">
				<form action="__ACTION__" method="post">
					<table border="" style="margin: 0 auto;">
						<tr>
							<td height="36" align="right" valign="middle">中文船名：</td>
							<td><input type="text" class="article" name="shipname" id="ship_name" required="required" autocomplete="off" style="text-transform: uppercase;"
								required="required" /></td>
							
							<td height="36" align="right" valign="middle">航次：</td>
							<td><input type="text" class="article" name="voyage"
								required="required" /></td>

							<td height="36" align="right" valign="middle">作业地点：</td>
							<td>
							   <input type="text" class="article" name="location_name" id="location_name" required="required" autocomplete="off" style="text-transform: uppercase;" />
							</td>
						</tr>
						<tr>
							<td height="36" align="right" valign="middle">委托单位：</td>
							<td>
							   <input type="text" class="article" name="entrust_company" id="entrust_company" autocomplete="off" style="text-transform: uppercase;" />
							</td>
							
							<td height="36" align="right" valign="top">装箱方式：</td>
							<td><select name="operation_type" class="article"
								required="required">
									<option value="">--请选择--</option>
									<option value="0">--人工--</option>
									<option value="1">--机械--</option>
							</select></td>
						</tr>

						<tr style="text-align: right;">
							<td colspan="6"><input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
								<input type="hidden" name="id">
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

	$("#ship_name").bigAutocomplete({
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
})
</script>
