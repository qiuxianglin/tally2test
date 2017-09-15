<head>
<title>新增船期</title>
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
</head>
<body>
	<div class="wrapper_o">
		<div class="title">
			<span></span>
		</div>
		<div style="min-height: 420px;">
			<div class="dl_div">
				<form action="__ACTION__" method="post">
					<table>
					    <tr>
							<td width="70" height="25" align="right" valign="middle">船舶名称：</td>
							<td>
							   <input type="text" class="itext" style="width: 187px;text-transform: uppercase;" id="ship" name="shipname" value="" autocomplete="off">
							</td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">航次：</td>
							<td><input type="text" class="itext" style="width: 187px" name="voyage"/></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">开航日期：</td>
							<td><input type="text" class="itext Wdate" style="width: 187px" name="sailing_date" onClick="WdatePicker()"/></td>
						</tr>
						<tr>
							<td height="25" align="right" valign="middle">到港日期：</td>
							<td><input type="text" class="itext Wdate" style="width: 187px" name="arrival_date" onClick="WdatePicker()"/></td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">起运港：</td>
							<td>
							   <input type="text" class="itext" style="width: 187px;text-transform: uppercase;" id="port" name="loading_port" value="" autocomplete="off">
							</td>
						</tr>
						<tr>
							<td width="70" height="25" align="right" valign="middle">目的港：</td>
							<td>
							   <input type="text" class="itext" style="width: 187px;text-transform: uppercase;" id="port2" name="destination_port" value="" autocomplete="off">
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" class="dl" style="background-color: #3398db" value="新增船期" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>
<script>
$(function(){
	$("#ship").bigAutocomplete({
		width:200,
		data:[
			<?php 
			foreach ($shiplist as $sl)
			{
				echo '{title:"'.$sl['ship_code'].'",show:"'.$sl['ship_name'].'"},';
				echo '{title:"'.$sl['ship_name'].'",show:"'.$sl['ship_name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#port").bigAutocomplete({
		width:200,
		data:[
			<?php 
			foreach ($portlist as $sl)
			{
				echo '{title:"'.$sl['code'].'",show:"'.$sl['name'].'"},';
				echo '{title:"'.$sl['name'].'",show:"'.$sl['name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#port2").bigAutocomplete({
		width:200,
		data:[
			<?php 
			foreach ($portlist as $sl)
			{
				echo '{title:"'.$sl['code'].'",show:"'.$sl['name'].'"},';
				echo '{title:"'.$sl['name'].'",show:"'.$sl['name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
})
</script>