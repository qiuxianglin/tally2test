<head>
<title>起泊装箱_作业指令列表</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/nff.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
<script type="text/javascript">
$(function(){
	$('.right_list2').find('table tbody tr:even').css('background','#fff')	
})
</script>
<style>
#wapper, .right, .right_top, .right_list2, .row table {
	width: 1000px
}
</style>
</head>

<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/QbzxPlan/index">起驳装箱</a>&nbsp;&gt;&nbsp;
					<a href="__MODULE__/QbzxInstruction/index">作业指令</a>&nbsp;&gt;&nbsp;作业指令列表
				</div>
			</div>
			<div class="right_list2">
				<div class="hmsg">
					<div class="l">
						<p>
							委托编号：<span>{$planMsg['entrustno']}</span>&nbsp;&nbsp;&nbsp;&nbsp;船名：<span>{$planMsg['ship_name']}</span>&nbsp;&nbsp;&nbsp;&nbsp;航次：<span>{$planMsg['voyage']}</span>&nbsp;&nbsp;&nbsp;&nbsp;理货地点：<span>{$planMsg['location_name']}</span>
						</p>
						<p>
							委托单位：<span>{$planMsg['entrust_company']}</span>&nbsp;&nbsp;&nbsp;&nbsp;总票数：<span>{$planMsg['total_ticket']}</span>&nbsp;&nbsp;&nbsp;&nbsp;总件数：<span>{$planMsg['total_package']}</span>&nbsp;&nbsp;&nbsp;&nbsp;预配箱数：<span>{$planMsg['total_ctn']}</span>&nbsp;&nbsp;&nbsp;&nbsp;已配箱数：<span>{$has_container_num}</span>
						</p>
					</div>
					<div class="r">
						<a id="add" class="box" href="__MODULE__/QbzxInstruction/add/plan_id/{$plan_id}" style="margin: 10px 10px 6px;">新增作业指令</a>
					</div>
				</div>

				<div style="clear: both"></div>
				<div class="row" style="text-align: center;">
					<div class="col-xs-12">
						<div>
							<table class="table" style="width: 95%">
								<thead>
									<tr>
										<th>指令编号</th>
										<th>指令日期</th>
										<th>作业场地</th>
										<th>装箱方式</th>
										<th>已配箱数</th>
										<th>作业状态</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
								<?php 
								$InstructionContainer=new \Common\Model\QbzxInstructionCtnModel();
								?>
									<volist name="list" id="l">
									<tr>
										<td>{$l['id']}</td>
										<td>{$l['ordertime']}</td>
										<td>{$l['location_name']}</td>
										<td><if condition="$l['loadingtype'] eq 0">人工 <else />机械 </if></td>
										<td>
										<?php 
										echo $InstructionContainer->hasContainerNum($l['id']);
										?>
										</td>
										<td><if condition="$l['status'] eq 0">未派工 <elseif
												condition="$l['status'] eq 1" />已派工 <elseif
												condition="$l['status'] eq 2" />已完成 <else />未知 </if></td>
										<td>
										  <a href="__MODULE__/QbzxInstructionCtn/index/instruction_id/{$l['id']}">查看 | 修改</a>
										  | <a onclick="return confirm('删除是不可恢复的，你确认要删除吗？');" href="__CONTROLLER__/del/instruction_id/{$l['id']}">删除</a>
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