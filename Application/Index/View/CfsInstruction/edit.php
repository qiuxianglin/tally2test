<head>
<title>CFS装箱_添加指令</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/nff.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
</script>
<style>
#wapper, .right, .right_t {
	width: 1000px
}
</style>
</head>
<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/CfsInstruction/index">CFS装箱</a>&nbsp;&gt;&nbsp;<a
						href="__MODULE__/CfsInstruction/">指令</a>&nbsp;&gt;&nbsp;指令详情
				</div>
			</div>
			<div class="right_t" style="text-align: center;">
				<form action="__ACTION__" method="post">
					<table border="" style="margin: 0 auto;">
						<tr>
							<td height="36" align="right" valign="middle">中文船名：</td>
							<td><input type="text" class="article" name="shipname" value="{$msg['ship_name']}" id="ship_name" required="required" autocomplete="off" style="text-transform: uppercase;"
								required="required" /></td>

							<td height="36" align="right" valign="middle">航次：</td>
							<td><input type="text" class="article" name="voyage" value="{$msg['voyage']}"
								required="required" /></td>

							<td height="36" align="right" valign="middle">作业地点：</td>
							<td>
							   <input type="text" class="article" name="location_name" value="{$msg['location_name']}" id="location_name" required="required" autocomplete="off" style="text-transform: uppercase;" />
							</td>
						</tr>
						<tr>
							<td height="36" align="right" valign="middle">委托单位：</td>
							<td>
							   <input type="text" class="article" name="entrust_company" value="{$msg['entrust_company_zh']}" id="entrust_company" autocomplete="off" style="text-transform: uppercase;" />
							</td>
						
							<td height="36" align="right" valign="middle">所属工班组：</td>
							<td>
							   <input type="text" class="article" name="department_id" value="{$dMsg['parent_department_name']}-{$dMsg['department_name']}" readonly="readonly"/>
							</td>

							<td height="36" align="right" valign="middle">日期：</td>
							<td>
							   <input type="text" class="article" name="date" value="{$msg['date']}" readonly="readonly"/>
							</td>
						</tr>
						
						<tr>
							<td height="36" align="right" valign="top">装箱方式：</td>
							<td><select name="operation_type" class="article"
								required="required">
									<option value="0" <?php if($msg['operation_type'] == '0'){echo 'selected';}?>>人工</option>
									<option value="1" <?php if($msg['operation_type'] == '1'){echo 'selected';}?>>机械</option>
							</select></td>
						</tr>

						<tr style="text-align: right;">
							<td colspan="6"><input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
								<input type="hidden" name="id" value="{$msg['id']}">
								<input type="reset" class="qr" value="重&nbsp;置" /></td>
						</tr>
					</table>
				</form>
				<div style="clear: both; margin-top: 10px"></div>
				<div class="clear" style="width:900px;"></div>
				<div class="row" style="width: 900px; margin: 0 auto">
					<div class="rowh">
						<span>派工情况</span>
						<?php
						if($msg['status']=='0')
						{
							echo '<a style="float: right" id="pg" class="box" href="__MODULE__/CfsDispatch/add/instruction_id/'.$msg['id'].'">新增派工</a>';
						}
						?>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width: 900px;">
								<thead>
									<tr>
									    <th>指令日期</th>
									    <th>指令状态</th>
										<th>工班号</th>
										<th>作业班组</th>
										<th>理货长</th>
										<th>派工时间</th>
										<th>理货员</th>
										<th>派工操作</th>
									</tr>
								</thead>
								<tbody>
									<tr>
									    <td>{$msg['date']}</td>
									    <td>{$msg['status_zh']}</td>
										<td>{$dispatch_detail['shift_id']}</td>
										<td>{$msg['department']}</td>
										<td>{$dispatch_detail['chieftally_name']}</td>
										<td>{$dispatch_detail['dispatch_time']}</td>
										<td>
										  <?php 
										  if($msg['status']!='0')
										  {
										  	foreach ($dispatch_detail['detail'] as $d)
										  	{
										  		echo $d['user_name'].'&nbsp;&nbsp;';
										  	}
										  }
										  ?>
										</td>
										<td><?php
										if ($msg['status'] == '1')
										{
											echo '<a class="box" href="__MODULE__/CfsDispatch/edit/instruction_id/'.$msg['id'].'/dispatch_id/'.$dispatch_detail['id'].'">修改派工</a>&nbsp;|&nbsp;<a href="__MODULE__/CfsDispatch/del/instruction_id/'.$msg['id'].'/dispatch_id/'.$dispatch_detail['id'].'">取消派工</a>';
										}else if($msg['status'] == '2'){
											echo '该作业已完成';
										}
										?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>				
					</div>
				<div style="clear: both; margin-top: 10px"></div>
				<div class="clear" style="width:900px;"></div>
				<div class="row" style="width: 900px; margin: 0 auto">
					<div class="rowh">
						<span>配货情况</span>
						<a style="float: right" id="pg" class="box" href="__MODULE__/CfsInstructionCargo/add/instruction_id/{$id}">新增配货</a>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width:900px;">
								<thead>
									<tr>
										<th>提单号</th>
										<th>目的港</th>
										<th>货名</th>
										<th>件数</th>
										<th>包装</th>
										<th>标志</th>
										<th>总重量</th>
										<th>危险等级</th>
										<th>总体积</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="cargolist" id="c">
									<tr>
										<td>{$c['blno']}</td>
										<td>
										<?php 
										  if(!empty($c['port_id']))
										  {
										  	$port = new \Common\Model\PortModel();
										  	$port_id = $c['port_id'];
										  	$port_name = $port->getPortMsg($port_id);
										  	echo $port_name["name"];
										  }
										?>
										</td>
										<td>{$c['name']}</td>
										<td>{$c['number']}</td>
										<td>{$c['package']}</td>
										<td>{$c['mark']}</td>
										<td>{$c['totalweight']}</td>
										<td>{$c['dangerlevel']}</td>
										<td>{$c['totalvolume']}</td>
										<td>
										   <a class="box" href="__MODULE__/CfsInstructionCargo/edit/id/{$c['id']}">查看 | 修改</a>
										   | <a onclick="return confirm('删除是不可恢复的，你确认要删除该配货吗？');" href="__MODULE__/CfsInstructionCargo/del/id/{$c['id']}">删除</a>
										</td>
									</tr>
									</volist>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div style="clear: both; margin-top: 10px"></div>
				<div class="clear" style="width:900px;"></div>
				<div class="row" style="width: 900px; margin: 0 auto">
					<div class="rowh">
						<span>配箱情况</span>
						<a style="float: right" id="pg" class="box" href="__MODULE__/CfsInstructionContainer/add/instruction_id/{$id}">新增配箱</a>
					</div>
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width:900px;">
								<thead>
									<tr>
										<th>序号</th>
										<th>箱号</th>
										<th>箱型尺寸</th>
										<th>箱主</th>
										<th>拼箱状态</th>
										<th>作业状态</th>
										<th>审核状态</th>
										<th>预配件数</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="containerlist" id="cl">
									<tr>
									<?php $n++;?>
									    <td>{$n}</td>
										<td>{$cl['ctnno']}</td>
										<td>{$cl['ctn_size']}</td>
										<td>{$cl['cmaster']}</td>
										<td><?php if($cl['lcl'] == 'F'){
										echo $cl['lcl'] = '整箱';
										}else{
										echo $cl['lcl'] = '拼箱';
										}?></td>
										<td><if condition="$cl['status'] eq 0">未作业<elseif
												condition="$cl['status'] eq 1" />工作中<elseif
												condition="$cl['status'] eq 2" />已铅封<elseif
												condition="$cl['status'] eq -1" />箱残损<else/>未知</if>
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
										<td>{$cl['pre_number']}</td>
										<td>
										   <if condition="$cl['status'] eq 0"> 
										     <a class="box" href="__MODULE__/CfsInstructionContainer/edit/id/{$cl['id']}">查看 | 修改 |</a>
										   </if>
										    <?php
										    //判断是否可以删除配箱
										    //箱状态为未开始，并且没有作业记录的箱可以删除
										    if($cl['status']=='0'  or $cl['status'] == '-1') 
										    {
										    	echo ' <a onclick="return confirm(\'删除是不可恢复的，你确认要删除吗？\');" href="__MODULE__/CfsInstructionContainer/del/id/'.$cl['id'].'">删除</a>';
										    }
										    if($cl['status'] == '1' or $cl['status'] == '2' ){
										    	echo ' <a  href="__MODULE__/CfsOperationContainer/detail/ctn_id/'.$cl['id'].'">查看作业详情</a>';
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

	$("#ship_name").bigAutocomplete({
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

	$("#cargo_agent").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($cargoAgentList as $cl)
			{
				echo '{title:"'.$cl['code'].'",show:"'.$cl['name'].'"},';
				echo '{title:"'.$cl['name'].'",show:"'.$cl['name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);
		}
	});
	$("#entrust_company").bigAutocomplete({
		width:160,
		data:[
			<?php 
			foreach ($customerlist as $c)
			{
				echo '{title:"'.$c['customer_code'].'",show:"'.$c['customer_name'].'"},';
				echo '{title:"'.$c['customer_name'].'",show:"'.$c['customer_name'].'"},';
			}
			?>
		],
		callback:function(data){
			//alert(data.title);
		}
	});
})
</script>
