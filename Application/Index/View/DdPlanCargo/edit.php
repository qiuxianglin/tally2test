<head>
<title>拆箱系统_预报计划_编辑配货</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
<script src="__PUBLIC__/admin/js/jquery-1.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__" method="post">
			<input type="hidden" name="id" value="{$msg['id']}">
			<h5 class="hh">修改预报配货</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="36" align="right" valign="middle">提单号：</td>
					<td><input type="text" class="article" style="width:150px" name="billno"
						required="required" value="{$msg['blno']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">付费方名称：</td>
					<td><input type="text" class="article" style="width:150px" name='payman' id="payman"
						required="required" value="{$msg['payman']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">货名：</td>
					<td><input type="text" class="article" style="width:150px" name="cargoname" required="required" value="{$msg['cargoname']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">件数：</td>
					<td><input type="text" class="article" style="width:150px" name="number" required="required" value="{$msg['numbersofpackages']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">包装：</td>
					<td><input type="text" class="article" style="width:150px" name="package" required="required" value="{$msg['package']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">标志：</td>
					<td><input type="text" class="article" style="width:150px" name="mark" required="required" value="{$msg['mark']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">收货人：</td>
					<td><input type="text" class="article" style="width:150px" name="consignee" required="required" value="{$msg['consignee']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">联系人：</td>
					<td><input type="text" class="article" style="width:150px" name="contactuser" required="required" value="{$msg['contactuser']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">联系方式：</td>
					<td><input type="text" class="article" style="width:150px" name="contact" required="required" value="{$msg['contact']}"/></td>
				</tr> 
				<tr>
					<td height="36" align="right" valign="middle">联合国编号：</td>
					<td><input type="text" class="article" style="width:150px" name="undgno" required="required" value="{$msg['undgno']}"/></td>
				</tr>
				<tr>
					<td height="36" align="right" valign="middle">危险品等级：</td>
					<td><input type="text" class="article" style="width:150px" name="classes" required="required" value="{$msg['classes']}"/></td>
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

$("#payman").bigAutocomplete({
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
</script>