<head>
<title>起驳装箱_实时作业详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.firstebox.pack.js"></script>
<style type="text/css" media="all">
@import "__PUBLIC__/css/firstebox.css";
#wapper, .right, .right_t, .right_list2, .row table {
	width: 1000px
}
</style>
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
})
</script>
</head>

	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0;">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/QbzxSearch/index">查询统计</a>&nbsp;&gt;&nbsp;实时作业详情
				</div>
			</div>

			<div class="right_t">
				<div class="amsg">
					<p>船名：{$instructionMsg['ship_name']}&nbsp;&nbsp;&nbsp;&nbsp;
					       航次：{$instructionMsg['voyage']}&nbsp;&nbsp;&nbsp;&nbsp;
					       装箱地点：{$instructionMsg['location_name']}&nbsp;&nbsp;&nbsp;&nbsp;
<!-- 					       提单号：{$instructionMsg['billno']}&nbsp;&nbsp;&nbsp;&nbsp; -->
<!-- 					       包装：{$instructionMsg['package']}&nbsp;&nbsp;&nbsp;&nbsp; -->
<!-- 					       标志：{$instructionMsg['mark']}&nbsp;&nbsp;&nbsp;&nbsp; -->
					</p>
					<p>箱号：{$msg['ctnno']}&nbsp;&nbsp;&nbsp;
					       箱型尺寸：{$msg['ctn_type_code']}&nbsp;&nbsp;&nbsp;
						<?php
						if ($operationMsg ['seal_picture']) 
                        {
							echo '（<a href=".'.$IMAGE_QBZX_SEAL.$operationMsg ['seal_picture'].'" class="firstebox">查看铅封照片</a>）';
						}
						?>
						<?php
						$n = count($emptylist);
						if($n>0)
						{
							echo '（';
							for($i=0;$i<$n;$i++)
							{
								echo '<a href=".'.$IMAGE_QBZX_EMPTY.$emptylist[$i]['empty_picture'].'" class="firstebox" rel="gallery">';
								if ($i == 0) {
									echo '查看空箱照片';
								}
								echo '</a>';
							}
							echo '）';
						}
						?>
					       开始时间：{$operationMsg['begin_time']}&nbsp;&nbsp;&nbsp;&nbsp;
						</p>
				</div>

				<div class="row" style="text-align: center">
					<div class="col-xs-12">
						<table class="table"
							style="margin-left: 35px; margin-top: 10px; width: 950px">
							<thead>
								<tr>
									<th>关序号</th>
									<th>提单号</th>
									<th>驳船</th>
									<th>包装</th>
									<th>标志</th>
									<th>货物件数</th>
									<th>残损件数</th>
									<th>理货员</th>
									<th>操作时间</th>
								</tr>
							</thead>
							<tbody>
								<volist name="levellist" id="l">
								<tr>
									<td>{$l['level_num']}</td>
									<td>{$instructionMsg['billno']}</td>
									<td>{$instructionMsg['ship_name']}</td>
									<td>{$instructionMsg['pack']}</td>
									<td>{$instructionMsg['mark']}</td>
									<td>{$l['cargo_number']}（<?php echo '<a href=".'.$IMAGE_QBZX_CARGO.$l['cargo_picture'].'"
											class="firstebox">查看照片</a>';?>）</td>
									<td>{$l['damage_num']}
									<?php
									$n = count ( $l ['damage_picture'] );
									if ($n > 0) {
										echo '（';
										for($i = 0; $i < $n; $i ++) {
											echo '<a href=".'.$IMAGE_QBZX_CDAMAGE.$l['damage_picture'][$i] ['damage_picture'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
											if ($i == 0) {
												echo '查看残损照片';
											}
											echo '</a>';
										}
										echo '）';
									}
									?></td>
									<td>{$l['user_name']}</td>
									<td>{$l['createtime']}</td>
								</tr>
								</volist>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
