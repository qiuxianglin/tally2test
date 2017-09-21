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
<title>起泊装箱_作业指令详情</title>
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
#wapper,.right,.right_t {
	width: 1000px
}
</style>
</head>

<body>
	<div id="wapper">
		<div class="right">

			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：
					<a href="/tally2test/index.php?s=/QbzxPlan/index">起驳装箱</a>
					&nbsp;&gt;&nbsp;
					<a href="/tally2test/index.php?s=/QbzxInstruction/index">作业指令</a>
					&nbsp;&gt;&nbsp;作业指令详情
				</div>
			</div>

			<div class="right_t">
				<p
					style="margin: 0 auto; width: 740px; min-height: 30px; line-height: 30px; font-size: 14px;">
					指令编号：<?php echo ($msg['id']); ?>
					&nbsp;&nbsp;&nbsp;&nbsp;指令日期：<?php echo ($msg['ordertime']); ?>
					&nbsp;&nbsp;&nbsp;&nbsp;船名：<?php echo ($planMsg['ship_name']); ?>
					&nbsp;&nbsp;&nbsp;&nbsp;航次：<?php echo ($planMsg['voyage']); ?>
					&nbsp;&nbsp;&nbsp;&nbsp;已配箱数：<?php echo ($has_container_num); ?></p>
				<form action="/tally2test/index.php?s=/QbzxInstruction/edit/id/<?php echo ($msg['iid']); ?>" method="post">
					<table border="" style="margin: 0 auto;">
						<tr>
							<td width="70" align="right" valign="middle">作业场地：</td>
							<td>
								<input type="text" class="article" name="location_name"
									id="location_name" required="required" autocomplete="off"
									style="text-transform: uppercase; width: 90px;"
									value="<?php echo ($msg['location_name']); ?>" />
							</td>
							<td width="70" align="right" valign="middle">装箱方式：</td>
							<td>
								<select name="loadingtype" class="article" style="width: 62px;">
									<option value="1"
										<?php echo ($msg['loadingtype']=='1') ? 'selected' : '';?>
										>机械</option>
									<option value="0"
										<?php echo ($msg['loadingtype']=='0') ? 'selected' : '';?>
										>人工</option>
								</select>
							</td>
							<td>作业班组：<?php echo ($dMsg['parent_department_name']); ?>-<?php echo ($dMsg['department_name']); ?></td>
							<td>
								<input type="submit" class="qr" value="修&nbsp;改" />
								&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
					</table>
				</form>

				<div class="clear"></div>

				<div class="row" style="width: 740px; margin: 0 auto">
					<div class="rowh">
						<span>派工情况</span>
						<?php if ($msg['status']=='0') echo '<a style="float: right" id="pg" class="box" href="/tally2test/index.php?s=/QbzxDispatch/add/instruction_id/'.$instruction_id.'">新增派工</a>';?>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;">
								<thead>
									<tr>
										<th>工班号</th>
										<th>理货长</th>
										<th>派工时间</th>
										<th>理货员</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo ($dispatch_detail['shift_id']); ?></td>
										<td><?php echo ($dispatch_detail['chieftally_name']); ?></td>
										<td><?php echo ($dispatch_detail['dispatch_time']); ?></td>
										<td>
										  <?php  foreach ($dispatch_detail['detail'] as $d) { echo $d['user_name'].'&nbsp;&nbsp;'; } ?>
										</td>
										<td>
										<?php
 if ($msg['status']!='0' and $msg['status']!='-1') { echo '<a class="box" href="/tally2test/index.php?s=/QbzxDispatch/edit/instruction_id/'.$instruction_id.'/dispatch_id/'.$dispatch_detail['id'].'">修改派工</a>
												| <a onclick="return confirm("你确认要取消派工吗？");" href="/tally2test/index.php?s=/QbzxDispatch/cancel/instruction_id/'.$instruction_id.'/dispatch_id/'.$dispatch_detail['id'].'">取消派工</a>'; } ?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="clear"></div>

				<div class="row" style="width: 740px; margin: 0 auto">
					<div class="rowh">
						<span>配箱情况</span>
						<a style="float: right" id="pg" class="box"
							href="/tally2test/index.php?s=/QbzxInstructionCtn/add/instruction_id/<?php echo ($instruction_id); ?>">新增配箱</a>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;">
								<thead>
									<tr>
									    <th>序号</th>
										<th>箱号</th>
										<th>箱型尺寸</th>
										<th>箱主</th>
										<th>作业状态</th>
										<th>审核状态</th>
										<th>操作</th>
										<th>替换理货员</th>
									</tr>
								</thead>
								<tbody>
								<?php $n=0;?>
									<?php if(is_array($containerlist)): $i = 0; $__LIST__ = $containerlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cl): $mod = ($i % 2 );++$i;?><tr>
									<?php $n++;?>
									    <td><?php echo ($n); ?></td>
										<td><?php echo ($cl['ctnno']); ?></td>
										<td><?php echo ($cl['ctn_type_code']); ?></td>
										<td><?php echo ($cl['cmaster']); ?></td>
										<td>
										<?php
 switch ($cl ['status']) { case '0' : echo '未作业'; break; case '1' : echo '工作中'; break; case '2' : echo '已铅封'; break; case '-1' : echo '<span style="color: red; font-size: 14px;">箱残损</span>'; break; } ?>
										</td>
										<td>
										<?php
 switch ($cl ['operation_examine']) { case '1' : echo '未审核'; break; case '2' : echo '通过'; break; case '3' : echo '未通过'; break; default: echo '---'; break; } ?>
										</td>
										<td>
										    <?php
 if($cl['status']=='0') { echo '<a class="box" href="/tally2test/index.php?s=/QbzxInstructionCtn/edit/id/'.$cl['id'].'">查看 | 修改</a>| <a onclick="return confirm(\'删除是不可恢复的，你确认要删除吗？\');" href="/tally2test/index.php?s=/QbzxInstructionCtn/del/id/'.$cl['id'].'">删除</a>'; }else { if($cl['status'] == '-1'){ echo '<a onclick="return confirm(\'删除是不可恢复的，你确认要删除吗？\');" href="/tally2test/index.php?s=/QbzxInstructionCtn/del/id/'.$cl['id'].'">删除</a>'; }else { echo '<a href="/tally2test/index.php?s=/QbzxOperation/index/ctn_id/'.$cl['id'].'">查看作业详情</a>'; } } ?>
										</td>
										<td>
										    <?php  if($cl['status']=='0' or $cl['status'] == '-1') { echo '暂无理货员'; }else { echo '<a href="/tally2test/index.php?s=/QbzxOperation/replaceTallyClerk/instruction_id/'.$instruction_id.'/ctn_id/'.$cl['id'].'" class="box">替换理货员</a>'; } ?>
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
		width:100,
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