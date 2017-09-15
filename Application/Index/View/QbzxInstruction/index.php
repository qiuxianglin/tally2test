<head>
<title>起驳装箱_作业指令列表</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/nff.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
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
					当前位置：<a href="__MODULE__/QbzxPlan/index">起驳装箱</a>&nbsp;&gt;&nbsp;作业指令列表
				</div>
			</div>
			<div class="right_list2">
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table">
								<thead>
									<tr>
										<th>委托编号</th>
										<th>船名</th>
										<th>航次</th>
										<th>理货地点</th>
										<th>委托单位</th>
										<th>委托日期</th>
										<th>总票数</th>
										<th>总箱数</th>
										<th>已配箱数</th>
										<th>作业场地</th>
										<th>指令日期</th>
										<th>装箱方式</th>
										<th>作业指令</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
										<td>{$l['entrustno']}</td>
										<td>{$l['ship_name']}</td>
										<td>{$l['voyage']}</td>
										<td>{$l['location_name']}</td>
										<td>
										<?php 
										if($l['customer'])
										{
											echo $l['customer'];
										}else {
											echo $l['customer_name'];
										}
										?>
										</td>
										<td>{$l['entrust_time']}</td>
										<td>{$l['total_ticket']}</td>
										<td>{$l['total_ctn']}</td>
										<td>{$l['has_container_num']}</td>
										<td>{$l['location_name']}</td>
										<td>{$l['instruction_date']}</td>
										<td><?php if ($l['operation_method']==='0'){echo '人工';}elseif($l['operation_method']==='1'){echo '机械';} ?></td>
										<td>
										  <if condition="$l['ins_count'] eq 0"> 
										    <a class="box" href="__MODULE__/QbzxInstruction/add/plan_id/{$l['id']}">添加</a>
											<else /> 
											<a href="__MODULE__/QbzxInstruction/listins/plan_id/{$l['id']}">查看</a>
										  </if>
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