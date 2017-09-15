<head>
<title>门到门拆箱_指令列表</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript">
$(function(){
	$('.right_list2').find('table tbody tr:even').css('background','#fff');	
})
</script>
</head>
<style type="text/css">
.clear{clear:both;}
</style>

<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="">门到门拆箱</a>&nbsp;&gt;&nbsp;指令列表
				</div>
			</div>
			<div class="right_list2">
				<div style="clear: both"></div>
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table width="100%" class="table">
								<thead>
									<tr>
										<th>委托编号</th>
										<th>业务系统</th>
										<th>中文船名</th>
										<th>航次</th>
										<th>申报公司名称</th>
										<th>拆箱地点</th>
										<th>拆箱类别</th>
										<th>委托日期</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
										<td>{$l['orderid']}</td>
										<if condition="$l['business'] eq 'cfs'">
											<td>CFS拆箱</td>
										<else />
											<td>门到门拆箱</td>	
										</if>
										<td>{$l['vslname']}</td>
										<td>{$l['voyage']}</td>
										<td>{$l['applyname']}</td>
										<td>{$l['unpackagingplace']}</td>
										<td>
									<?php echo $l['category'] == '1' ? '港内拆箱' : '港外拆箱';?>
									</td>
										<td>{$l['orderdate']}</td>
										<td>
										  <a href="__CONTROLLER__/view/plan_id/{$l['id']}/instruction_id/{$l['instruction_id']}">查看详情进行派工</a>
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

<script type="text/javascript">
	$(function() { 
		$('.mwui-switch-btn').each(function() {
			$(this).bind("click", function() { 
				var btn = $(this).find("span");
				var change = btn.attr("change");
				btn.toggleClass('off'); 

				if(btn.attr("class") == 'off') { 
					$(this).find("input").val("1");
					btn.attr("change", btn.html()); 
					btn.html(change);
				} else {
					$(this).find("input").val("2");
					btn.attr("change", btn.html()); 
					btn.html(change);
				}
				return false;
			});
		});
	});
</script>