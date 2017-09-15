<head>
<title>起驳装箱_预报计划_编辑配货</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
<script src="__PUBLIC__/admin/js/jquery-1.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__/id/{$id}" method="post">
			<h5 class="hh">修改预报配货</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="36" align="right" valign="middle">提单号：</td>
					<td>
					   <input type="text" class="article" name="billno" required="required" value="{$msg['billno']}"/>
					</td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">目的港：</td>
					<td>
					<?php 
					  if(!empty($msg['port_id']))
					  {
					  	$port = new \Common\Model\PortModel();
					  	$port_id = $msg['port_id'];
					  	$port_name = $port->getPortMsg($port_id);
					  	echo '<input type="text" class="article" name="port_name" id="port"
						required="required" value="'.$port_name["name"].'"/>';
					  }else{
					  	echo '<input type="text" class="article" name="port_name" id="port"
						required="required" value=""/>';
					  }
					?>
					</td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">货名：</td>
					<td><input type="text" class="article" name="cargoname" required="required"  value="{$msg['cargo_name']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">件数：</td>
					<td><input type="text" class="article" name="number" required="required" value="{$msg['number']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">包装：</td>
					<td><input type="text" class="article" name="package" required="required" value="{$msg['pack']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">标志：</td>
					<td><input type="text" class="article" name="mark" required="required" value="{$msg['mark']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">重量：</td>
					<td><input type="text" class="article" name="total_weight" value="{$msg['total_weight']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">危险等级：</td>
					<td><input type="text" class="article" name="dangerlevel" value="{$msg['dangerlevel']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">驳船：</td>
					<td id="ship_input">
					  <?php 
					  if(!empty($msg['ship_id']))
					  {
					  	$ship_arr=explode(',', $msg['ship_id']);
					  	$shipModel=new \Common\Model\ShipModel();
					  	foreach ($ship_arr as $k=>$v)
					  	{
					  		$res_s=$shipModel->getShipMsg($v);
					  		if($res_s)
					  		{
					  			echo '<input type="text" class="article" name="ship_id[]" id="shipname" autocomplete="off" style="text-transform: uppercase;" value="'.$res_s['ship_name'].'"/>';
					  		}
					  	}
					  }else {
					  	echo '<input type="text" class="article" name="ship_id[]" id="shipname" autocomplete="off" style="text-transform: uppercase;" value=""/>';
					  }
					  ?>
					  <a href="javascript:;" onclick="addship()" id="addship" style="font-size: 18px;font-weight:bold"> + </a>
					</td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">来源场地：</td>
					<td id="location_input">
					  <?php 
					  if(!empty($msg['location_id']))
					  {
					  	$location_arr=explode(',', $msg['location_id']);
					  	$location=new \Common\Model\LocationModel();
					  	foreach ($location_arr as $k=>$v)
					  	{
					  		$res_l=$location->getLocationMsg($v);
					  		if($res_l)
					  		{
					  			echo '<input type="text" class="article" name="location_name[]" id="location_name" autocomplete="off" style="text-transform: uppercase;" value="'.$res_l['location_name'].'"/>';
					  		}
					  	}
					  }else {
					  	echo '<input type="text" class="article" name="location_name[]" id="location_name" autocomplete="off" style="text-transform: uppercase;" value=""/>';
					  }
					  ?>
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