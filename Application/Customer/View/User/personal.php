<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>个人信息</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/nff.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.firstebox.pack.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/firstebox.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule1.css" />
<style>
.searchabc ul li{list-style:none;float:left;margin-left:30px;}
.searchabc ul li a{text-decoration:none;color:#505050;line-height:60px;font-family:"新宋体";font-size:17px;background:#f0f0f0;border:1px solid #f0f0f0;border-radius:5px;padding:10px 5px;}
.searchabc ul li a:hover{color:#fff;background:#3398db;}
</style>
</head>
<body>
	<div id="wapper" class="sywapper">
		<div class="right">
			<div class="wrapper_o">
				<div class="title">
					<span></span>
				</div>
				<div style="min-height: 80px; width: 1176px; margin: 0 auto;">
					<div class="searchabc">
						<p style="font-size:22px;color:#000;letter-spacing:2px;">用户中心</p>
						<ul style="margin-left:-32px;">
							<li ><a href="__MODULE__/User/changepersonal" class="box" style="background:#3398db;color:#fff;">修改用户信息</a></li>
							<li ><a href="__MODULE__/User/changepwd" class="box">修改密码</a></li>
							<li ><a href="__MODULE__/User/loginout">退出登录</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="right_list2">
				<div class="addrule" style="font-size:16px;">
						客户代码： {$customerMsg['customer_code']} <br/><br/>
						客户名称： {$customerMsg['customer_name']} <br/><br/>
						客户简称： <if condition="$customerMsg['customer_shortname'] eq ''">暂无<else/>{$customerMsg['customer_shortname']}</if> <br/><br/>
						客户类别： <if condition="$customerMsg['customer_category'] ==1">
						<td>代理</td>
						<elseif condition="$customerMsg['customer_category'] == 2"/>
						<td>货主</td>
						<elseif condition="$customerMsg['customer_category'] == 3"/>
						<td>港区</td>
						<else />
						<td>其他</td>
						</if> <br/><br/>
						联&nbsp; 系&nbsp; 人：<if condition="$customerMsg['linkman'] eq ''">暂无<else/>{$customerMsg['linkman']} </if><br/><br/>
						联系电话： <if condition="$customerMsg['telephone'] eq ''">暂无<else/>{$customerMsg['telephone']}</if> <br/><br/>
						合同有效期： <if condition="$customerMsg['contract_life'] eq ''">暂无<else/>{$customerMsg['contract_life']}</if> <br/><br/>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
</body>
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
</html>