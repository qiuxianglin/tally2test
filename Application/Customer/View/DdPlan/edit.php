<head>
<title>门到门拆箱_预报计划详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
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
					当前位置：<a href="__MODULE__/DdSearch/index">查询统计</a>&nbsp;&gt;&nbsp;<a href="__CONTROLLER__/index">门到门拆箱预报计划</a>&nbsp;&gt;&nbsp;预报计划详情
				</div>
			</div>

			<div class="right_t" style="text-align: center;">
					<table style="margin: 0 auto; width: 820px">
						<tr>
							<td height="36" width="110" align="right" valign="middle">委托编号：</td>
							<td>
								<input type="text" class="article" value="{$msg['orderid']}" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">委托日期：</td>
							<td>
								<input type="text" class="article" value="{$msg['orderdate']}" readonly>
							</td>

							<td height="36" width="110" align="right" valign="middle">船名：</td>
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
							<td height="36" width="110" align="right" valign="middle">运输条款：</td>
							<td>
								<input type="text" class="article" value="{$msg['transit']}" readonly>
							</td>	
							<td height="36" width="110" align="right" valign="middle">拆箱方式：</td>
							<td>
								<input type="text" class="article" value="{$msg['operating_type_zh']}" readonly>
							</td>
							<td height="36" width="110" align="right" valign="middle">拆箱地点：</td>
							<td>
								<input type="text" class="article" value="{$msg['unpackagingplace']}" readonly>
							</td>
						</tr>
						
						<tr>
							<td height="36" width="110" align="right" valign="top">备注：</td>
							<td colspan="5">
								<textarea class="article" style="width: 690px; height: 100px" readonly>{$msg['note']}</textarea>
							</td>
						</tr>

					</table>
				</form>

				<div style="clear: both; margin-top: 10px"></div>

				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width:850px">
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
									</tr>
									</volist>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div style="clear: both; margin-bottom: 10px"></div>
				<div style="clear: both; margin-top: 10px"></div>
				<div class="row">
					<div class="col-xs-12">
						<div>
							<table class="table" style="margin: 0 auto; text-align: center;width:850px">
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
										<th>危险品等级</th>
										<th>联合国编号</th>
										<th>箱状态</th>
									</tr>
								</thead>
								<tbody>
									<?php $n=0;?>
									<volist name="plancontainerlist" id="c">
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
										<td>{$c['classes']}</td>
										<td>{$c['undgno']}</td>
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