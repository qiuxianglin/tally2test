<head>
<title>CFS装箱-分票单证查询</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule1.css" />
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
<script type="text/javascript">
$(function(){
	$('.right_list2').find('table tbody tr:even').css('background','#fff');	
})
</script>
<style>
.searchabc ul li{list-style:none;float:left;margin-left:30px;}
.searchabc ul li a{text-decoration:none;color:#505050;line-height:60px;font-family:"新宋体";font-size:17px;background:#f0f0f0;border:1px solid #f0f0f0;border-radius:5px;padding:10px 5px;}
.searchabc ul li a:hover{color:#fff;background:#3398db;}
</style>
</head>


	<div id="wapper" class="sywapper">
		<div class="right">

			<div class="wrapper_o">
				<div class="title">
					<span></span>
				</div>
				<div style="min-height: 80px;width: 1176px; margin: 0 auto;">
					<div class="searchabc">
						<p style="font-size:22px;color:#000;letter-spacing:2px;">CFS装箱查询统计</p>
						<ul style="margin-left:-32px;">
							<li ><a href="__MODULE__/CfsSearch/real_time">实时作业查询</a></li>
							<li ><a href="__MODULE__/CfsSearch/complete">完成作业查询</a></li>
							<li ><a href="__MODULE__/CfsSearch/documentByCtn">分箱单证查询</a></li>
							<li ><a href="__MODULE__/CfsSearch/documentByTicket" style="background:#3398db;color:#fff;">分票单证查询</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="right_list2">
				<div class="addrule">
					<form class="select" action="__ACTION__" method="get">
					<input type="hidden" name="p" value="1">
					<input type="hidden" name="c" value="CfsSearch">
					<input type="hidden" name="a" value="documentByTicket">
						船名： <input type="text" class="input1" name="ship_name" id="shipname" autocomplete="off" style="text-transform: uppercase;"/>&nbsp;&nbsp;&nbsp;&nbsp; 
						航&nbsp;&nbsp;&nbsp;次：<input type="text"name="voyage" class="input1"><br><br>
						场地： <select class="input1" name="location_name" style="width: 135px">
							<option value="">--默认全部--</option>
							<volist name="locationlist" id="l">
							<option value="{$l['location_name']}">{$l['location_name']}</option>
							</volist>
						</select>&nbsp;&nbsp;&nbsp;&nbsp;
						提单号：<input type="text" name="blno" class="input1"> &nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" value="查询" style="background-color: #f1691e !important; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px;">
					</form>
				</div>
				<div style="clear: both;"></div>
				<div class="row" style="margin-top: 10px">
					<div class="col-xs-12">
						<div>
							<table width="100%" class="table">
								<thead>
									<tr>
										<th>船名</th>
										<th>航次</th>
										<th>拆箱地点</th>
										<th>提单号</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
										<td>{$l['ship_name']}</td>
										<td>{$l['voyage']}</td>
										<td>{$l['location_name']}</td>
										<td>{$l['blno']}</td>
										<td>
										   <a href="__CONTROLLER__/documentByTicketMsg/blno/{$l['blno']}/instruction_id/{$l['id']}">查看</a>
										</td>
									</tr>
									</volist>
								</tbody>
							</table>
							<div class="pages">{$page}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
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
})
</script>