<head>
<title>起泊装箱_作业指令详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/admin/css/rule.css" />
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/admin/css/nff.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css"
	type="text/css" />
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
</script>
<style>
#wapper,.right,.right_t {
	width: 1000px
}
</style>
</head>

<body>
	<div id="wapper">
		<div class="right">

			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：
					<a href="__MODULE__/QbzxPlan/index">起驳装箱</a>
					&nbsp;&gt;&nbsp;
					<a href="__MODULE__/QbzxInstruction/index">作业指令</a>
					&nbsp;&gt;&nbsp;作业指令详情
				</div>
			</div>

			<div class="right_t">
				<p
					style="margin: 0 auto; width: 740px; min-height: 30px; line-height: 30px; font-size: 14px;">
					指令编号：{$msg['id']}
					&nbsp;&nbsp;&nbsp;&nbsp;指令日期：{$msg['ordertime']}
					&nbsp;&nbsp;&nbsp;&nbsp;船名：{$planMsg['ship_name']}
					&nbsp;&nbsp;&nbsp;&nbsp;航次：{$planMsg['voyage']}
					&nbsp;&nbsp;&nbsp;&nbsp;已配箱数：{$has_container_num}</p>
				<form action="__MODULE__/QbzxInstruction/edit/id/{$msg['iid']}" method="post">
					<table border="" style="margin: 0 auto;">
						<tr>
							<td width="70" align="right" valign="middle">作业场地：</td>
							<td>
								<input type="text" class="article" name="location_name"
									id="location_name" required="required" autocomplete="off"
									style="text-transform: uppercase; width: 90px;"
									value="{$msg['location_name']}" />
							</td>
							<td width="70" align="right" valign="middle">装箱方式：</td>
							<td>
								<select name="loadingtype" class="article" style="width: 62px;">
									<option value="1"
										<?php echo ($msg['loadingtype']=='1') ? 'selected' : '';?>
										>机械</option>
									<option value="0"
										<?php echo ($msg['loadingtype']=='0') ? 'selected' : '';?>
										>人工</option>
								</select>
							</td>
							<td>作业班组：{$dMsg['parent_department_name']}-{$dMsg['department_name']}</td>
							<td>
								<input type="submit" class="qr" value="修&nbsp;改" />
								&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
					</table>
				</form>

				<div class="clear"></div>

				<div class="row" style="width: 740px; margin: 0 auto">
					<div class="rowh">
						<span>派工情况</span>
						<?php if ($msg['status']=='0') echo '<a style="float: right" id="pg" class="box" href="__MODULE__/QbzxDispatch/add/instruction_id/'.$instruction_id.'">新增派工</a>';?>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;">
								<thead>
									<tr>
										<th>工班号</th>
										<th>理货长</th>
										<th>派工时间</th>
										<th>理货员</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>{$dispatch_detail['shift_id']}</td>
										<td>{$dispatch_detail['chieftally_name']}</td>
										<td>{$dispatch_detail['dispatch_time']}</td>
										<td>
										  <?php 
										  foreach ($dispatch_detail['detail'] as $d)
										  {
											 echo $d['user_name'].'&nbsp;&nbsp;';
										  }
										  ?>
										</td>
										<td>
										<?php
										if ($msg['status']!='0' and $msg['status']!='-1')
										{
											echo '<a class="box" href="__MODULE__/QbzxDispatch/edit/instruction_id/'.$instruction_id.'/dispatch_id/'.$dispatch_detail['id'].'">修改派工</a>
												| <a onclick="return confirm("你确认要取消派工吗？");" href="__MODULE__/QbzxDispatch/cancel/instruction_id/'.$instruction_id.'/dispatch_id/'.$dispatch_detail['id'].'">取消派工</a>';
										}
										/* <a onclick="return confirm("你确认要取消派工吗？");" href="__MODULE__/index/canceltaskpc/business/qbzx/insid/' . $instruction_id . '">取消派工</a> */
										?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="clear"></div>

				<div class="row" style="width: 740px; margin: 0 auto">
					<div class="rowh">
						<span>配箱情况</span>
						<a style="float: right" id="pg" class="box"
							href="__CONTROLLER__/add/instruction_id/{$instruction_id}">新增配箱</a>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;">
								<thead>
									<tr>
									    <th>序号</th>
										<th>箱号</th>
										<th>箱型尺寸</th>
										<th>箱主</th>
										<th>作业状态</th>
										<th>审核状态</th>
										<th>操作</th>
										<th>替换理货员</th>
									</tr>
								</thead>
								<tbody>
								<?php $n=0;?>
									<volist name="containerlist" id="cl">
									<tr>
									<?php $n++;?>
									    <td>{$n}</td>
										<td>{$cl['ctnno']}</td>
										<td>{$cl['ctn_type_code']}</td>
										<td>{$cl['cmaster']}</td>
										<td>
										<?php
										switch ($cl ['status']) {
											case '0' :
												echo '未作业';
												break;
											case '1' :
												echo '工作中';
												break;
											case '2' :
												echo '已铅封';
												break;
											case '-1' :
												echo '<span style="color: red; font-size: 14px;">箱残损</span>';
												break;
										}
										?>
										</td>
										<td>
										<?php
										switch ($cl ['operation_examine']) {
											case '1' :
												echo '未审核';
												break;
											case '2' :
												echo '通过';
												break;
											case '3' :
												echo '未通过';
												break;
											default:
												echo '---';
												break; 
										}
										?>
										</td>
										<td>
										    <?php
										    //判断是否可以删除配箱
										    //箱状态为未开始，并且没有作业记录的箱可以删除
										    if($cl['status']=='0')
										    {
										    	echo '<a class="box" href="__CONTROLLER__/edit/id/'.$cl['id'].'">查看 | 修改</a>| <a onclick="return confirm(\'删除是不可恢复的，你确认要删除吗？\');" href="__CONTROLLER__/del/id/'.$cl['id'].'">删除</a>';
										    }else {
										    	if($cl['status'] == '-1'){ 
                                                  echo '<a onclick="return confirm(\'删除是不可恢复的，你确认要删除吗？\');" href="__CONTROLLER__/del/id/'.$cl['id'].'">删除</a>';
                                                }else {
	                                                  echo '<a href="__MODULE__/QbzxOperation/index/ctn_id/'.$cl['id'].'">查看作业详情</a>';
                                                }
										    }
										    ?>
										</td>
										<td>
										    <?php 
										    if($cl['status']=='0' or $cl['status'] == '-1')
										    {
										    	echo '暂无理货员';
										    }else {
										    	echo '<a href="__MODULE__/QbzxOperation/replaceTallyClerk/instruction_id/'.$instruction_id.'/ctn_id/'.$cl['id'].'" class="box">替换理货员</a>';
										    }
										    ?>
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
<script>
$(function(){
	$("#location_name").bigAutocomplete({
		width:100,
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