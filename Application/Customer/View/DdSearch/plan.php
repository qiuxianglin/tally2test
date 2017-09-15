<head>
<title>门到门拆箱_预报计划列表</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule1.css" />
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
<script type="text/javascript">
$(function(){
	$('.right_list2').find('table tbody tr:even').css('background','#fff');	
})
</script>
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
				<div style="min-height: 80px;width: 1176px; margin: 0 auto;">
					<div class="searchabc">
						<p style="font-size:22px;color:#000;letter-spacing:2px;">门到门拆箱查询统计</p>
						<ul style="margin-left:-32px;">
							<li ><a href="__MODULE__/DdSearch/plan" style="background:#3398db;color:#fff;">委托计划查询</a></li>
							<li ><a href="__MODULE__/DdSearch/real_time">实时作业查询</a></li>
							<li ><a href="__MODULE__/DdSearch/complete">完成作业查询</a></li>
							<li ><a href="__MODULE__/DdSearch/documentByCtn">分箱单证查询</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="right_list2">
			   <div class="addrule">
					<form action="__ACTION__" method="get">
					<input type="hidden" name="p" value="1">
					<input type="hidden" name="c" value="DdSearch">
					<input type="hidden" name="a" value="plan">
					委托编号：<input type="text" name="orderid" class="input1">&nbsp;&nbsp;&nbsp;&nbsp;<br/><br/>
					船&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：<input type="text" class="input1" name="vslname" id="shipname" autocomplete="off" style="text-transform: uppercase;"/>&nbsp;&nbsp;&nbsp;&nbsp;
					航次：<input type="text" name="voyage" class="input1"> &nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" value="查询" style="background-color: #f1691e !important; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px;">
					</form>
				</div>
				<div style="clear: both;margin-bottom:10px"></div>
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table width="100%" class="table">
								<thead>
									<tr>
										<th>委托编号</th>
										<th>船名</th>
										<th>航次</th>
										<th>申报公司名称</th>
										<th>所属业务系统</th>
										<th>运输条款</th>
										<th>拆箱类别</th>
										<th>委托日期</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
										<td>{$l['orderid']}</td>
										<td>{$l['vslname']}</td>
										<td>{$l['voyage']}</td>
										<td>{$l['applyname']}</td>
										<td>
										<if condition="$l['business'] eq 'cfs'">
											CFS拆箱
										<elseif condition="$l['business'] eq 'dd'" />
											门到门拆箱
										</if>
										</td>
										<td>{$l['transit']}</td>
										<td>
										<if condition="$l['category'] eq '1'">
											港内拆箱
										<elseif condition="$l['category'] eq '2'" />
											港外拆箱
										</if>
										</td>
										<td>{$l['orderdate']}</td>
										<td>
										  <a href="__CONTROLLER__/edit/id/{$l['id']}">查看</a>
										</td>
									</tr>
									</volist>
								</tbody>
							</table>
							<div class="pages">{$page}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

<script>
$(function(){
	$("#shipname").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($shiplist as $s)
			{
				echo '{title:"'.$s['ship_code'].'",show:"'.$s['ship_name'].'"},';
				echo '{title:"'.$s['ship_name'].'",show:"'.$s['ship_name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
})
</script>