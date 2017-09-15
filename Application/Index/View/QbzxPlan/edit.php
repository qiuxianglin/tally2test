<head>
<title>起驳装箱_预报计划详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/admin/css/rule.css" />
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/admin/css/nff.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css"
	type="text/css" />
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
</script>
<style>
#wapper, .right, .right_t {
	width: 1000px
}

.right_t table td .article {
	width: 150px
}
</style>
</head>
<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/QbzxPlan/index">起驳装箱</a>&nbsp;&gt;&nbsp;<a
						href="__MODULE__/QbzxPlan/index">预报计划</a>&nbsp;&gt;&nbsp;预报计划详情
				</div>
			</div>

			<div class="right_t" style="text-align: center;">
				<form action="__MODULE__/QbzxPlan/edit" method="post">
					<input type="hidden" name="plan_id" value="{$plan_id}">
					<table style="margin: 0 auto;">
						<tr>
							<td height="36" align="right" valign="middle">委托编号：</td>
							<td><input type="text" class="article" name="entrustno"
								required="required" value="{$msg['entrustno']}" /></td>

							<td height="36" align="right" valign="middle">委托单位：</td>
							<td><input type="text" class="article" name="entrust_company"
								id="entrust_company" required="required" autocomplete="off"
								style="text-transform: uppercase;" value="{$msg['customer']}" />
							</td>

							<td height="36" align="right" valign="middle">理货地点：</td>
							<td><input type="text" class="article" name="location_name"
								id="location_name" required="required" autocomplete="off"
								style="text-transform: uppercase;"
								value="{$msg['location_name']}" /></td>
						</tr>
						<tr>
							<td height="46" align="right" valign="middle">船舶名称：</td>
							<td><input type="text" class="article" name="shipname"
								id="shipname" required="required" autocomplete="off"
								style="text-transform: uppercase;" value="{$msg['ship_name']}" />
							</td>

							<td height="36" align="right" valign="middle">航次：</td>
							<td><input type="text" class="article" name="voyage"
								required="required" value="{$msg['voyage']}" /></td>

							<td height="36" align="right" valign="middle">总箱数：</td>
							<td><input type="text" class="article" name="total_ctn"
								value="{$msg['total_ctn']}" /></td>
						</tr>
						<tr>
							<td height="36" align="right" valign="middle">总票数：</td>
							<td><input type="text" class="article" name="total_ticket"
								required="required" value="{$msg['total_ticket']}" /></td>

							<td height="36" align="right" valign="middle">总件数：</td>
							<td><input type="text" class="article" name="total_package"
								value="{$msg['total_package']}" /></td>

							<td height="36" align="right" valign="middle">总重量：</td>
							<td><input type="text" class="article" name="total_weight"
								value="{$msg['total_weight']}" /></td>
						</tr>
						<tr>
							<td height="36" align="right" valign="middle">货代：</td>
							<td><input type="text" class="article" name="cargo_agent"
								id="cargo_agent" required="required" autocomplete="off"
								style="text-transform: uppercase;"
								value="{$msg['cargo_agent_name']}" /></td>
						</tr>
						<tr>
							<td height="36" align="right" valign="top">装箱要求：</td>
							<td colspan="5"><textarea rows="" cols="" name="packing_require"
									class="article" style="width: 650px; height: 300px">{$msg['packing_require']}</textarea>
							</td>
						</tr>

						<tr style="text-align: right;">
							<td colspan="6"><input type="submit" class="qr" value="修&nbsp;改" />&nbsp;&nbsp;&nbsp;</td>
						</tr>

					</table>
				</form>

				<div style="clear: both; margin-top: 10px"></div>

				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;">
								<thead>
									<tr>
										<th>提单号</th>
										<th>目的港</th>
										<th>货名</th>
										<th>件数</th>
										<th>包装</th>
										<th>标志</th>
										<th>重量</th>
										<th>危险等级</th>
										<th>驳船</th>
										<th>来源场地</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="cargolist" id="c">
									<tr>
										<td>{$c['billno']}</td>
										<td>
											<?php 
											  if(!empty($c['port_id']))
											  {
											  	$port = new \Common\Model\PortModel();
											  	$port_id = $c['port_id'];
											  	$port_name = $port->getPortMsg($port_id);
											  	echo $port_name["name"];
											  }
											?>
										</td>
										<td>{$c['cargo_name']}</td>
										<td>{$c['number']}</td>
										<td>{$c['pack']}</td>
										<td>{$c['mark']}</td>
										<td>{$c['total_weight']}</td>
										<td>{$c['dangerlevel']}</td>
										<td>
										  <?php
												if (! empty ( $c ['ship_id'] )) {
													$ship_arr = explode ( ',', $c ['ship_id'] );
													$shipModel = new \Common\Model\ShipModel ();
													foreach ( $ship_arr as $k => $v ) {
														$res_s = $shipModel->getShipMsg ( $v );
														if ($res_s) {
															$shipstr .= $res_s ['ship_name'] . '、';
														}
													}
													echo substr ( $shipstr, 0, - 3 );
													$shipstr = '';
												}
												?>
										</td>
										<td>
										 <?php
											if (! empty ( $c ['location_id'] )) {
												$location_arr = explode ( ',', $c ['location_id'] );
												$location = new \Common\Model\LocationModel ();
												foreach ( $location_arr as $k => $v ) {
													$res_l = $location->getLocationMsg ( $v );
													if ($res_l) {
														$locationstr .= $res_l ['location_name'] . '、';
													}
												}
												echo substr ( $locationstr, 0, - 3 );
												$locationstr = '';
											}
											?>
										</td>
										<td><a class="box"
											href="__MODULE__/QbzxPlanCargo/edit/id/{$c['id']}">修改</a> | <a
											onclick="return confirm('删除是不可恢复的，你确认要删除该配货吗？');"
											href="__MODULE__/QbzxPlanCargo/del/id/{$c['id']}/plan_id/{$c['plan_id']}">删除</a>
										</td>
									</tr>
									</volist>
									<tr>
										<td colspan="11"><a class="box"
											href="__MODULE__/QbzxPlanCargo/add/plan_id/{$msg['id']}"
											id="add">新增配货</a></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div style="clear: both; margin-top: 10px"></div>
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;">
								<thead>
									<tr>
										<th>箱型尺寸</th>
										<th>箱子个数</th>
										<th>箱主</th>
										<th>整拼标志</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="containerlist" id="cl">
									<tr>
										<td>{$cl['ctn_type_code']}</td>
										<td>{$cl['quantity']}</td>
										<td>{$cl['ctn_master']}</td>
										<td><if condition="$cl['flflag'] eq L">拼箱 <else />整箱</if></td>
										<td><a class="box"
											href="__MODULE__/QbzxPlanCtn/edit/id/{$cl['id']}">查看 | 修改</a>
											| <a onclick="return confirm('删除是不可恢复的，你确认要删除该配箱吗？');"
											href="__MODULE__/QbzxPlanCtn/del/id/{$cl['id']}">删除</a></td>
									</tr>
									</volist>
									<tr>
										<td colspan="6"><a class="box"
											href="__MODULE__/QbzxPlanCtn/add/plan_id/{$msg['id']}"
											id="add">新增配箱</a></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div style="clear: both; margin-bottom: 10px"></div>
			</div>
		</div>
	</div>
</body>
<script>
$(function(){
	$("#location_name").bigAutocomplete({
		width:160,
		data:[
				<?php
				foreach ( $locationlist as $l ) {
					echo '{title:"' . $l ['location_code'] . '",show:"' . $l ['location_name'] . '"},';
					echo '{title:"' . $l ['location_name'] . '",show:"' . $l ['location_name'] . '"},';
				}
				?>
			],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#entrust_company").bigAutocomplete({
		width:160,
		data:[
				<?php
				foreach ( $customerlist as $c ) {
					echo '{title:"' . $c ['customer_code'] . '",show:"' . $c ['customer_name'] . '"},';
					echo '{title:"' . $c ['customer_shortname'] . '",show:"' . $c ['customer_name'] . '"},';
				}
				?>
			],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#shipname").bigAutocomplete({
		width:160,
		data:[
			<?php
			foreach ( $shiplist as $s ) {
				echo '{title:"' . $s ['ship_code'] . '",show:"' . $s ['ship_name'] . '"},';
				echo '{title:"' . $s ['ship_name'] . '",show:"' . $s ['ship_name'] . '"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#cargo_agent").bigAutocomplete({
		width:160,
		data:[
			<?php
			foreach ( $cargoAgentList as $cl ) {
				echo '{title:"' . $cl ['code'] . '",show:"' . $cl ['name'] . '"},';
				echo '{title:"' . $cl ['name'] . '",show:"' . $cl ['name'] . '"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
})
</script>