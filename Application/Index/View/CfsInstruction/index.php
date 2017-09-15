<head>
<title>CFS装箱_指令列表</title>
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
					当前位置：<a href="__MODULE__/CfsInstruction/index">CFS装箱</a>&nbsp;&gt;&nbsp;指令列表
				</div>
			</div>
			<div class="right_list2">
				<a id="add" href="__MODULE__/CfsInstruction/add" style="float: left; margin: -4px 10px 6px;">新增作业指令</a>
				<div style="clear: both"></div>
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table">
								<thead>
									<tr>
										<th>船名</th>
										<th>航次</th>
										<th>装箱方式</th>
										<th>作业地点</th>
										<th>作业状态</th>
										<th>日期</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
										<td>{$l['ship_name']}</td>
										<td>{$l['voyage']}</td>
										<td><?php if($l['operation_type'] == '1'){
										echo $l['operation_type'] = '机械';
										}else{
										echo $l['operation_type'] = '人工';
										}?></td>
										<td>{$l['location_name']}</td>
										<td>
										<?php if($l['status'] == '0'){
										echo $l['status'] = '未派工';
										}else if($l['status'] == '1'){
										echo $l['status'] = '已派工';
										}else if($l['status'] == '2'){
										echo $l['status'] = '已完成';
										}?>
										</td>
										<td>{$l['date']}</td>
										<td>
										  <a href="__MODULE__/CfsInstruction/edit/id/{$l['id']}">查看 | 修改</a>&nbsp;|&nbsp;<a onclick="return confirm('删除是不可恢复的，你确认要删除吗？');" href="__CONTROLLER__/del_instruct/id/{$l['id']}">删除</a>
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