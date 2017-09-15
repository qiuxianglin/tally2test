<head>
<title>起驳装箱_预报计划列表</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
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
					当前位置：<a href="__MODULE__/QbzxPlan/index">起驳装箱</a>&nbsp;&gt;&nbsp;预报计划列表
				</div>
			</div>
			<div class="right_list2">
				<a id="add" href="__MODULE__/QbzxPlan/add" style="float: right; margin: -4px 10px 6px;">新增预报计划 </a>
				<div style="clear: both"></div>
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table">
								<thead>
									<tr>
										<th>委托编号</th>
										<th>船名</th>
										<th>航次</th>
										<th>总票数</th>
										<th>总箱数</th>
										<th>理货地点</th>
										<th>委托单位</th>
										<th>委托日期</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
										<td>{$l['entrustno']}</td>
										<td>{$l['ship_name']}</td>
										<td>{$l['voyage']}</td>
										<td>{$l['total_ticket']}</td>
										<td>{$l['total_ctn']}</td>
										<td>{$l['location_name']}</td>
										<td>{$l['customer']}</td>
										<td>{$l['entrust_time']}</td>
										<td>
										  <a href="__MODULE__/QbzxPlan/edit/plan_id/{$l['id']}">查看 | 修改</a>
										  | <a onclick="return confirm('删除是不可恢复的，你确认要删除该预报计划吗？');" href="__MODULE__/QbzxPlan/del/plan_id/{$l['id']}">删除</a>
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