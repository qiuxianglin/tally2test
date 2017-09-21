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
<title>门到门拆箱_指令详情</title>
<link rel="stylesheet" type="text/css" href="/tally2test/Public/css/page.css" />
<link rel="stylesheet" type="text/css" href="/tally2test/Public/admin/css/rule.css" />
<link rel="stylesheet" type="text/css" href="/tally2test/Public/admin/css/nff.css" />
<script src="/tally2test/Public/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="/tally2test/Public/css/jquery.bigautocomplete.css" type="text/css" />
<script type="text/javascript" src="/tally2test/Public/admin/js/box.js"></script>
<script type="text/javascript" src="/tally2test/Public/admin/js/nff.js"></script>
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
</script>
<style>
#wapper,.right,.right_t {
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
					当前位置：<a href="/tally2test/index.php?s=/DdInstruction/index">门到门拆箱</a>&nbsp;&gt;&nbsp;指令详情
				</div>
			</div>
			
			<div class="right_t" style="text-align: center;">
			
			<div class="row" style="width: 900px; margin: 0 auto">
					<div class="rowh">
						<span>派工情况</span>
						<?php
 if($instruction_msg['status']=='0') { echo '<a style="float: right" id="pg" class="box" href="/tally2test/index.php?s=/DdDispatch/add/instruction_id/'.$instruction_msg['id'].'">新增派工</a>'; } ?>
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
									    <td><?php echo ($instruction_msg['date']); ?></td>
									    <td><?php echo ($instruction_status_d[$instruction_msg['status']]); ?></td>
										<td><?php echo ($dispatch_detail['shift_id']); ?></td>
										<td><?php echo ($instruction_msg['parent_department_name']); ?>-<?php echo ($instruction_msg['department_name']); ?></td>
										<td><?php echo ($dispatch_detail['chieftally_name']); ?></td>
										<td><?php echo ($dispatch_detail['dispatch_time']); ?></td>
										<td>
										  <?php  if($instruction_msg['status']!='0') { foreach ($dispatch_detail['detail'] as $d) { echo $d['user_name'].'&nbsp;&nbsp;'; } } ?>
										</td>
										<td><?php
 if ($instruction_msg['status']!='0' and $instruction_msg['status']!='2') { echo '<a class="box" href="/tally2test/index.php?s=/DdDispatch/edit/instruction_id/'.$instruction_msg['id'].'/dispatch_id/'.$dispatch_detail['id'].'">修改派工</a>&nbsp;|&nbsp;<a href="/tally2test/index.php?s=/DdDispatch/cancel/instruction_id/'.$instruction_msg['id'].'/dispatch_id/'.$dispatch_detail['id'].'">取消派工</a>'; } ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
					<form action="/tally2test/index.php?s=/DdInstruction/view" method="post">
					<input type="hidden" name="plan_id" value="<?php echo ($plan_id); ?>"/>
					<table style="margin: 0 auto; width: 900px">
						<tr>
							<td height="36" width="110" align="right" valign="middle">委托编号：</td>
							<td>
								<input type="text" class="article" value="<?php echo ($msg['orderid']); ?>" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">委托日期：</td>
							<td>
								<input type="text" class="article" value="<?php echo ($msg['orderdate']); ?>" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">中文船名：</td>
							<td>
								<input type="text" class="article" value="<?php echo ($msg['vslname']); ?>" readonly>
							</td>
						</tr>

						<tr>
							<td height="36" width="110" align="right" valign="middle">航次：</td>
							<td>
								<input type="text" class="article" value="<?php echo ($msg['voyage']); ?>" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">申报公司代码：</td>
							<td>
								<input type="text" class="article" value="<?php echo ($msg['applycode']); ?>" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">申报公司名称：</td>
							<td>
								<input type="text" class="article" value="<?php echo ($msg['applyname']); ?>" readonly>
							</td>
						</tr>

						<tr>
							<td height="36" width="110" align="right" valign="middle">金额：</td>
							<td>
								<input type="text" class="article" value="<?php echo ($msg['amount']); ?>" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">结算方式：</td>
							<td>
							  <input type="text" class="article" value="<?php echo $customer_paytype_d[$msg ['paytype']];?>" readonly>
							</td>

						    <td height="36" width="110" align="right" valign="middle">收款标识：</td>
							<td>
							    <?php  if($msg['rcvflag']=='1') { echo '<input type="text" class="article" value="已收款" readonly>'; }else { echo '<input type="text" class="article" value="未收款" readonly>'; } ?>
							</td>

						</tr>

						<tr>
						    <td height="36" width="110" align="right" valign="middle"><span style="color:red">*</span>拆箱地点：</td>
							<td>
								<input type="text" class="article" name="location_name"
								id="location_name" required="required" autocomplete="off"
								style="text-transform: uppercase;" value="<?php echo ($msg['unpackagingplace']); ?>"/>
							</td>
							
							<td height="36" width="110" align="right" valign="middle">拆箱方式：</td>
							<td>
								<input type="text" class="article" value="<?php echo ($msg['operating_type_zh']); ?>" readonly>
							</td>
							
							<td height="36" width="110" align="right" valign="middle">业务系统：</td>
							<td>
								<input type="text" class="article" 
								<?php if($msg['business'] == 'cfs'){echo "value='CFS拆箱'";}else{echo "value='门到门拆箱'";}?>
								 readonly>
							</td>
						</tr>
						<tr>
							<td height="36" width="110" align="right" valign="middle">运输条款：</td>
							<td>
								<input type="text" class="article" value="<?php echo ($msg['transit']); ?>" readonly>
							</td>
						</tr>
						
						<tr>
							<td height="36" width="110" align="right" valign="top">备注：</td>
							<td colspan="5">
								<textarea class="article" style="width: 750px; height: 100px" readonly><?php echo ($msg['note']); ?></textarea>
							</td>
						</tr>
						<tr style="text-align: right;">
							<td colspan="6"><input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
								<input type="reset" class="qr" value="重&nbsp;置" /></td>
						</tr>
					</table>
					</form>
				 <div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width:900px">
								<thead>
									<tr>
										<th>提单号</th>
										<th>付费方名称</th>
										<th>货名</th>
										<th>包装</th>
										<th>件数</th>
										<th>标志</th>
										<th>收货人</th>
										<th>危险等级</th>
										<th>联合国编号</th>
										<th>联系人</th>
										<th>联系方式</th>
									</tr>
								</thead>
								<tbody>
									<?php if(is_array($cargolist)): $i = 0; $__LIST__ = $cargolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><tr>
										<td><?php echo ($c['blno']); ?></td>
										<td><?php echo ($c['payman']); ?></td>
										<td><?php echo ($c['cargoname']); ?></td>
										<td><?php echo ($c['package']); ?></td>
										<td><?php echo ($c['numbersofpackages']); ?></td>
										<td><?php echo ($c['mark']); ?></td>
										<td><?php echo ($c['consignee']); ?></td>
										<td><?php echo ($c['classes']); ?></td>
										<td><?php echo ($c['undgno']); ?></td>
										<td><?php echo ($c['contactuser']); ?></td>
										<td><?php echo ($c['contact']); ?></td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div style="clear: both; margin-bottom: 20px"></div>

				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table"
								style="margin: 0 auto; text-align: center; width: 900px">
								<thead>
									<tr>
										<th>序号</th>
										<th>箱号</th>
										<th>箱尺寸</th>
										<th>箱型</th>
										<th>铅封号</th>
										<th>件数</th>
										<th>重量</th>
										<th>体积</th>
										<th>集装箱状态</th>
										<th>审核状态</th>
										<th>箱状态</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php $n=0;?>
									<?php if(is_array($msg['ctns'])): $i = 0; $__LIST__ = $msg['ctns'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><tr>
									<?php $n++;?>
									    <td><?php echo ($n); ?></td>
										<td><?php echo ($c['ctnno']); ?></td>
										<td><?php echo ($c['ctnsize']); ?></td>
										<td><?php echo ($c['ctntype']); ?></td>
										<td><?php echo ($c['sealno']); ?></td>
										<td><?php echo ($c['numbersofpackages']); ?></td>
										<td><?php echo ($c['weight']); ?></td>
										<td><?php echo ($c['volume']); ?></td>
										<td>
										<?php  if($c['flflag']=='F') { echo '整箱'; }else { echo '拼箱'; } ?>
										</td>
										<td>
										<?php
 switch ($c ['operation_examine']) { case '1' : echo '未审核'; break; case '2' : echo '通过'; break; case '3' : echo '未通过'; break; default: echo '---'; break; } ?></td>
										<td>
										<?php  switch ($c['status']) { case '0': echo '未作业'; break; case '1': echo '工作中'; break; case '2': echo '工作完成'; break; case '-1': echo '箱残损'; break; } ?>
										</td>
										<td><a href="/tally2test/index.php?s=/DdOperation/index/ctn_id/<?php echo ($c['id']); ?>">查看详情</a></td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
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
			<?php  foreach ($locationlist as $l) { echo '{title:"'.$l['location_code'].'",show:"'.$l['location_name'].'"},'; echo '{title:"'.$l['location_name'].'",show:"'.$l['location_name'].'"},'; } ?>
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