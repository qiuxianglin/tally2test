<head>
<title>拆箱系统-分票单证查询</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<script type="text/javascript">
$(function(){
	$('.right_list2').find('table tbody tr:even').css('background','#fff');	
})
</script>
</head>


	<div id="wapper">
		<div class="right">

			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/Serch/index">查询统计</a>&nbsp;&gt;&nbsp;门到门拆箱&nbsp;&gt;&nbsp;分票单证查询
				</div>
			</div>
			<div class="right_list2">
				<div class="addrule">
					<form class="select" action="__ACTION__" method="get">
					<input type="hidden" name="p" value="1">
					<input type="hidden" name="c" value="DdSearch">
					<input type="hidden" name="a" value="documentByTicket">
						船名： <select class="input1" name="vslname" style="width: 135px">
							<option value="">--默认全部--</option>
							<volist name="shiplist" id="sl">
							<option value="{$sl['shipname']}">{$sl['shipname']}</option>
							</volist>
						</select>&nbsp;&nbsp;&nbsp;&nbsp; 
						航次：<input type="text"name="voyage" class="input1"> &nbsp;&nbsp;&nbsp;&nbsp;
						场地： <select class="input1" name="unpackagingplace" style="width: 135px">
							<option value="">--默认全部--</option>
							<volist name="locationlist" id="l">
							<option value="{$l['locationname']}">{$l['locationname']}</option>
							</volist>
						</select>&nbsp;&nbsp;&nbsp;&nbsp;
						提单号：<input type="text" name="blno" class="input1"> &nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" value="查询" style="background-color: #3398db !important; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px;">
					</form>
				</div>
				<div style="clear: both;"></div>
				<div class="row" style="margin-top: 10px">
					<div class="col-xs-12">
						<div>
							<table width="100%" class="table">
								<thead>
									<tr>
										<th>船名</th>
										<th>航次</th>
										<th>拆箱地点</th>
										<th>提单号</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
										<td>{$l['vslname']}</td>
										<td>{$l['voyage']}</td>
										<td>{$l['unpackagingplace']}</td>
										<td>{$l['blno']}</td>
										<td>
										   <a href="__CONTROLLER__/documentByTicketMsg/blno/{$l['blno']}/plan_id/{$l['id']}">查看</a>
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