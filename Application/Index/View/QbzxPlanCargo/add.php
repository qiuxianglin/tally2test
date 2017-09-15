<head>
<title>起驳装箱_预报计划_新增配货</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
<script src="__PUBLIC__/admin/js/jquery-1.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__" method="post">
			<input type="hidden" name="plan_id" value="{$plan_id}">
			<h5 class="hh">新增预报配货</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="36" align="right" valign="middle">提单号：</td>
					<td><input type="text" class="article" name="billno"
						required="required" /></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">目的港：</td>
					<td><input type="text" class="article" name="port_name" id="port"
						required="required" /></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">货名：</td>
					<td><input type="text" class="article" name="cargoname" required="required"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">件数：</td>
					<td><input type="text" class="article" name="number" required="required"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">包装：</td>
					<td><input type="text" class="article" name="package" required="required"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">标志：</td>
					<td><input type="text" class="article" name="mark" required="required"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">重量：</td>
					<td><input type="text" class="article" name="total_weight" /></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">危险等级：</td>
					<td><input type="text" class="article" name="dangerlevel" /></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">驳船：</td>
					<td id="ship_input">
					  <input type="text" class="article" name="ship_id[]" id="shipname" autocomplete="off" style="text-transform: uppercase;"/>
					  <a href="javascript:;" onclick="addship()" id="addship" style="font-size: 18px;font-weight:bold"> + </a>
					</td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">来源场地：</td>
					<td id="location_input">
					  <input type="text" class="article" name="location_name[]" id="location_name" autocomplete="off" style="text-transform: uppercase;"/>
					  <a href="javascript:;" onclick="addlocation()" id="addlocation" style="font-size: 18px;font-weight:bold"> + </a>
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
<script>
$(function(){
	$("#shipname").bigAutocomplete({
		width:160,
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

})

function addship()
{
	var num=$("#ship_input input").length;
	var id='shipname'+num;
	var html='<input type="text" class="article" name="ship_id[]" id="'+id+'" autocomplete="off" style="text-transform: uppercase;"/>&nbsp;&nbsp;&nbsp;';
	$('#addship').before(html);
	$("#"+id).bigAutocomplete({
		width:160,
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
}
function addlocation()
{
	var num=$("#location_input input").length;
	var id='location_name'+num;
	var html='<input type="text" class="article" name="location_name[]" id="'+id+'" autocomplete="off" style="text-transform: uppercase;"/>&nbsp;&nbsp;&nbsp;';
	$('#addlocation').before(html);
	$("#"+id).bigAutocomplete({
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
}

$("#port").bigAutocomplete({
	width:160,
	data:[
		<?php 
		foreach ($portlist as $c)
		{
			echo '{title:"'.$c['code'].'",show:"'.$c['name'].'"},';
			echo '{title:"'.$c['name'].'",show:"'.$c['name'].'"},';
		}
		?>
	],
	callback:function(data){
		//alert(data.title);	
	}
});
</script>