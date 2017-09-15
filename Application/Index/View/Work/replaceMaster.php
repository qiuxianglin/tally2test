<head>
<title>替换当班理货长</title>
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
					当前位置：工班作业&nbsp;&gt;&nbsp;工班恢复
				</div>
			</div>
			<div class="right_list2">
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table">
								<thead>
									<tr>
										<th>工班ID</th>
										<th>当班理货长</th>
										<th>所属部门组</th>
										<th>班次</th>
										<th>工班日期</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
								<?php 
								$Shift=new \Common\Model\ShiftModel();
								?>
									<volist name="list" id="l">
									<?php 
									//根据工班ID获取工班信息
									$workMsg=$Shift->getShiftMsg($l['shift_id']);
									?>
									<tr>
										<td>{$l['shift_id']}</td>
										<td>{$workMsg['master_name']}</td>
										<td>{$workMsg['parent_department_name']}-{$workMsg['department_name']}</td>
										<td>{$workMsg['classes_zh']}</td>
										<td>{$l['date']}</td>
										<td>
										   <a class="box" href="__CONTROLLER__/replaceMasterPro/shift_id/{$l['shift_id']}">替换当班理货长</a>
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