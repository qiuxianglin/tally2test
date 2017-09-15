
<head>
<title>船期列表</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript">
$(function(){
	$('.right_list2').find('table tbody tr:even').css('background','#fff');	
})
</script>
</head>

<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/ShipSchedule/index">船期维护</a>&nbsp;&gt;&nbsp;船期列表
				</div>
			</div>
			<div class="right_list2">
				<a id="add" href="__MODULE__/ShipSchedule/add" style="float: right; margin: -4px 10px 6px;width:55px">新增船期 </a>
				<div style="clear: both"></div>
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table">
								<thead>
									<tr>
										<th>船名</th>
										<th>航次</th>
										<th>开航日期</th>
										<th>到港日期</th>
										<th>起运港</th>
										<th>目的港</th>
										<th>创建日期</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
								    <?php 
									  $Ship=new \Common\Model\ShipModel();
									  $Port=new \Common\Model\PortModel();
									  
									?>
									<volist name="list" id="l">
									<tr>
									<?php 
									$res_s=$ship->getShipMsg($l['ship_id']);
									$shipname=$res_s['ship_name'];
									if($l['loading_port'])
									{
										$res_p=$Port->getPortMsg($l['loading_port']);
										if($res_p!==false)
										{
											$loading_port_name=$res_p['name'];
										}
									}else {
										$loading_port_name='';
									}
									if($l['destination_port'])
									{
										$res_p=$Port->getPortMsg($l['destination_port']);
										if($res_p!==false)
										{
											$destination_port_name=$res_p['name'];
										}
									}else {
										$destination_port_name='';
									}
									?>
										<td>{$shipname}</td>
										<td>{$l['voyage']}</td>
										<td>{$l['sailing_date']}</td>
										<td>{$l['arrival_date']}</td>
										<td>{$loading_port_name}</td>
										<td>{$destination_port_name}</td>
										<td>{$l['createtime']}</td>
										<td>
										   <a href="__CONTROLLER__/edit/id/{$l['id']}">查看/修改</a>
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
</body>