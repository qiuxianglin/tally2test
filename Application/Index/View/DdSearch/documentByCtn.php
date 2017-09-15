<head>
<title>查询门到门拆箱集装箱单证</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
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
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/Search/index">查询统计</a>&nbsp;&gt;&nbsp;拆箱分箱单证查询
				</div>
			</div>
			<div class="wrapper_o">
				<div style="min-height: 30px;width: 1176px; margin: 0 auto;">
					<div class="searchabc">
						<ul style="margin-left:-32px;">
							<li ><a href="__MODULE__/DdSearch/documentByCtn" style="background:#3398db;color:#fff;">分箱单证查询</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="right_list2">
				<div class="addrule">
					<form class="select" action="" method="get">
					<input type="hidden" name="p" value="1">
					<input type="hidden" name="c" value="DdSearch">
					<input type="hidden" name="a" value="documentByCtn">
						船名： <input type="text" class="input1" name="ship_name" id="shipname" autocomplete="off" style="text-transform: uppercase;"/>&nbsp;&nbsp;&nbsp;&nbsp;
						航次：<input type="text" name="vargo" class="input1"> &nbsp;&nbsp;&nbsp;&nbsp;
						作业地点： <select class="input1" name="location_id" style="width: 135px">
							<option value="">--默认全部--</option>
							<volist name="locationlist" id="l">
							<option value="{$l['id']}">{$l['location_name']}</option>
							</volist>
						</select>&nbsp;&nbsp;&nbsp;&nbsp;
						集装箱号：<input type="text" name="ctn_no" class="input1"><br> 
						整拼： <select class="input1" name="flflag" style="width: 135px">
							<option value="">--默认全部--</option>
							<option value="F">整箱</option>
							<option value="L">拼箱</option>
						</select> &nbsp;&nbsp;&nbsp;&nbsp;
						制单时间： 从 <input type="text" name="begin_time" class="input1 Wdate" onClick="WdatePicker()"
							style="width: 90px"> 到 <input type="text" name="end_time"
							class="input1 Wdate" onClick="WdatePicker()" style="width: 90px">
						&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="查询"
							style="background-color: #3398db !important; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px;">
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
										<th>作业场地</th>
										<th>箱号</th>
										<th>箱型尺寸</th>
										<th>铅封号</th>
										<th>总票数</th>
										<th>总件数</th>
										<th>总重量</th>
										<th>总残损</th>
										<th>制单时间</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
										<td>{$l['ship_name']}</td>
										<td>{$l['vargo']}</td>
										<td>{$l['location_name']}</td>
										<td>{$l['ctn_no']}</td>
										<td>{$l['ctn_type_code']}</td>
										<td>{$l['sealno']}</td>
										<td>{$l['total_ticket']}</td>
										<td>{$l['total_package']}</td>
										<td>{$l['total_weight']}</td>
										<td>{$l['damaged_quantity']}</td>
										<td>{$l['createtime']}</td>
										<td>
										  <a href="__CONTROLLER__/documentByCtnMsg/id/{$l['id']}">查看</a>
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