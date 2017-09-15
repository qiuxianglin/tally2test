<!DOCTYPE HTML>
<html>
<head>
<title>起驳装箱_分驳船单证查询</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule1.css" />
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
						<p style="font-size:22px;color:#000;letter-spacing:2px;">起驳装箱查询统计</p>
						<ul style="margin-left:-32px;">
							<li ><a href="__MODULE__/QbzxSearch/RealTime">实时作业查询</a></li>
							<li ><a href="__MODULE__/QbzxSearch/OperationFinish">完成作业查询</a></li>
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
						船名： <input type="text" class="input1" name="ship_container" id="shipname" autocomplete="off" style="text-transform: uppercase;"/><br/><br/>
						航次： <input type="text" class="input1" name="voyage" value="">&nbsp;&nbsp;&nbsp;&nbsp;
						驳船名称： <select class="input1" name="ship_barge" style="width: 135px">
							<option value="">--默认全部--</option>
							<?php 
							foreach ($shiplist as $sl)
							{
								echo '<option value="'.$sl['ship_name'].'">'.$sl['ship_name'].'</option>';
							}
							?>
						</select>
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
$(function(){
	$("#shipname").bigAutocomplete({
		width:160,
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
			//alert(data.title);	
		}
	});
})
</script>