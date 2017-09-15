<!DOCTYPE HTML>
<html>
<head>
<title>起驳装箱_分票单证详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.PrintArea.js"></script>
<script type="text/javascript">
$(function(){
	$('.right_list2').find('table tbody tr:even').css('background','#fff');	
})
</script>
<style>
#wapper, .right, .right_t {
	width: 1000px
}

.amsg {
	width: 940px;
	margin: 0 auto;
	padding-left: 5px
}

.amsg p {
	height: 30px;
	line-height: 30px;
	font-size: 14px;
	text-align: left;
}

.right_t table td .article {
	width: 150px
}

.remark {
	width: 930px;
	margin: 10px auto;
	text-align: left;
	border: 1px solid #888;
	padding: 5px;
}
</style>
</head>

<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/QbzxSearch/RealTime">起驳装箱查询</a>&nbsp;&gt;&nbsp;<a href="__CONTROLLER__/ProveByTicket">分票单证查询</a>&nbsp;&gt;&nbsp;分票单证详情
				</div>
			</div>
			<div class="right_t" style="text-align: center;">
			<div  id="printContent">
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
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table" style="width: 940px; margin: 0 auto; text-align: center;">
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
				</div>
				<div class="remark">
					<p style="font-size: 16px;">批注：{$remark}</p>
				</div>
			</div>
				<p style="width: 940px; margin: 0 auto">
					<a id="add" href="javascript:;" class="btnPrint"
						style="float: right; margin: -4px 10px 6px; text-align: center;">打印</a>
				</p>

				<div style="clear: both; margin-top: 10px"></div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
$(function(){
        $(".btnPrint").click(function(){ $("#printContent").printArea(); });
});
</script>