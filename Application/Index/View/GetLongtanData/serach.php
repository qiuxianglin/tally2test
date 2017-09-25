<head>
<title>查询龙潭数据</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
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
					当前位置：<a href="__MODULE__/Search/index">查询统计</a>&nbsp;&gt;&nbsp;查询龙潭数据
				</div>
			</div>

			<div class="right_list2">
				<div class="addrule">
					<form class="select" action="__ACTION__" method="get">
					<input type="hidden" name="p" value="1">
					<input type="hidden" name="c" value="DdSearch">
					<input type="hidden" name="a" value="real_time">
						提单号： <input type="text" class="input1" name="blno" id="shipname" autocomplete="off" style="text-transform: uppercase;"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						箱号： <input type="text" class="input1" name="ctnno" id="shipname" autocomplete="off" style="text-transform: uppercase;"/>
						&nbsp;&nbsp;&nbsp;&nbsp; 
						船名：<input type="text" name="voyage" class="input1"> 
						&nbsp;&nbsp;&nbsp;&nbsp; 
						航次：<input type="text" name="voyage" class="input1">
						&nbsp;&nbsp;&nbsp;&nbsp; 
						<input type="submit" value="查询" style="background-color: #3398db !important; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px;">
					</form>
				</div>
				<div style="clear: both;"></div>
				<div class="row" style="margin-top: 10px">
					<div class="col-xs-12">
						<table class="table">
							<thead>
								<tr>
									<th>提单号</th>
									<th>进出标识</th>
									<th>箱号</th>
									<th>箱型尺寸高度</th>
									<th>服务类型</th>
									<th>箱重</th>
									<th>铅封</th>
									<th>船名 出口航次</th>
									<th>船名</th>
									<th>进口航次</th>
									<th>装货港</th>
									<th>货物代码</th>
									<th>件数</th>
									<th>重量</th>
									<th>体积</th>
									<th>装拆箱时间</th>
									<th>运输条款</th>
									<th>运输方式</th>
								</tr>
							</thead>
							<tbody>
								<volist name="list" id="l">
								<tr>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['vslname']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['voyage']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['unpackagingplace']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['ctnno']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['ctnsize']}{$l['ctntype']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['levelnum']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['cargonum']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['damage_num']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['begin_time']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['newtime']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >
									<if condition="$l['cstatus']=='1'">
										作业中
									<elseif condition="$l['cstatus']=='2'" />
										已铅封
									<elseif condition="$l['cstatus']=='4'" />
										已铅封
									</if>
									-
									<if condition="$l['operation_examine']=='1'">
										未审核
									<elseif condition="$l['operation_examine']=='2'" />
										已审核
									</if></td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >
									   <a href="__CONTROLLER__/realtimeDetail/ctn_id/{$l['id']}">查看</a>
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
	
<script>
$(function(){
	$("#shipname").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($shiplist as $s)
			{
				echo '{title:"'.$s['ship_code'].'",show:"'.$s['ship_name'].'"},';
				echo '{title:"'.$s['ship_name'].'",show:"'.$s['ship_name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
})
</script>