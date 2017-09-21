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
<title>CFS装箱_完成作业详情</title>
<link rel="stylesheet" type="text/css" href="/tally2test/Public/css/page.css" />
<link rel="stylesheet" type="text/css" href="/tally2test/Public/admin/css/rule.css" />
<script type="text/javascript" src="/tally2test/Public/js/jquery.firstebox.pack.js"></script>
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

	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0;">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="/tally2test/index.php?s=/Search/index">查询统计</a>&nbsp;&gt;&nbsp;<a href="/tally2test/index.php?s=/CfsSearch/complete">完成作业查询</a>&nbsp;&gt;&nbsp;完成作业详情
				</div>
			</div>

			<div class="right_t">
				<div class="amsg">
					<p>箱号：<?php echo ($msg['ctnno']); ?>
						<?php
 $n = count($emptylist); if($n>0) { for($i=0;$i<$n;$i++) { echo '<a href=".'.IMAGE_CFS_EMPTY.$emptylist[$i] ['empty_img'].'" class="firstebox" rel="empty">'; if($i == 0) { echo '( 查看空箱照片 )'; } echo '</a>'; } } ?>
						&nbsp;&nbsp;&nbsp;&nbsp; 铅封号：<?php echo ($msg['sealno']); ?>
						<?php
 if ($operationMsg ['seal_picture']) { echo '（<a href=".'.IMAGE_CFS_SEAL.$operationMsg ['seal_picture'].'" class="firstebox">查看铅封照片</a>）'; } ?>&nbsp;&nbsp;&nbsp;&nbsp; 
						<?php
 if ($operationMsg ['halfclose_door_picture']) { echo '（<a href=".'.IMAGE_CFS_HALFCLOSEDOOR.$operationMsg ['halfclose_door_picture'].'" class="firstebox">查看半关门照片</a>）'; } ?>
						&nbsp;&nbsp;&nbsp;&nbsp; 
						<?php
 if ($operationMsg ['close_door_picture']) { echo '（<a href=".'.IMAGE_CFS_CLOSEDOOR.$operationMsg ['close_door_picture'].'" class="firstebox">查看半关门照片</a>）'; } ?>
					</p>
					<p>
					         货物件数：<?php echo ($msg['total_ticket']); ?>&nbsp;&nbsp;&nbsp;
						残损件数：<?php echo ($msg['damage_num']); ?>&nbsp;&nbsp;&nbsp;
						开始时间：<?php echo ($operationMsg['begin_time']); ?>&nbsp;&nbsp;&nbsp;&nbsp;
						结束时间：<?php echo ($msg['createtime']); ?>&nbsp;&nbsp;&nbsp;&nbsp;</p>
				</div>

				<div class="row" style="text-align: center">
					<div class="col-xs-12">
						<table class="table"
							style="margin-left: 35px; margin-top: 10px; width: 950px">
							<thead>
								<tr>
									<th>关序号</th>
									<th>提单号</th>
									<th>货物件数</th>
									<th>残损件数</th>
									<th>理货员</th>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($levellist)): $i = 0; $__LIST__ = $levellist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><tr>
									<td><?php echo ($l['level_num']); ?></td>
									<td><?php echo ($l['blno']); ?></td>
									<td><?php echo ($l['num']); ?>&nbsp;&nbsp;
									<?php
 $n = count ( $l ['level_cargo_img'] ); if ($n > 0) { echo '（'; for($i = 0; $i < $n; $i ++) { echo '<a href=".'.IMAGE_CFS_CARGO. $l ['level_cargo_img'] [$i] ['level_img'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">'; if ($i == 0) { echo '查看照片'; } echo '</a>'; } echo '）'; }else{ echo '暂无'; } ?></td>
									<td><?php echo ($l['damage_num']); ?>
									<?php
 $n = count ( $l ['cargo_damage_img'] ); if ($n > 0) { echo '（'; for($i = 0; $i < $n; $i ++) { echo '<a href=".'.IMAGE_CFS_CDAMAGE.$l ['cargo_damage_img'] [$i] ['img'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">'; if ($i == 0) { echo '查看残损照片'; } echo '</a>'; } echo '）'; } ?></td>
									<td><?php echo ($l['user_name']); ?></td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
						</table>

						<div class="remark">
							<p>批注：<?php echo ($msg['remark']); ?></p>
						</div>
						
						
						<div class="clear" style="width: 950px;"></div>

						<div style="text-align: center; width: 950px;">
							<font style="font-size: 16px;">修改记录</font>
						</div>

						<table class="table"
							style="margin-left: 35px; margin-top: 10px; width: 950px">
							<thead>
								<tr>
									<th>修改目标</th>
									<th>原内容</th>
									<th>修改后内容</th>
									<th>修改人</th>
									<th>修改时间</th>
									<th>修改原因</th>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($amendlist)): $i = 0; $__LIST__ = $amendlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
									<td>
									<?php
 switch ($vo ['field_name']) { case 'sealno' : echo '铅封号'; break; case 'num' : echo '货物件数'; break; case 'damage_num' : echo '残损件数'; break; case 'seal_picture' : echo '铅封照片'; break; } ?>
									</td>
									<td>
									<?php
 if (strpos ( $vo ['field_old_value'], '/AppUpload' )) { echo '<a href="' . $vo ['field_old_value'] . '" class="firstebox">查看原照片</a>'; } else { echo $vo ['field_old_value']; } ?>
									</td>
									<td>
									<?php
 if (strpos ( $vo ['field_new_value'], '/AppUpload' )) { echo '<a href="' . $vo ['field_new_value'] . '" class="firstebox">查看新照片</a>'; } else { echo $vo ['field_new_value']; } ?>
									</td>
									<td><?php echo ($vo['user_name']); ?></td>
									<td><?php echo ($vo['date']); ?></td>
									<td><?php echo ($vo['remark']); ?></td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="foot_w">
    <div class="foot2">
      <p>版权所有  南京中理外轮理货有限公司   苏ICP备10220284号-1</p>
    </div>
</div>
</body>