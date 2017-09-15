<head>
<title>起驳装箱_分驳船单证详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/nff.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.firstebox.pack.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.PrintArea.js"></script>
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
</script>
<style>
@import "__PUBLIC__/css/firstebox.css";
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
					当前位置：<a href="__MODULE__/QbzxSearch/RealTime">起驳装箱查询</a>&nbsp;&gt;&nbsp;<a href="__MODULE__/QbzxSearch/ProveByShip">分驳船单证查询</a>&nbsp;&gt;&nbsp;单证详情
				</div>
			</div>

			<div class="right_t" style="text-align: center;">
			<div  id="printContent">
				<div class="amsg">
					<p>船名：{$ctn_content['ship_name']}&nbsp;&nbsp;&nbsp;&nbsp;
						航次：{$ctn_content['voyage']}&nbsp;&nbsp;&nbsp;&nbsp;
						作业地点：{$ctn_content['location_name']}&nbsp;&nbsp;&nbsp;&nbsp;
						驳船：{$ctn_content['ship_name']}</p>
					<p>箱号：{$ctn_content['ctnno']}&nbsp;&nbsp;&nbsp;&nbsp;
						箱型尺寸：{$ctn_content['ctn_type_code']}&nbsp;&nbsp;&nbsp;&nbsp;
						铅封号：{$ctn_content['sealno']}&nbsp;&nbsp;&nbsp;&nbsp;
						箱重：{$ctn_content['empty_weight']} KG&nbsp;&nbsp;&nbsp;&nbsp;
						货重：{$ctn_content['cargo_weight']} KG&nbsp;&nbsp;&nbsp;&nbsp;
						总重：{$ctn_content['total_weight']} KG</p>
					<p>总票数：{$ctn_content['total_ticket']}&nbsp;&nbsp;&nbsp;&nbsp;
						总件数：{$ctn_content['total_package']}&nbsp;&nbsp;&nbsp;&nbsp;
						总关数：{$ctn_content['level_num']}&nbsp;&nbsp;&nbsp;&nbsp;
						总残损：{$ctn_content['damage_num']}&nbsp;&nbsp;&nbsp;&nbsp;
						开始时间：{$begin_time}&nbsp;&nbsp;&nbsp;&nbsp;
						完成时间：{$ctn_content['createtime']}</p>
				</div>

				<div style="clear: both; margin-top: 10px"></div>

				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table"
								style="width: 940px; margin: 0 auto; text-align: center;">
								<thead>
									<tr>
										<th>关序号</th>
										<th>提单号</th>
										<th>标志</th>
										<th>包装</th>
										<th>货物件数</th>
										<th>残损件数</th>
										<th>残损说明</th>
										<th>备注</th>
										<th>理货员</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
									   <td>{$l['level_num']}</td>
									   <td>{$l['billno']}</td>
									   <td>{$l['mark']}</td>
									   <td>{$l['pack']}</td>
									   <td>
									     {$l['cargo_number']}
									   </td>
									   <td>
									     {$l['damage_num']}
									     
									     </td>
									     <td>
									        <a href="javascript:;" onclick="cansun({$l['id']})">查看残损说明</a>
									     </td>
									     <td>
									        <a href="javascript:;" onclick="beizhu({$l['id']})">查看备注</a>
									     </td>
									     <td>{$l['user_name']}</td>
								     </tr>
								     </volist>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="remark">
					<p>批注：{$ctn_content['remark']}</p>
				</div>
			
				<p style="width: 940px; margin: 0 auto">
					<font class="msg2" style="margin-left: 0px;">理货员：{$ctn_content['user_name']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对接人：{$ctn_content['consignee']}</font>
				</p>
			</div>
			<p style="width: 940px; margin: 0 auto">
					<a id="add" class="btnPrint" href="javascript:;" style="float: right; margin: -4px 10px 6px;">打印</a>
				</p>
				<div style="clear: both; margin-top: 10px"></div>

			</div>
		</div>
	</div>
</body>

<div style="display: none">
	<?php 
	foreach ( $list as $l ) {
		echo '<p id="cansun' . $l ['id'] . '">' . $l ['damage_explain'] . '</p>';
	}
	?>
	</div>
	<script>
	function cansun(id)
	{
		if(id)
		{
			var a='#cansun'+id;
			var content=$(a).html();
			alert(content);
		}
	}
	</script>
	<div style="display: none">
	<?php
	foreach ( $list as $l ) {
		echo '<p id="beizhu' . $l ['id'] . '">' . $l ['comment'] . '</p>';
	}
	?>
	</div>
	<script>
	function beizhu(id)
	{
		if(id)
		{
			var a='#beizhu'+id;
			var content=$(a).html();
			alert(content);
		}
	}
	</script>
	<script type="text/javascript">
$(function(){
        $(".btnPrint").click(function(){ $("#printContent").printArea(); });
});
</script>