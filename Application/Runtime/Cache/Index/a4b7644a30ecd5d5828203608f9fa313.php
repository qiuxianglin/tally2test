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
<title>CFS装箱_添加指令</title>
<link rel="stylesheet" type="text/css" href="/tally2test/Public/css/page.css" />
<link rel="stylesheet" type="text/css" href="/tally2test/Public/admin/css/rule.css" />
<link rel="stylesheet" type="text/css" href="/tally2test/Public/admin/css/nff.css" />
<script type="text/javascript" src="/tally2test/Public/admin/js/box.js"></script>
<script type="text/javascript" src="/tally2test/Public/admin/js/nff.js"></script>
<script src="/tally2test/Public/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="/tally2test/Public/css/jquery.bigautocomplete.css" type="text/css" />
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
</script>
<style>
#wapper, .right, .right_t {
	width: 1000px
}
</style>
</head>
<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="/tally2test/index.php?s=/CfsInstruction/index">CFS装箱</a>&nbsp;&gt;&nbsp;<a
						href="/tally2test/index.php?s=/CfsInstruction/">指令</a>&nbsp;&gt;&nbsp;指令详情
				</div>
			</div>
			<div class="right_t" style="text-align: center;">
				<form action="/tally2test/index.php?s=/CfsInstruction/edit" method="post">
					<table border="" style="margin: 0 auto;">
						<tr>
							<td height="36" align="right" valign="middle">中文船名：</td>
							<td><input type="text" class="article" name="shipname" value="<?php echo ($msg['ship_name']); ?>" id="ship_name" required="required" autocomplete="off" style="text-transform: uppercase;"
								required="required" /></td>

							<td height="36" align="right" valign="middle">航次：</td>
							<td><input type="text" class="article" name="voyage" value="<?php echo ($msg['voyage']); ?>"
								required="required" /></td>

							<td height="36" align="right" valign="middle">作业地点：</td>
							<td>
							   <input type="text" class="article" name="location_name" value="<?php echo ($msg['location_name']); ?>" id="location_name" required="required" autocomplete="off" style="text-transform: uppercase;" />
							</td>
						</tr>
						<tr>
							<td height="36" align="right" valign="middle">委托单位：</td>
							<td>
							   <input type="text" class="article" name="entrust_company" value="<?php echo ($msg['entrust_company_zh']); ?>" id="entrust_company" autocomplete="off" style="text-transform: uppercase;" />
							</td>
						
							<td height="36" align="right" valign="middle">所属工班组：</td>
							<td>
							   <input type="text" class="article" name="department_id" value="<?php echo ($dMsg['parent_department_name']); ?>-<?php echo ($dMsg['department_name']); ?>" readonly="readonly"/>
							</td>

							<td height="36" align="right" valign="middle">日期：</td>
							<td>
							   <input type="text" class="article" name="date" value="<?php echo ($msg['date']); ?>" readonly="readonly"/>
							</td>
						</tr>
						
						<tr>
							<td height="36" align="right" valign="top">装箱方式：</td>
							<td><select name="operation_type" class="article"
								required="required">
									<option value="0" <?php if($msg['operation_type'] == '0'){echo 'selected';}?>>人工</option>
									<option value="1" <?php if($msg['operation_type'] == '1'){echo 'selected';}?>>机械</option>
							</select></td>
						</tr>

						<tr style="text-align: right;">
							<td colspan="6"><input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
								<input type="hidden" name="id" value="<?php echo ($msg['id']); ?>">
								<input type="reset" class="qr" value="重&nbsp;置" /></td>
						</tr>
					</table>
				</form>
				<div style="clear: both; margin-top: 10px"></div>
				<div class="clear" style="width:900px;"></div>
				<div class="row" style="width: 900px; margin: 0 auto">
					<div class="rowh">
						<span>派工情况</span>
						<?php
 if($msg['status']=='0') { echo '<a style="float: right" id="pg" class="box" href="/tally2test/index.php?s=/CfsDispatch/add/instruction_id/'.$msg['id'].'">新增派工</a>'; } ?>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width: 900px;">
								<thead>
									<tr>
									    <th>指令日期</th>
									    <th>指令状态</th>
										<th>工班号</th>
										<th>作业班组</th>
										<th>理货长</th>
										<th>派工时间</th>
										<th>理货员</th>
										<th>派工操作</th>
									</tr>
								</thead>
								<tbody>
									<tr>
									    <td><?php echo ($msg['date']); ?></td>
									    <td><?php echo ($msg['status_zh']); ?></td>
										<td><?php echo ($dispatch_detail['shift_id']); ?></td>
										<td><?php echo ($msg['department']); ?></td>
										<td><?php echo ($dispatch_detail['chieftally_name']); ?></td>
										<td><?php echo ($dispatch_detail['dispatch_time']); ?></td>
										<td>
										  <?php  if($msg['status']!='0') { foreach ($dispatch_detail['detail'] as $d) { echo $d['user_name'].'&nbsp;&nbsp;'; } } ?>
										</td>
										<td><?php
 if ($msg['status'] == '1') { echo '<a class="box" href="/tally2test/index.php?s=/CfsDispatch/edit/instruction_id/'.$msg['id'].'/dispatch_id/'.$dispatch_detail['id'].'">修改派工</a>&nbsp;|&nbsp;<a href="/tally2test/index.php?s=/CfsDispatch/del/instruction_id/'.$msg['id'].'/dispatch_id/'.$dispatch_detail['id'].'">取消派工</a>'; }else if($msg['status'] == '2'){ echo '该作业已完成'; } ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>				
					</div>
				<div style="clear: both; margin-top: 10px"></div>
				<div class="clear" style="width:900px;"></div>
				<div class="row" style="width: 900px; margin: 0 auto">
					<div class="rowh">
						<span>配货情况</span>
						<a style="float: right" id="pg" class="box" href="/tally2test/index.php?s=/CfsInstructionCargo/add/instruction_id/<?php echo ($id); ?>">新增配货</a>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width:900px;">
								<thead>
									<tr>
										<th>提单号</th>
										<th>目的港</th>
										<th>货名</th>
										<th>件数</th>
										<th>包装</th>
										<th>标志</th>
										<th>总重量</th>
										<th>危险等级</th>
										<th>总体积</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php if(is_array($cargolist)): $i = 0; $__LIST__ = $cargolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><tr>
										<td><?php echo ($c['blno']); ?></td>
										<td>
										<?php  if(!empty($c['port_id'])) { $port = new \Common\Model\PortModel(); $port_id = $c['port_id']; $port_name = $port->getPortMsg($port_id); echo $port_name["name"]; } ?>
										</td>
										<td><?php echo ($c['name']); ?></td>
										<td><?php echo ($c['number']); ?></td>
										<td><?php echo ($c['package']); ?></td>
										<td><?php echo ($c['mark']); ?></td>
										<td><?php echo ($c['totalweight']); ?></td>
										<td><?php echo ($c['dangerlevel']); ?></td>
										<td><?php echo ($c['totalvolume']); ?></td>
										<td>
										   <a class="box" href="/tally2test/index.php?s=/CfsInstructionCargo/edit/id/<?php echo ($c['id']); ?>">查看 | 修改</a>
										   | <a onclick="return confirm('删除是不可恢复的，你确认要删除该配货吗？');" href="/tally2test/index.php?s=/CfsInstructionCargo/del/id/<?php echo ($c['id']); ?>">删除</a>
										</td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div style="clear: both; margin-top: 10px"></div>
				<div class="clear" style="width:900px;"></div>
				<div class="row" style="width: 900px; margin: 0 auto">
					<div class="rowh">
						<span>配箱情况</span>
						<a style="float: right" id="pg" class="box" href="/tally2test/index.php?s=/CfsInstructionContainer/add/instruction_id/<?php echo ($id); ?>">新增配箱</a>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width:900px;">
								<thead>
									<tr>
										<th>序号</th>
										<th>箱号</th>
										<th>箱型尺寸</th>
										<th>箱主</th>
										<th>拼箱状态</th>
										<th>作业状态</th>
										<th>审核状态</th>
										<th>预配件数</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php if(is_array($containerlist)): $i = 0; $__LIST__ = $containerlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cl): $mod = ($i % 2 );++$i;?><tr>
									<?php $n++;?>
									    <td><?php echo ($n); ?></td>
										<td><?php echo ($cl['ctnno']); ?></td>
										<td><?php echo ($cl['ctn_size']); ?></td>
										<td><?php echo ($cl['cmaster']); ?></td>
										<td><?php if($cl['lcl'] == 'F'){ echo $cl['lcl'] = '整箱'; }else{ echo $cl['lcl'] = '拼箱'; }?></td>
										<td><?php if($cl['status'] == 0): ?>未作业<?php elseif($cl['status'] == 1): ?>工作中<?php elseif($cl['status'] == 2): ?>已铅封<?php elseif($cl['status'] == -1): ?>箱残损<?php else: ?>未知<?php endif; ?>
										</td>
										<td>
										<?php
 switch ($cl ['operation_examine']) { case '1' : echo '未审核'; break; case '2' : echo '通过'; break; case '3' : echo '未通过'; break; default: echo '---'; break; } ?>
										</td>
										<td><?php echo ($cl['pre_number']); ?></td>
										<td>
										   <?php if($cl['status'] == 0): ?><a class="box" href="/tally2test/index.php?s=/CfsInstructionContainer/edit/id/<?php echo ($cl['id']); ?>">查看 | 修改 |</a><?php endif; ?>
										    <?php
 if($cl['status']=='0' or $cl['status'] == '-1') { echo ' <a onclick="return confirm(\'删除是不可恢复的，你确认要删除吗？\');" href="/tally2test/index.php?s=/CfsInstructionContainer/del/id/'.$cl['id'].'">删除</a>'; } if($cl['status'] == '1' or $cl['status'] == '2' ){ echo ' <a  href="/tally2test/index.php?s=/CfsOperationContainer/detail/ctn_id/'.$cl['id'].'">查看作业详情</a>'; } ?>
										</td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
								</tbody>
							</table>
							<div class="pages"><?php echo ($page); ?></div>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
</body>
<script>
$(function(){
	$("#location_name").bigAutocomplete({
		width:160,
		data:[
			<?php  foreach ($locationlist as $l) { echo '{title:"'.$l['location_code'].'",show:"'.$l['location_name'].'"},'; echo '{title:"'.$l['location_name'].'",show:"'.$l['location_name'].'"},'; } ?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#ship_name").bigAutocomplete({
		width:160,
		data:[
			<?php  foreach ($shiplist as $s) { echo '{title:"'.$s['ship_code'].'",show:"'.$s['ship_name'].'"},'; echo '{title:"'.$s['ship_name'].'",show:"'.$s['ship_name'].'"},'; } ?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});

	$("#cargo_agent").bigAutocomplete({
		width:160,
		data:[
			<?php  foreach ($cargoAgentList as $cl) { echo '{title:"'.$cl['code'].'",show:"'.$cl['name'].'"},'; echo '{title:"'.$cl['name'].'",show:"'.$cl['name'].'"},'; } ?>
		],
		callback:function(data){
			//alert(data.title);
		}
	});
	$("#entrust_company").bigAutocomplete({
		width:160,
		data:[
			<?php  foreach ($customerlist as $c) { echo '{title:"'.$c['customer_code'].'",show:"'.$c['customer_name'].'"},'; echo '{title:"'.$c['customer_name'].'",show:"'.$c['customer_name'].'"},'; } ?>
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