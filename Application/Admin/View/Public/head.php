<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css"
		href="__PUBLIC__/ad/css/style.css">
<script>
$(document).ready(function(){
	var now_name='<?php echo CONTROLLER_NAME.'/'.ACTION_NAME;?>';
	$("#leftMenu dl dd a").each(function(){
	    var src=$(this).attr('href');
	    src_arr=src.split("/");
	    var arr_length=src_arr.length;
	    var name=src_arr[arr_length-2]+'/'+src_arr[arr_length-1];
	    if(now_name==name)
	    {
		    $(this).parent().addClass("on");
		}
	  });
});
</script>
</head>
<?php
switch (CONTROLLER_NAME) 
{
	//作业地点、港口
	case 'Location' :
		$hf2 = 'current';
		break;
	case 'Port' :
		$hf2 = 'current';
		break;
	//船舶、船代、货代
	case 'Ship' :
		$hf3 = 'current';
		break;
	case 'ShipAgent' :
		$hf3 = 'current';
		break;
	case 'CargoAgent' :
		$hf3 = 'current';
		break;
	//箱型、箱主
	case 'Container' :
		$hf4 = 'current';
		break;
	case 'ContainerMaster' :
		$hf4 = 'current';
		break;
	//客户
	case 'Customer' :
		$hf5 = 'current';
		break;
	//部门
	case 'Department' :
		$hf6 = 'current';
		break;
	//用户、用户组、用户权限
	case 'User' :
		$hf7 = 'current';
		break;
	case 'UserGroup' :
		$hf7 = 'current';
		break;
	case 'UserAuthRule' :
		$hf7 = 'current';
		break;
	//费率本、费率明细
	case 'Rate' :
		$hf8 = 'current';
		break;
	case 'RateDetail' :
		$hf8 = 'current';
		break;
	default :
		$hf1 = 'current';
		break;
}
?>
<body style="background: #E2E9EA">
	<div id="header" class="header">
		<div class="logo">
			<a href="#" target="_blank">
				<img src="__PUBLIC__/admin/images/logo.png" width="180">
			
			</a>
		</div>
		<div class="nav f_r">
			<a href="__MODULE__/System/cleancache" style="color: red;">清空缓存</a>
			&nbsp;&nbsp;
		</div>
		<div class="nav">
			&nbsp;&nbsp;&nbsp;&nbsp;欢迎您！<?php echo $_SESSION['adminname'];?>
			<i>|</i>
			[<?php echo $_SESSION['group_title'];?>]
			<i>|</i>
			<a href="__MODULE__/Index/loginout" style="color: red;">[安全退出]</a>
		</div>
		<div class="topmenu">
			<ul>
				<li>
					<span class="<?php echo $hf1;?>">
						<a href="__MODULE__/System/index">后台设置</a>
					</span>
				</li>
				<li>
					<span class="<?php echo $hf2;?>">
						<a href="__MODULE__/Location/index">作业地点维护</a>
					</span>
				</li>
				<li>
					<span class="<?php echo $hf3;?>">
						<a href="__MODULE__/Ship/index">船舶信息维护</a>
					</span>
				</li>
				<li>
					<span class="<?php echo $hf4;?>">
						<a href="__MODULE__/Container/index">集装箱信息维护</a>
					</span>
				</li>
				<li>
					<span class="<?php echo $hf5;?>">
						<a href="__MODULE__/Customer/index">客户管理</a>
					</span>
				</li>
				<li>
					<span class="<?php echo $hf6;?>">
						<a href="__MODULE__/Department/index">部门管理</a>
					</span>
				</li>
				<li>
					<span class="<?php echo $hf7;?>">
						<a href="__MODULE__/User/index">用户管理</a>
					</span>
				</li>
				<li>
					<span class="<?php echo $hf8;?>">
						<a href="__MODULE__/Rate/index">计费维护</a>
					</span>
				</li>
			</ul>
		</div>
		<div class="header_footer"></div>
	</div>
</body>
</html>