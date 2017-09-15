<!DOCTYPE HTML>
<html>
<head>
<title>起驳装箱_分票单证详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
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
					当前位置：<a href="__MODULE__/Search/index">查询统计</a>&nbsp;&gt;&nbsp;<a href="__CONTROLLER__/documentByTicket">分票单证查询</a>&nbsp;&gt;&nbsp;分票单证详情
				</div>
			</div>
			<div class="right_list2">
				<div class="amsg" style="width: 940px;">
					<p>船名：{$instructionMsg['ship_name']}&nbsp;&nbsp;&nbsp;&nbsp;
						航次：{$instructionMsg['voyage']}&nbsp;&nbsp;&nbsp;&nbsp;
						提单号：{$instructionMsg['billno']}&nbsp;&nbsp;&nbsp;&nbsp;
						作业场地：{$instructionMsg['location_name']}
					</p>
					<p>
					总箱数：{$total_ctn}&nbsp;&nbsp;&nbsp;&nbsp;
					总件数：{$total_num}&nbsp;&nbsp;&nbsp;&nbsp;
					总残损：{$total_damage}&nbsp;&nbsp;&nbsp;&nbsp;
					完成时间：{$finished_time}
					</p>
				</div>
				<div style="clear: both; margin-top: 10px;"></div>
				<div class="row" style="margin-top: 10px;">
					<div class="col-xs-12">
						<table class="table" style="width: 940px;">
							<thead>
								<tr>
									<th>序号</th>
									<th>箱号</th>
									<th>箱型尺寸</th>
									<th>铅封号</th>
									<th>件数</th>
									<th>残损</th>
									<th>完成时间</th>
									<th>理货员</th>
									<th>详情</th>
								</tr>
							</thead>
							<tbody>
							<?php 
							$user=new \Common\Model\UserModel();
							?>
								<volist name="list" id="l" key="k">
								<tr>
									<td>{$k}</td>
									<td>{$l['ctnno']}</td>
									<td>{$l['ctn_type_code']}</td>
									<td>{$l['sealno']}</td>
									<?php 
									$content=json_decode($l['content'],true);
									?>
									<td>{$content[0]['cargo_unit']}</td>
									<td>{$content[0]['damage_unit']}</td>
									<td>{$l['createtime']}</td>
									<td>
									  <?php 
									  $operator=$l['operator_id'];
									  $res_u=$user->where("uid=$operator")->field('user_name')->find();
									  if($res_u['user_name'])
									  {
									  	echo $res_u['user_name'];
									  }
									  ?>
									</td>
									<td>
									   <a href="__MODULE__/QbzxSearch/ProveByCtnMsg/id/{$l['id']}">查看</a>
									</td>
								</tr>
								</volist>
							</tbody>
						</table>
					</div>
				</div>
				<div class="remark">
					<p style="font-size: 16px;">批注：{$remark}</p>
				</div>

				<p style="width: 940px; margin: 0 auto">
					<a id="add" href="javascript:;"
						style="float: right; margin: -4px 0; text-align: center;">打印</a>
				</p>

				<div style="clear: both; margin-top: 10px"></div>
			</div>
		</div>
	</div>
</body>
