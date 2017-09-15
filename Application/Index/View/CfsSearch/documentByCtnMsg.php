<head>
<title>CFS装箱_分箱单证详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.PrintArea.js"></script>
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
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

	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/Search/index">查询统计</a>&nbsp;&gt;&nbsp;<a href="__CONTROLLER__/documentByCtn">分箱单证查询</a>&nbsp;&gt;&nbsp;单证详情
				</div>
			</div>

			<div class="right_t" style="text-align: center;">
			<div  id="printContent">
			    <h1 style="font-size: 20px;color:#000;text-align:center;margin-bottom:10px">装拆箱单证</h1>
			<?php 
			$content=json_decode($msg['content'],true);
			?>
				<div class="amsg">
					<p>船名：{$msg['ship_name']}&nbsp;&nbsp;&nbsp;&nbsp;
						航次：{$msg['voyage']}&nbsp;&nbsp;&nbsp;&nbsp;
						作业地点：{$msg['location_name']}</p>
					<p>箱号：{$msg['ctnno']}&nbsp;&nbsp;&nbsp;&nbsp;
						箱型尺寸：{$msg['ctn_type_code']}&nbsp;&nbsp;&nbsp;&nbsp;
						铅封号：{$msg['sealno']}&nbsp;&nbsp;&nbsp;&nbsp;
						箱重：{$msg['empty_weight']}&nbsp;&nbsp;&nbsp;&nbsp;
						货重：{$msg['cargo_weight']}&nbsp;&nbsp;&nbsp;&nbsp;
						总重：{$msg['total_weight']}&nbsp;&nbsp;&nbsp;&nbsp;
                    </p> 
					<p>总票数：{$msg['total_ticket']}&nbsp;&nbsp;&nbsp;&nbsp;
						总件数：{$msg['total_package']}&nbsp;&nbsp;&nbsp;&nbsp;
						总残损：{$msg['damage_num']}&nbsp;&nbsp;&nbsp;&nbsp;
						开始时间：{$begin_time}&nbsp;&nbsp;&nbsp;&nbsp;
						完成时间：{$msg['createtime']}</p>
				</div>

				<div style="clear: both; margin-top: 10px"></div>

				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table"
								style="width: 940px; margin: 0 auto; text-align: center;">
								<thead>
									<tr>
										<th>序号</th>
							            <th>提单号</th>										
										<th>标志</th>
										<th>包装</th>
										<th>货物件数</th>
										<th>残损件数</th>
									</tr>
								</thead>
								<tbody>
									<volist name="content" id="c" key="k">
									<tr>
										<td>{$k}</td>
										<td>{$c['blno']}</td>
										<td>{$c['package']}</td>
										<td>{$c['mark']}</td>
										<td>{$c['cargo_unit']}</td>
										<td>{$c['damage_unit']}</td>
									</tr>
									</volist>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="remark">
					<p>批注：{$msg['remark']}</p>
				</div>

				<p style="width: 940px; margin: 0 auto">
					<font class="msg2" style="margin-left: 0px;">理货员：{$msg['operator_name']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对接人：{$msg['consignee']}</font>
				</p>
			</div>
			<p style="width: 940px; margin: 0 auto">
					<a id="add" class="btnPrint" href="javascript:;" style="float: right; margin: -4px 10px 6px;">打印</a>
				</p>

				<div style="clear: both; margin-top: 10px"></div>

			</div>
		</div>
	</div>

<script type="text/javascript">
$(function(){
        $(".btnPrint").click(function(){ $("#printContent").printArea(); });
});
</script>