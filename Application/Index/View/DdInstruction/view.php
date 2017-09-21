<head>
<title>拆箱系统_指令详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/nff.css" />
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/box.js"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/nff.js"></script>
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
</script>
<style>
#wapper,.right,.right_t {
	width: 1000px
}

.right_t table td .article {
	width: 150px
}
</style>
</head>
<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__CONTROLLER__/index">拆箱系统</a>&nbsp;&gt;&nbsp;指令详情
				</div>
			</div>
			
			<div class="right_t" style="text-align: center;">
			
			<div class="row" style="width: 900px; margin: 0 auto">
					<div class="rowh">
						<span>派工情况</span>
						<?php
						if($instruction_msg['status']=='0')
						{
							echo '<a style="float: right" id="pg" class="box" href="__MODULE__/DdDispatch/add/instruction_id/'.$instruction_msg['id'].'">新增派工</a>';
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
									    <td>{$instruction_msg['date']}</td>
									    <td>{$instruction_status_d[$instruction_msg['status']]}</td>
										<td>{$dispatch_detail['shift_id']}</td>
										<td>{$instruction_msg['parent_department_name']}-{$instruction_msg['department_name']}</td>
										<td>{$dispatch_detail['chieftally_name']}</td>
										<td>{$dispatch_detail['dispatch_time']}</td>
										<td>
										  <?php 
										  if($instruction_msg['status']!='0')
										  {
										  	foreach ($dispatch_detail['detail'] as $d)
										  	{
										  		echo $d['user_name'].'&nbsp;&nbsp;';
										  	}
										  }
										  ?>
										</td>
										<td><?php
										if ($instruction_msg['status']!='0' and $instruction_msg['status']!='2')
										{
											echo '<a class="box" href="__MODULE__/DdDispatch/edit/instruction_id/'.$instruction_msg['id'].'/dispatch_id/'.$dispatch_detail['id'].'">修改派工</a>&nbsp;|&nbsp;<a href="__MODULE__/DdDispatch/cancel/instruction_id/'.$instruction_msg['id'].'/dispatch_id/'.$dispatch_detail['id'].'">取消派工</a>';
										}
										?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
					<form action="__ACTION__" method="post">
					<input type="hidden" name="plan_id" value="{$plan_id}"/>
					<table style="margin: 0 auto; width: 900px">
						<tr>
							<td height="36" width="110" align="right" valign="middle">委托编号：</td>
							<td>
								<input type="text" class="article" value="{$msg['orderid']}" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">委托日期：</td>
							<td>
								<input type="text" class="article" value="{$msg['orderdate']}" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">中文船名：</td>
							<td>
								<input type="text" class="article" value="{$msg['vslname']}" readonly>
							</td>
						</tr>

						<tr>
							<td height="36" width="110" align="right" valign="middle">航次：</td>
							<td>
								<input type="text" class="article" value="{$msg['voyage']}" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">申报公司代码：</td>
							<td>
								<input type="text" class="article" value="{$msg['applycode']}" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">申报公司名称：</td>
							<td>
								<input type="text" class="article" value="{$msg['applyname']}" readonly>
							</td>
						</tr>

						<tr>
							<td height="36" width="110" align="right" valign="middle">金额：</td>
							<td>
								<input type="text" class="article" value="{$msg['amount']}" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">结算方式：</td>
							<td>
							  <input type="text" class="article" value="<?php echo $customer_paytype_d[$msg ['paytype']];?>" readonly>
							</td>

						    <td height="36" width="110" align="right" valign="middle">收款标识：</td>
							<td>
							    <?php 
							    if($msg['rcvflag']=='1')
							    {
							    	echo '<input type="text" class="article" value="已收款" readonly>';
							    }else {
							    	echo '<input type="text" class="article" value="未收款" readonly>';
							    }
							    ?>
							</td>

						</tr>

						<tr>
						    <td height="36" width="110" align="right" valign="middle"><span style="color:red">*</span>拆箱地点：</td>
							<td>
								<input type="text" class="article" name="location_name"
								id="location_name" required="required" autocomplete="off"
								style="text-transform: uppercase;" value="{$msg['unpackagingplace']}"/>
							</td>
							
							<td height="36" width="110" align="right" valign="middle">拆箱方式：</td>
							<td>
								<input type="text" class="article" value="{$msg['operating_type_zh']}" readonly>
							</td>
							
							<td height="36" width="110" align="right" valign="middle">业务系统：</td>
							<td>
								<input type="text" class="article" 
								<?php if($msg['business'] == 'cfs'){echo "value='CFS拆箱'";}else{echo "value='门到门拆箱'";}?>
								 readonly>
							</td>
						</tr>
						<tr>
							<td height="36" width="110" align="right" valign="middle">运输条款：</td>
							<td>
								<input type="text" class="article" value="{$msg['transit']}" readonly>
							</td>
						</tr>
						
						<tr>
							<td height="36" width="110" align="right" valign="top">备注：</td>
							<td colspan="5">
								<textarea class="article" style="width: 750px; height: 100px" readonly>{$msg['note']}</textarea>
							</td>
						</tr>
						<tr style="text-align: right;">
							<td colspan="6"><input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;
								<input type="reset" class="qr" value="重&nbsp;置" /></td>
						</tr>
					</table>
					</form>
				 <div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width:900px">
								<thead>
									<tr>
										<th>提单号</th>
										<th>付费方名称</th>
										<th>货名</th>
										<th>包装</th>
										<th>件数</th>
										<th>标志</th>
										<th>收货人</th>
										<th>危险等级</th>
										<th>联合国编号</th>
										<th>联系人</th>
										<th>联系方式</th>
									</tr>
								</thead>
								<tbody>
									<volist name="cargolist" id="c">
									<tr>
										<td>{$c['blno']}</td>
										<td>{$c['payman']}</td>
										<td>{$c['cargoname']}</td>
										<td>{$c['package']}</td>
										<td>{$c['numbersofpackages']}</td>
										<td>{$c['mark']}</td>
										<td>{$c['consignee']}</td>
										<td>{$c['classes']}</td>
										<td>{$c['undgno']}</td>
										<td>{$c['contactuser']}</td>
										<td>{$c['contact']}</td>
									</tr>
									</volist>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div style="clear: both; margin-bottom: 20px"></div>

				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table"
								style="margin: 0 auto; text-align: center; width: 900px">
								<thead>
									<tr>
										<th>序号</th>
										<th>箱号</th>
										<th>箱尺寸</th>
										<th>箱型</th>
										<th>铅封号</th>
										<th>件数</th>
										<th>重量</th>
										<th>体积</th>
										<th>集装箱状态</th>
										<th>审核状态</th>
										<th>箱状态</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php $n=0;?>
									<volist name="msg['ctns']" id="c">
									<tr>
									<?php $n++;?>
									    <td>{$n}</td>
										<td>{$c['ctnno']}</td>
										<td>{$c['ctnsize']}</td>
										<td>{$c['ctntype']}</td>
										<td>{$c['sealno']}</td>
										<td>{$c['numbersofpackages']}</td>
										<td>{$c['weight']}</td>
										<td>{$c['volume']}</td>
										<td>
										<?php 
										if($c['flflag']=='F')
										{
											echo '整箱';
										}else {
											echo '拼箱';
										}
										?>
										</td>
										<td>
										<?php
										switch ($c ['operation_examine']) {
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
										?></td>
										<td>
										<?php 
										switch ($c['status'])
										{
											case '0':
												echo '未作业';
												break;
											case '1':
												echo '工作中';
												break;
											case '2':
												echo '工作完成';
												break;
											case '-1':
												echo '箱残损';
												break;
										}
										?>
										</td>
										<td><a href="__MODULE__/DdOperation/index/ctn_id/{$c['id']}">查看详情</a></td>
									</tr>
									</volist>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div style="clear: both; margin-bottom: 10px"></div>
				
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
})
</script>