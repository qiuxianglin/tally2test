<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/nff.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.firstebox.pack.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/firstebox.css" />
<?php
switch (CONTROLLER_NAME) {
	//查询统计
	case 'QbzxSearch' :
		$hf2 = 'on';
		break;
	case 'DdSearch' :
		$hf3 = 'on';
		break;
	case 'CfsSearch' :
		$hf4 = 'on';
		break;
	case 'QbzxSearch' :
		$hf5 = 'on';
		break;
	default :
		$hf1 = 'on';
		break;
}
switch (CONTROLLER_NAME.'/'.ACTION_NAME) {
	//个人信息
	case 'User/personal' :
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
					<a href="__MODULE__/QbzxSearch/RealTime">起驳装箱查询</a>
				</h3>
				<!--ul class="sub">
				   <li ><a href="__MODULE__/QbzxSearch/RealTime">实时作业查询</a></li>
				   <li ><a href="__MODULE__/QbzxSearch/OperationFinish">完成作业查询</a></li>
				   <li ><a href="__MODULE__/QbzxSearch/ProveByCtn">分箱单证查询</a></li>
				   <li ><a href="__MODULE__/QbzxSearch/ProveByTicket">分票单证查询</a></li>
				   <li ><a href="__MODULE__/QbzxSearch/ProveByShip">分驳船单证查询</a></li> 
				</ul-->
			</li>
			<li class="m <?php echo $hf4;?>">
				<h3>
					<a href="__MODULE__/CfsSearch/real_time">CFS装箱查询</a>
				</h3>
			</li>
			<li class="m <?php echo $hf3;?>">
				<h3>
					<a href="__MODULE__/DdSearch/real_time">拆箱查询</a>
				</h3>
			</li>
			<li class="m <?php echo $hf2;?>">
				<h3>
					<a href="__MODULE__/Index/index">收费查询</a>
				</h3>
			</li>
			<li class="m <?php echo $hf7;?>">
				<h3>
					<a href="__MODULE__/User/personal" >用户中心</a>
				</h3>
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