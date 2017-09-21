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
<title>起驳装箱_分箱单证详情</title>
<link rel="stylesheet" type="text/css" href="/tally2test/Public/css/page.css" />
<link rel="stylesheet" type="text/css" href="/tally2test/Public/admin/css/rule.css" />
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
</script>
<style>
#wapper, .right, .right_t {
	width: 1000px
}

.amsg {
	width: 940px;
	margin: 0 auto;
	padding-left: 5px
}

.amsg p {
	height: 30px;
	line-height: 30px;
	font-size: 14px;
	text-align: left;
}

.right_t table td .article {
	width: 150px
}

.remark {
	width: 930px;
	margin: 10px auto;
	text-align: left;
	border: 1px solid #888;
	padding: 5px;
}
</style>
</head>

	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="/tally2test/index.php?s=/Search/index">查询统计</a>&nbsp;&gt;&nbsp;<a href="/tally2test/index.php?s=/QbzxSearch/documentByCtn">分箱单证查询</a>&nbsp;&gt;&nbsp;单证详情
				</div>
			</div>

			<div class="right_t" style="text-align: center;">
				<div class="amsg">
					<p>船名：<?php echo ($msg['ship_name']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						航次：<?php echo ($msg['voyage']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						作业地点：<?php echo ($msg['location_name']); ?></p>
					<p>箱号：<?php echo ($msg['ctnno']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						箱型尺寸：<?php echo ($msg['ctn_type_code']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						铅封号：<?php echo ($msg['sealno']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						箱重：<?php echo ($msg['empty_weight']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						货重：<?php echo ($msg['cargo_weight']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						总重：<?php echo ($msg['total_weight']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    </p> 
					<p>总票数：<?php echo ($msg['total_ticket']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						总件数：<?php echo ($msg['total_package']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						总残损：<?php echo ($msg['damage_num']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						开始时间：<?php echo ($begin_time); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						完成时间：<?php echo ($msg['createtime']); ?></p>
				</div>

				<div style="clear: both; margin-top: 10px"></div>

				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table"
								style="width: 940px; margin: 0 auto; text-align: center;">
								<thead>
									<tr>
										<th>序号</th>
							            <th>提单号</th>										
										<th>标志</th>
										<th>包装</th>
										<th>货物件数</th>
										<th>残损件数</th>
									</tr>
								</thead>
								<tbody>
								<?php  $content=json_decode($msg['content'],true); ?>
									<?php if(is_array($content)): $k = 0; $__LIST__ = $content;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($k % 2 );++$k;?><tr>
										<td><?php echo ($k); ?></td>
										<td><?php echo ($c['billno']); ?></td>
										<td><?php echo ($c['mark']); ?></td>
										<td><?php echo ($c['package']); ?></td>
										<td><?php echo ($c['cargo_unit']); ?></td>
										<td><?php echo ($c['damage_unit']); ?></td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="remark">
					<p>批注：<?php echo ($msg['remark']); ?></p>
				</div>

				<p style="width: 940px; margin: 0 auto">
					<font class="msg2" style="margin-left: 0px;">理货员：<?php echo ($msg['operator_name']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对接人：<?php echo ($msg['consignee']); ?></font>
					<a id="add" href="javascript:;" style="float: right; margin: -4px 10px 6px;">打印</a>
				</p>

				<div style="clear: both; margin-top: 10px"></div>

			</div>
		</div>
	</div>

	<div class="foot_w">
    <div class="foot2">
      <p>版权所有  南京中理外轮理货有限公司   苏ICP备10220284号-1</p>
    </div>
</div>
</body>