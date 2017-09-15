<!DOCTYPE HTML>
<html>
<head>
<title>起驳装箱_分驳船单证查询</title>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css"
	type="text/css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
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
					当前位置：<a href="__MODULE__/Search/index">查询统计</a>&nbsp;&gt;&nbsp;分驳船单证查询
				</div>
			</div>
			<div class="wrapper_o">
				<div style="min-height: 30px;width: 1176px; margin: 0 auto;">
					<div class="searchabc">
						<ul style="margin-left:-32px;">
							<li ><a href="__MODULE__/QbzxSearch/ProveByCtn">分箱单证查询</a></li>
							<li ><a href="__MODULE__/QbzxSearch/ProveByTicket">分票单证查询</a></li>
							<li ><a href="__MODULE__/QbzxSearch/ProveByShip" style="background:#3398db;color:#fff;">分驳船单证查询</a></li> 
						</ul>
					</div>
				</div>
			</div>
			<div class="right_list2">
			
				<div class="addrule">
					<form class="select" action="" method="get">
					   <input type="hidden" value="QbzxSearch" name="c">
					   <input type="hidden" value="ProveByShip" name="a">
					   <input type="hidden" value="1" name="p">
						船名： 
						<input type="text" name="ship_container" id='ship_container' class="input1"> 
						航次：<input type="text" class="input1" name="voyage" value="">
						驳船名称：
						<input type="text" name="ship_barge" id='ship_barge' class="input1">
						<input type="submit" value="查询" style="background-color: #3398db !important; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px;">
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
										<th>驳船名称</th>
										<th>箱号</th>
										<th>箱型</th>
										<th>铅封号</th>
										<th>总件数</th>
										<th>总残损数</th>
										<th>完成时间</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
								<?php 
								$ship=new \Common\Model\ShipModel();
								?>
									<volist name="ctnlist" id="cl">
										<?php $ship_content=json_decode($cl['barge_ship_content'],true);?>
										<foreach name="ship_content" item='v'>
											<tr>
												<td>{$cl['ship_name']}</td>
												<td>{$cl['voyage']}</td>
												<td>{$cl['location_name']}</td>
												<td>
												 <?php
												 	$res_c=$ship->getShipMsg($v['ship_id']);
												 	echo $res_c['ship_name'];
												 ?>
												</td>
												<td>{$cl['ctnno']}</td>
												<td>{$cl['ctn_type_code']}</td>
												<td>{$cl['sealno']}</td>
												<?php
												echo '<td>'.$cl['total_package'].'</td>
				                                      <td>'.$cl['damage_num'].'</td>';
												?>
												<td>{$cl['createtime']}</td>
												<td>
												  <a href="__MODULE__/QbzxSearch/ProveByShipMsg/ctn_id/{$cl['ctn_id']}/ship_id/{$v['ship_id']}">查看</a>
												</td>
											</tr>
										</foreach>
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
	$("#ship_container").bigAutocomplete({
		width:135,
		data:[
			<?php
			foreach ($shiplist2 as $s)
			{
				echo '{title:"'.$s['ship_code'].'",show:"'.$s['ship_name'].'"},';
				echo '{title:"'.$s['ship_name'].'",show:"'.$s['ship_name'].'"},';
			}
			?>
		],
		callback:function(data){

		}
	});
	$("#ship_barge").bigAutocomplete({
		width:135,
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

		}
	});
</script>