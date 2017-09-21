<head>
<title>拆箱系统_预报计划列表</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
<script type="text/javascript">
$(function(){
	$('.right_list2').find('table tbody tr:even').css('background','#fff');	
})
</script>
</head>

<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：拆箱系统</a>&nbsp;&gt;&nbsp;拆箱预报计划</a>
				</div>
			</div>
			<div class="right_list2">
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