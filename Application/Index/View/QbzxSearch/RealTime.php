<head>
<title>起驳装箱_实时作业查询</title>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css"
	type="text/css" />
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
					当前位置：<a href="__MODULE__/Search/index">查询统计</a>&nbsp;&gt;&nbsp;实时作业查询
				</div>
			</div>

			<div class="right_list2">
				<div class="addrule">
					<form class="select" action="__ACTION__" method="get">
					<input type="hidden" name="p" value="1">
					<input type="hidden" name="c" value="QbzxSearch">
					<input type="hidden" name="a" value="RealTime">
						船名： 
						<!--<select class="input1" name="ship_name" style="width: 135px">
							<option value="">--默认全部--</option>
							<volist name="shiplist" id="sl">
							<option value="{$sl['ship_name']}">{$sl['ship_name']}</option>
							</volist>
						</select> -->
						<input type="text" name="ship_name" id='ship_name' class="input1">
						&nbsp;&nbsp;&nbsp;&nbsp; 
						航次：<input type="text" name="voyage" class="input1"> &nbsp;&nbsp;&nbsp;&nbsp; 
						目的港：<input type="text" name="port" id='port' class="input1"> &nbsp;&nbsp;&nbsp;&nbsp;
						作业地点：
						<!-- <select class="input1" name="location_name" style="width: 135px">
							<option value="">--默认全部--</option>
							<volist name="locationlist" id="l">
							<option value="{$l['location_name']}">{$l['location_name']}</option>
							</volist>
						</select>-->
						<input type="text" name="location_name" id='location_name' class="input1">
						&nbsp;&nbsp;&nbsp;&nbsp;
						提单号：<input type="text" name="billno" class="input1"> &nbsp;&nbsp;&nbsp;&nbsp;<br/><br/>
						箱号：<input type="text" name="ctnno" class="input1"> &nbsp;&nbsp;&nbsp;&nbsp;
						状态：
						<select name="cstatus" id="" class="input1">
							<option value="">--默认全部--</option>
							<option value="1">工作中</option>
							<option value="2">已铅封</option>
						</select>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" value="查询" style="background-color: #3398db !important; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px;">
					</form>
				</div>
				<div style="clear: both;"></div>
				<div class="row" style="margin-top: 10px">
					<div class="col-xs-12">
						<table class="table">
							<thead>
								<tr>
									<th>船名</th>
									<th>航次</th>
									<th>作业地点</th>
									<!-- <th>提单号</th> -->
									<th>箱号</th>
									<th>箱型尺寸</th>
									<th>关数</th>
									<th>件数</th>
									<th>残损数</th>
									<th>操作人</th>
									<th>作业开始时间</th>
									<th>最新操作时间</th>
									<th>状态</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<volist name="list" id="l">
								<tr>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['ship_name']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['voyage']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['location_name']}</td>
									<!-- <td <?php
									//if ($l ['red'] == 1) {
										//echo 'style="color:red;"';
									//}
									?> >{$l['billno']}</td> -->
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['ctnno']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['ctn_type_code']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['level_num']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['cargo_number']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['damage_num']}</td>
									<td <?php
									if ($l ['red'] == 1) {
										echo 'style="color:red;"';
									}
									?> >{$l['user_name']}</td>
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
									<if condition="$l['status']=='1'">
										作业中
									<elseif condition="$l['status']=='2'" />
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
									   <a href="__CONTROLLER__/RealTimeDetail/ctn_id/{$l['id']}">查看</a>
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
	$("#port").bigAutocomplete({
		width:135,
		data:[
			<?php
			foreach ($portlist as $v)
			{
				echo '{title:"'.$v['code'].'",show:"'. $v ['name'] . '"},';
				echo '{title:"' . $v ['name'] . '",show:"' . $v ['name'] . '"},';
			}
			?>
		],
		callback:function(data){

		}
	});
	$("#ship_name").bigAutocomplete({
		width:135,
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

		}
	});
	$("#location_name").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($locationlist as $l)
			{
				echo '{title:"'.$l['location_code'].'",show:"'.$l['location_name'].'"},';
				echo '{title:"'.$l['location_name'].'",show:"'.$l['location_name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);	
		}
	});
})
</script>