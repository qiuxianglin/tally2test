<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/nff.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.firstebox.pack.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/firstebox.css" />
<?php
switch (CONTROLLER_NAME) {
	//起泊装箱
	case 'QbzxPlan' :
		$hf2 = 'on';
		break;
	case 'QbzxPlanCtn' :
		$hf2 = 'on';
		break;
	case 'QbzxPlanCargo' :
		$hf2 = 'on';
		break;
	case 'QbzxInstruction' :
		$hf2 = 'on';
		break;
	case 'QbzxInstructionCtn' :
		$hf2 = 'on';
		break;
	case 'QbzxOperation' :
		$hf2 = 'on';
		break;
	//门到门拆箱
	case 'DdPlan' :
		$hf3 = 'on';
		break;
	case 'DdInstruction' :
		$hf3 = 'on';
		break;
	case 'DdOperation' :
		$hf3 = 'on';
		break;
	//CFS装箱
	case 'CfsInstruction' :
		$hf4 = 'on';
		break;
	case 'CfsInstructionCargo' :
		$hf4 = 'on';
		break;
	case 'CfsInstructionContainer' :
		$hf4 = 'on';
		break;
	// 查询统计
	case 'Search' :
		$hf5 = 'on';
		break;
	case 'QbzxSearch' :
		$hf5 = 'on';
		break;
	case 'DdSearch' :
		$hf5 = 'on';
		break;
	case 'CfsSearch' :
		$hf5 = 'on';
		break;
	case 'QbzxSearch' :
		$hf5 = 'on';
		break;
	//船期维护
	case 'ShipSchedule' :
		$hf6 = 'on';
		break;
	default :
		$hf1 = 'on';
		break;
}
switch (CONTROLLER_NAME.'/'.ACTION_NAME) {
	//起泊装箱
	case 'Index/personal' :
		$hf7 = 'on';
		break;
}
?>

<div class="top_w">
	<div class="top_bj">
		<img src="__PUBLIC__/img/zjmls_01.png" alt="" />
	</div>
</div>

<div class="nav_w">
	<div class="navBar">
		<ul class="nav clearfix">
			<li class="m">
				<h3>
					<a href="__MODULE__/Index/index">首页</a>
				</h3>
			</li>
			<li class="m <?php echo $hf2;?>">
				<h3>
					<a href="__MODULE__/QbzxPlan/index">起驳装箱</a>
				</h3>
				<ul class="sub">
					<li><a href="__MODULE__/QbzxPlan/index">查看预报</a></li>
					<li><a href="__MODULE__/QbzxPlan/add">新增预报</a></li>
					<li><a href="__MODULE__/QbzxInstruction/index">作业指令</a></li>
				</ul>
			</li>
			<li class="m <?php echo $hf3;?>">
				<h3>
					<a href="__MODULE__/DdInstruction/index">拆箱系统</a>
				</h3>
				<ul class="sub">
					<li><a href="__MODULE__/DdPlan/index">查看预报</a></li>
					<li><a href="__MODULE__/DdPlan/add">新增预报</a></li>
					<li><a href="__MODULE__/DdInstruction/index">作业指令</a></li>
				</ul>
			</li>
			<li class="m <?php echo $hf4;?>">
				<h3>
					<a href="__MODULE__/CfsInstruction/index">CFS装箱</a>
				</h3>
				<ul class="sub">
					<li><a href="__MODULE__/CfsInstruction/index">查看指令</a></li>
					<li><a href="__MODULE__/CfsInstruction/add">新增指令</a></li>
				</ul>
			</li>
			<li class="m <?php echo $hf5;?>">
				<h3>
					<a href="__MODULE__/Search/index">查询统计</a>
				</h3>
			</li>
			<li class="m <?php echo $hf6;?>">
				<h3>
					<a href="#" onclick="window.location.reload();">工班作业</a>
				</h3>
				<ul class="sub">
					<li><a href="__MODULE__/Work/signin" class="box">工班签到</a></li>
			<?php
			if ($_SESSION ['u_group_id'] == 12 or $_SESSION ['u_group_id'] == 13) 
            {
				echo '<li><a href="__MODULE__/Work/succeed" class="box">接班开工</a></li>
					<li><a href="__MODULE__/Work/transfer" class="box">收工交班</a></li>';
			}
			if ($_SESSION ['u_group_id'] == 13)
			{
				echo '<li><a href="__MODULE__/Work/resume">工班恢复</a></li>
		              <li><a href="__MODULE__/Work/replaceMaster">替换当班理货长</a></li>';
			}
			?>
			        <li><a href="__MODULE__/ShipSchedule/index">船期维护</a></li>
				</ul>
			</li>
			<li class="m <?php echo $hf7;?>">
				<h3>
					<a href="#" onclick="window.location.reload();">用户中心</a>
				</h3>
				<ul class="sub">
				    <!-- <li><a href="__MODULE__/User/login">用户登录</a></li> -->
					<li><a href="__MODULE__/User/personal">个人信息</a></li>
					<li><a href="__MODULE__/User/changepwd" class="box">修改密码</a></li>
					<li><a href="__PUBLIC__/img/xiaza.png" class="firstebox">下载客户端</a></li>
					<li><a href="__MODULE__/User/loginout">退出登录</a></li>
					<li><a href="__MODULE__/Test/invoice">模拟发送门到门预报</a></li>
					<li><a href="__MODULE__/Test/payment">模拟发送支付回执</a></li>
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