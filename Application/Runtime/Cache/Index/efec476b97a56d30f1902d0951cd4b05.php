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
<title>起泊装箱_作业指令_作业详情</title>
<link rel="stylesheet" type="text/css" href="/tally2test/Public/css/page.css" />
<link rel="stylesheet" type="text/css"
	href="/tally2test/Public/admin/css/rule.css" />
<script type="text/javascript"
	src="/tally2test/Public/js/jquery.firstebox.pack.js"></script>
<style type="text/css" media="all">
@import "/tally2test/Public/css/firstebox.css";

#wapper, .right, .right_t, .right_list2, .row table {
	width: 1000px
}
</style>
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
})
</script>
</head>

<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0;">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="/tally2test/index.php?s=/QbzxPlan/index">起驳装箱</a>&nbsp;&gt;&nbsp; <a
						href="/tally2test/index.php?s=/QbzxInstruction/index">作业指令</a>&nbsp;&gt;&nbsp;作业详情
				</div>
			</div>

			<div class="right_t">
				<div class="amsg">
					<p>
						箱号：<?php echo ($ctnMsg['ctnno']); ?>
						<?php
 $n = count ( $msg ['empty_picture'] ); if ($n > 0) { echo '（'; for($i = 0; $i < $n; $i ++) { echo '<a href=".' . $msg ['empty_picture'] [$i] ['empty_picture_a'] . '" class="firstebox" rel="kongxiang">'; if ($i == 0) { echo '查看空箱照片'; } echo '</a>'; } echo '）'; } ?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;铅封号：<?php echo ($msg['sealno']); ?>
						<?php
 if ($msg ['seal_picture']) { echo '（<a href=".' . IMAGE_QBZX_SEAL . $msg ['seal_picture'] . '" class="firstebox">查看铅封照片</a>）'; } ?>
						<?php
 if ($msg ['halfclose_door_picture']) { echo '（<a href=".' . IMAGE_QBZX_HALFCLOSEDOOR . $msg ['halfclose_door_picture'] . '" class="firstebox">查看半关门照片</a>）'; } ?>
						<?php
 if ($msg ['close_door_picture']) { echo '（<a href=".' . IMAGE_QBZX_CLOSEDOOR . $msg ['close_door_picture'] . '" class="firstebox">查看全关门照片</a>）'; } ?>
					</p>
					<p>

						作业详情审核状态：
						<?php if($msg['operation_examine'] == 1): ?>未审核
						<?php elseif($msg['operation_examine'] == 2): ?>
							审核通过
						<?php elseif($msg['operation_examine'] == 3): ?>
							审核未通过<?php endif; ?>&nbsp;&nbsp;&nbsp;
						空箱重量：<?php echo ($msg['empty_weight']); ?>&nbsp;KG&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						货物重量：<?php echo ($msg['cargo_weight']); ?>&nbsp;KG&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="/tally2test/index.php?s=/QbzxOperation/editSealno/operation_id/<?php echo ($msg['id']); ?>"
							style="background-color: rgb(51, 152, 219) ! important; border-color: rgb(213, 213, 213); color: rgb(255, 255, 255); font-size: 16px; text-align: center; padding: 3px 15px;"
							class="box">修改</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</p>
					<p style="height:8px"></p>
				</div>

				<div class="row" style="text-align: center">
					<div class="col-xs-12">
						<div>
							<table class="table"
								style="margin-left: 35px; margin-top: 10px; width: 950px">
								<thead>
									<tr>
										<th>关序号</th>
										<th>提单号</th>
										<th>货物件数</th>
										<th>残损件数</th>
										<th>残损说明</th>
										<th>理货员</th>
										<th>照片</th>
										<th>备注</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php if(is_array($levelList)): $i = 0; $__LIST__ = $levelList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><tr>
										<td><?php echo ($l['level_num']); ?></td>
										<td><?php echo ($l['billno']); ?></td>
										<td><?php echo ($l['cargo_number']); ?></td>
										<td><?php echo ($l['damage_num']); ?>
												<?php
 $n = count ( $l ['damage_picture'] ); if ($n > 0) { echo '（'; for($i = 0; $i < $n; $i ++) { echo '<a href=".' . $l ['damage_picture'] [$i] ['damage_picture'] . '" class="firstebox" rel="gallery' . $l ['level_num'] . '">'; if ($i == 0) { echo '查看残损照片'; } echo '</a>'; } echo '）'; } ?>
										</td>
										<td>
										   <?php
 if ($l ['damage_explain']) { echo '<a href="javascript:;" onclick="alert(\'' . $l ['damage_explain'] . '\')">查看残损说明</a>'; } ?>
										</td>
										<td><?php echo ($l['user_name']); ?></td>
										<td>
											<?php
 $n = count ( $l ['cargo_picture'] ); if ($n > 0) { for($i = 0; $i < $n; $i ++) { echo '<a href=".' . $l ['cargo_picture'] [$i] ['cargo_picture'] . '" class="firstebox" rel="gallery' . $l ['level_num'] . '">'; if ($i == 0) { echo '查看照片'; } echo '</a>'; } } ?>
										</td>
										<td>
										  <?php
 if ($l ['comment']) { echo '<a href="javascript:;" onclick="alert(\'' . $l ['comment'] . '\')">查看备注</a>'; } ?>
										</td>
										<td><a
											href="/tally2test/index.php?s=/QbzxOperation/editlevel/operation_id/<?php echo ($l['operation_id']); ?>/level_id/<?php echo ($l['id']); ?>"
											class="box">修改</a></td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<?php if(($ctnMsg['status'] == 2) and ($msg['operation_examine'] != 2)): ?><div style="margin-left:700px;font-size:15px;width:300px;height:50px;line-height:50px;">
						审核作业情况：

						<a onclick="return confirm('你确认要审核通过该作业信息吗？');" href="/tally2test/index.php?s=/QbzxOperation/operation_examine/operation_id/<?php echo ($msg['id']); ?>/ctn_id/<?php echo ($ctn_id); ?>/operation_examine/2/instruction_id/<?php echo ($ctnMsg['instruction_id']); ?>" style="background-color: #f1691e; border-color: rgb(213, 213, 213); color: rgb(255, 255, 255); font-size: 16px; text-align: center; padding: 3px 15px;">通过</a>
									&nbsp;&nbsp;
					</div><?php endif; ?>
				<div style="margin-left:850px;font-size:15px;width:300px;height:50px;line-height:50px;">
					<a
							href="/tally2test/index.php?s=/QbzxOperation/pack_img/operation_id/<?php echo ($msg['id']); ?>/ctn_id/<?php echo ($ctn_id); ?>"
							style="background-color: rgb(51, 152, 219); border-color: rgb(213, 213, 213); color: rgb(255, 255, 255); font-size: 16px; text-align: center; padding: 3px 15px;">照片下载</a>
					</div>
			</div>
		</div>
	</div>
</body>
	<div class="foot_w">
    <div class="foot2">
      <p>版权所有  南京中理外轮理货有限公司   苏ICP备10220284号-1</p>
    </div>
</div>
</body>