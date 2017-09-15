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
			<h5 class="hh">修改指令配货</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="36" align="right" valign="middle">提单号：</td>
					<td>
					   <input type="text" class="article" name="blno" required="required" value="{$msg['blno']}"/>
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
					<td height="36" align="right" valign="middle">运输编号：</td>
					<td><input type="text" class="article" name="crgno" value="{$msg['crgno']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">货名：</td>
					<td><input type="text" class="article" name="name" required="required" value="{$msg['name']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">件数：</td>
					<td><input type="text" class="article" name="number" required="required" value="{$msg['number']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">包装：</td>
					<td><input type="text" class="article" name="package" required="required" value="{$msg['package']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">标志：</td>
					<td><input type="text" class="article" name="mark" value="{$msg['mark']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">重量：</td>
					<td><input type="text" class="article" name="totalweight" value="{$msg['totalweight']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">危险等级：</td>
					<td><input type="text" class="article" name="dangerlevel" value="{$msg['dangerlevel']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">总体积：</td>
					<td><input type="text" class="article" name="totalvolume" value="{$msg['totalvolume']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">po号：</td>
					<td><input type="text" class="article" name="po" value="{$msg['po']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">备注：</td>
					<td>
					  <textarea style="width:250px;height:50px" class="article" name="remark">{$msg['remark']}</textarea>					
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
				echo '{title:"'.$sl['shipcode'].'",show:"'.$sl['shipname'].'"},';
				echo '{title:"'.$sl['shipname'].'",show:"'.$sl['shipname'].'"},';
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
				echo '{title:"'.$l['locationcode'].'",show:"'.$l['locationname'].'"},';
				echo '{title:"'.$l['locationname'].'",show:"'.$l['locationname'].'"},';
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
				echo '{title:"'.$sl['shipcode'].'",show:"'.$sl['shipname'].'"},';
				echo '{title:"'.$sl['shipname'].'",show:"'.$sl['shipname'].'"},';
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
				echo '{title:"'.$l['locationcode'].'",show:"'.$l['locationname'].'"},';
				echo '{title:"'.$l['locationname'].'",show:"'.$l['locationname'].'"},';
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
