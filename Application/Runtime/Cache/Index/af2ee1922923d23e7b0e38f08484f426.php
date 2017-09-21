<?php if (!defined('THINK_PATH')) exit(); require './Public/inc/status.config.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="/tally2test/Public/admin/css/pages.css">
	<script src="/tally2test/Public/admin/js/jquery-1.js"></script>
	<script src="/tally2test/Public/js/jquery.SuperSlide.2.1.1.js"></script>
	<script src="/tally2test/Public/admin/js/base.js"></script>
	<script>
$(document).ready(function(){
	var innerHeight=window.innerHeight;
	var min_height=innerHeight-366;
	$("#wapper").css("min-height", min_height+'px');
});
</script>

</head>
<body>
	<link rel="stylesheet" type="text/css" href="/tally2test/Public/admin/css/nff.css" />
<script type="text/javascript" src="/tally2test/Public/admin/js/box.js"></script>
<script type="text/javascript" src="/tally2test/Public/admin/js/nff.js"></script>
<script type="text/javascript" src="/tally2test/Public/js/jquery.firstebox.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/tally2test/Public/css/firstebox.css" />
<?php
switch (CONTROLLER_NAME) { case 'QbzxPlan' : $hf2 = 'on'; break; case 'QbzxPlanCtn' : $hf2 = 'on'; break; case 'QbzxPlanCargo' : $hf2 = 'on'; break; case 'QbzxInstruction' : $hf2 = 'on'; break; case 'QbzxInstructionCtn' : $hf2 = 'on'; break; case 'QbzxOperation' : $hf2 = 'on'; break; case 'DdPlan' : $hf3 = 'on'; break; case 'DdInstruction' : $hf3 = 'on'; break; case 'DdOperation' : $hf3 = 'on'; break; case 'CfsInstruction' : $hf4 = 'on'; break; case 'CfsInstructionCargo' : $hf4 = 'on'; break; case 'CfsInstructionContainer' : $hf4 = 'on'; break; case 'Search' : $hf5 = 'on'; break; case 'QbzxSearch' : $hf5 = 'on'; break; case 'DdSearch' : $hf5 = 'on'; break; case 'CfsSearch' : $hf5 = 'on'; break; case 'QbzxSearch' : $hf5 = 'on'; break; case 'ShipSchedule' : $hf6 = 'on'; break; default : $hf1 = 'on'; break; } switch (CONTROLLER_NAME.'/'.ACTION_NAME) { case 'Index/personal' : $hf7 = 'on'; break; } ?>

<div class="top_w">
	<div class="top_bj">
		<img src="/tally2test/Public/img/zjmls_01.png" alt="" />
	</div>
</div>

<div class="nav_w">
	<div class="navBar">
		<ul class="nav clearfix">
			<li class="m">
				<h3>
					<a href="/tally2test/index.php?s=/Index/index">首页</a>
				</h3>
			</li>
			<li class="m <?php echo $hf2;?>">
				<h3>
					<a href="/tally2test/index.php?s=/QbzxPlan/index">起驳装箱</a>
				</h3>
				<ul class="sub">
					<li><a href="/tally2test/index.php?s=/QbzxPlan/index">查看预报</a></li>
					<li><a href="/tally2test/index.php?s=/QbzxPlan/add">新增预报</a></li>
					<li><a href="/tally2test/index.php?s=/QbzxInstruction/index">作业指令</a></li>
				</ul>
			</li>
			<li class="m <?php echo $hf3;?>">
				<h3>
					<a href="/tally2test/index.php?s=/DdInstruction/index">拆箱系统</a>
				</h3>
				<ul class="sub">
					<li><a href="/tally2test/index.php?s=/DdPlan/index">查看预报</a></li>
					<li><a href="/tally2test/index.php?s=/DdPlan/add">新增预报</a></li>
					<li><a href="/tally2test/index.php?s=/DdInstruction/index">作业指令</a></li>
				</ul>
			</li>
			<li class="m <?php echo $hf4;?>">
				<h3>
					<a href="/tally2test/index.php?s=/CfsInstruction/index">CFS装箱</a>
				</h3>
				<ul class="sub">
					<li><a href="/tally2test/index.php?s=/CfsInstruction/index">查看指令</a></li>
					<li><a href="/tally2test/index.php?s=/CfsInstruction/add">新增指令</a></li>
				</ul>
			</li>
			<li class="m <?php echo $hf5;?>">
				<h3>
					<a href="/tally2test/index.php?s=/Search/index">查询统计</a>
				</h3>
			</li>
			<li class="m <?php echo $hf6;?>">
				<h3>
					<a href="#" onclick="window.location.reload();">工班作业</a>
				</h3>
				<ul class="sub">
					<li><a href="/tally2test/index.php?s=/Work/signin" class="box">工班签到</a></li>
			<?php
 if ($_SESSION ['u_group_id'] == 12 or $_SESSION ['u_group_id'] == 13) { echo '<li><a href="/tally2test/index.php?s=/Work/succeed" class="box">接班开工</a></li>
					<li><a href="/tally2test/index.php?s=/Work/transfer" class="box">收工交班</a></li>'; } if ($_SESSION ['u_group_id'] == 13) { echo '<li><a href="/tally2test/index.php?s=/Work/resume">工班恢复</a></li>
		              <li><a href="/tally2test/index.php?s=/Work/replaceMaster">替换当班理货长</a></li>'; } ?>
			        <li><a href="/tally2test/index.php?s=/ShipSchedule/index">船期维护</a></li>
				</ul>
			</li>
			<li class="m <?php echo $hf7;?>">
				<h3>
					<a href="#" onclick="window.location.reload();">用户中心</a>
				</h3>
				<ul class="sub">
				    <!-- <li><a href="/tally2test/index.php?s=/User/login">用户登录</a></li> -->
					<li><a href="/tally2test/index.php?s=/User/personal">个人信息</a></li>
					<li><a href="/tally2test/index.php?s=/User/changepwd" class="box">修改密码</a></li>
					<li><a href="/tally2test/Public/img/xiaza.png" class="firstebox">下载客户端</a></li>
					<li><a href="/tally2test/index.php?s=/User/loginout">退出登录</a></li>
					<li><a href="/tally2test/index.php?s=/Test/invoice">模拟发送门到门预报</a></li>
					<li><a href="/tally2test/index.php?s=/Test/payment">模拟发送支付回执</a></li>
				</ul>
			</li>
		</ul>
	</div>
	<script type="text/javascript">
		jQuery(".nav").slide({ 
				type:"menu", //效果类型
				titCell:".m", // 鼠标触发对象
				targetCell:".sub", // 效果对象，必须被titCell包含
				delayTime:300, // 效果时间
				triggerTime:0, //鼠标延迟触发时间
				returnDefault:true  //返回默认状态
			});
	</script>
</div>
	<head>
<title>起驳装箱_预报计划详情</title>
<link rel="stylesheet" type="text/css" href="/tally2test/Public/css/page.css" />
<link rel="stylesheet" type="text/css"
	href="/tally2test/Public/admin/css/rule.css" />
<link rel="stylesheet" type="text/css"
	href="/tally2test/Public/admin/css/nff.css" />
<script type="text/javascript" src="/tally2test/Public/admin/js/box.js"></script>
<script type="text/javascript" src="/tally2test/Public/admin/js/nff.js"></script>
<script src="/tally2test/Public/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="/tally2test/Public/css/jquery.bigautocomplete.css"
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
					当前位置：<a href="/tally2test/index.php?s=/QbzxPlan/index">起驳装箱</a>&nbsp;&gt;&nbsp;<a
						href="/tally2test/index.php?s=/QbzxPlan/index">预报计划</a>&nbsp;&gt;&nbsp;预报计划详情
				</div>
			</div>

			<div class="right_t" style="text-align: center;">
				<form action="/tally2test/index.php?s=/QbzxPlan/edit" method="post">
					<input type="hidden" name="plan_id" value="<?php echo ($plan_id); ?>">
					<table style="margin: 0 auto;">
						<tr>
							<td height="36" align="right" valign="middle">委托编号：</td>
							<td><input type="text" class="article" name="entrustno"
								required="required" value="<?php echo ($msg['entrustno']); ?>" /></td>

							<td height="36" align="right" valign="middle">委托单位：</td>
							<td><input type="text" class="article" name="entrust_company"
								id="entrust_company" required="required" autocomplete="off"
								style="text-transform: uppercase;" value="<?php echo ($msg['customer']); ?>" />
							</td>

							<td height="36" align="right" valign="middle">理货地点：</td>
							<td><input type="text" class="article" name="location_name"
								id="location_name" required="required" autocomplete="off"
								style="text-transform: uppercase;"
								value="<?php echo ($msg['location_name']); ?>" /></td>
						</tr>
						<tr>
							<td height="46" align="right" valign="middle">船舶名称：</td>
							<td><input type="text" class="article" name="shipname"
								id="shipname" required="required" autocomplete="off"
								style="text-transform: uppercase;" value="<?php echo ($msg['ship_name']); ?>" />
							</td>

							<td height="36" align="right" valign="middle">航次：</td>
							<td><input type="text" class="article" name="voyage"
								required="required" value="<?php echo ($msg['voyage']); ?>" /></td>

							<td height="36" align="right" valign="middle">总箱数：</td>
							<td><input type="text" class="article" name="total_ctn"
								value="<?php echo ($msg['total_ctn']); ?>" /></td>
						</tr>
						<tr>
							<td height="36" align="right" valign="middle">总票数：</td>
							<td><input type="text" class="article" name="total_ticket"
								required="required" value="<?php echo ($msg['total_ticket']); ?>" /></td>

							<td height="36" align="right" valign="middle">总件数：</td>
							<td><input type="text" class="article" name="total_package"
								value="<?php echo ($msg['total_package']); ?>" /></td>

							<td height="36" align="right" valign="middle">总重量：</td>
							<td><input type="text" class="article" name="total_weight"
								value="<?php echo ($msg['total_weight']); ?>" /></td>
						</tr>
						<tr>
							<td height="36" align="right" valign="middle">货代：</td>
							<td><input type="text" class="article" name="cargo_agent"
								id="cargo_agent" required="required" autocomplete="off"
								style="text-transform: uppercase;"
								value="<?php echo ($msg['cargo_agent_name']); ?>" /></td>
						</tr>
						<tr>
							<td height="36" align="right" valign="top">装箱要求：</td>
							<td colspan="5"><textarea rows="" cols="" name="packing_require"
									class="article" style="width: 650px; height: 300px"><?php echo ($msg['packing_require']); ?></textarea>
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
									<?php if(is_array($cargolist)): $i = 0; $__LIST__ = $cargolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><tr>
										<td><?php echo ($c['billno']); ?></td>
										<td>
											<?php  if(!empty($c['port_id'])) { $port = new \Common\Model\PortModel(); $port_id = $c['port_id']; $port_name = $port->getPortMsg($port_id); echo $port_name["name"]; } ?>
										</td>
										<td><?php echo ($c['cargo_name']); ?></td>
										<td><?php echo ($c['number']); ?></td>
										<td><?php echo ($c['pack']); ?></td>
										<td><?php echo ($c['mark']); ?></td>
										<td><?php echo ($c['total_weight']); ?></td>
										<td><?php echo ($c['dangerlevel']); ?></td>
										<td>
										  <?php
 if (! empty ( $c ['ship_id'] )) { $ship_arr = explode ( ',', $c ['ship_id'] ); $shipModel = new \Common\Model\ShipModel (); foreach ( $ship_arr as $k => $v ) { $res_s = $shipModel->getShipMsg ( $v ); if ($res_s) { $shipstr .= $res_s ['ship_name'] . '、'; } } echo substr ( $shipstr, 0, - 3 ); $shipstr = ''; } ?>
										</td>
										<td>
										 <?php
 if (! empty ( $c ['location_id'] )) { $location_arr = explode ( ',', $c ['location_id'] ); $location = new \Common\Model\LocationModel (); foreach ( $location_arr as $k => $v ) { $res_l = $location->getLocationMsg ( $v ); if ($res_l) { $locationstr .= $res_l ['location_name'] . '、'; } } echo substr ( $locationstr, 0, - 3 ); $locationstr = ''; } ?>
										</td>
										<td><a class="box"
											href="/tally2test/index.php?s=/QbzxPlanCargo/edit/id/<?php echo ($c['id']); ?>">修改</a> | <a
											onclick="return confirm('删除是不可恢复的，你确认要删除该配货吗？');"
											href="/tally2test/index.php?s=/QbzxPlanCargo/del/id/<?php echo ($c['id']); ?>/plan_id/<?php echo ($c['plan_id']); ?>">删除</a>
										</td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
									<tr>
										<td colspan="11"><a class="box"
											href="/tally2test/index.php?s=/QbzxPlanCargo/add/plan_id/<?php echo ($msg['id']); ?>"
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
									<?php if(is_array($containerlist)): $i = 0; $__LIST__ = $containerlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cl): $mod = ($i % 2 );++$i;?><tr>
										<td><?php echo ($cl['ctn_type_code']); ?></td>
										<td><?php echo ($cl['quantity']); ?></td>
										<td><?php echo ($cl['ctn_master']); ?></td>
										<td><?php if($cl['flflag'] == L): ?>拼箱 <?php else: ?>整箱<?php endif; ?></td>
										<td><a class="box"
											href="/tally2test/index.php?s=/QbzxPlanCtn/edit/id/<?php echo ($cl['id']); ?>">查看 | 修改</a>
											| <a onclick="return confirm('删除是不可恢复的，你确认要删除该配箱吗？');"
											href="/tally2test/index.php?s=/QbzxPlanCtn/del/id/<?php echo ($cl['id']); ?>">删除</a></td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
									<tr>
										<td colspan="6"><a class="box"
											href="/tally2test/index.php?s=/QbzxPlanCtn/add/plan_id/<?php echo ($msg['id']); ?>"
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
 foreach ( $locationlist as $l ) { echo '{title:"' . $l ['location_code'] . '",show:"' . $l ['location_name'] . '"},'; echo '{title:"' . $l ['location_name'] . '",show:"' . $l ['location_name'] . '"},'; } ?>
			],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#entrust_company").bigAutocomplete({
		width:160,
		data:[
				<?php
 foreach ( $customerlist as $c ) { echo '{title:"' . $c ['customer_code'] . '",show:"' . $c ['customer_name'] . '"},'; echo '{title:"' . $c ['customer_shortname'] . '",show:"' . $c ['customer_name'] . '"},'; } ?>
			],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#shipname").bigAutocomplete({
		width:160,
		data:[
			<?php
 foreach ( $shiplist as $s ) { echo '{title:"' . $s ['ship_code'] . '",show:"' . $s ['ship_name'] . '"},'; echo '{title:"' . $s ['ship_name'] . '",show:"' . $s ['ship_name'] . '"},'; } ?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#cargo_agent").bigAutocomplete({
		width:160,
		data:[
			<?php
 foreach ( $cargoAgentList as $cl ) { echo '{title:"' . $cl ['code'] . '",show:"' . $cl ['name'] . '"},'; echo '{title:"' . $cl ['name'] . '",show:"' . $cl ['name'] . '"},'; } ?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
})
</script>
	<div class="foot_w">
    <div class="foot2">
      <p>版权所有  南京中理外轮理货有限公司   苏ICP备10220284号-1</p>
    </div>
</div>
</body>