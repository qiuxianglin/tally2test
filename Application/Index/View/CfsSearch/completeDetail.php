<head>
<title>CFS装箱_完成作业详情</title>
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
					当前位置：<a href="__MODULE__/Search/index">查询统计</a>&nbsp;&gt;&nbsp;<a href="__MODULE__/CfsSearch/complete">完成作业查询</a>&nbsp;&gt;&nbsp;完成作业详情
				</div>
			</div>

			<div class="right_t">
				<div class="amsg">
					<p>箱号：{$msg['ctnno']}
						<?php
						$n = count($emptylist);
						if($n>0)
						{
							for($i=0;$i<$n;$i++)
							{
							    echo '<a href=".'.IMAGE_CFS_EMPTY.$emptylist[$i] ['empty_img'].'" class="firstebox" rel="empty">';
								if($i == 0)
								{
								   echo '( 查看空箱照片 )';
								}
								echo '</a>';
							}
						}
						?>
						&nbsp;&nbsp;&nbsp;&nbsp; 铅封号：{$msg['sealno']}
						<?php
						if ($operationMsg ['seal_picture']) 
                        {
							echo '（<a href=".'.IMAGE_CFS_SEAL.$operationMsg ['seal_picture'].'" class="firstebox">查看铅封照片</a>）';
						}
						?>&nbsp;&nbsp;&nbsp;&nbsp; 
						<?php
						if ($operationMsg ['halfclose_door_picture']) 
                        {
							echo '（<a href=".'.IMAGE_CFS_HALFCLOSEDOOR.$operationMsg ['halfclose_door_picture'].'" class="firstebox">查看半关门照片</a>）';
						}
						?>
						&nbsp;&nbsp;&nbsp;&nbsp; 
						<?php
						if ($operationMsg ['close_door_picture']) 
                        {
							echo '（<a href=".'.IMAGE_CFS_CLOSEDOOR.$operationMsg ['close_door_picture'].'" class="firstebox">查看半关门照片</a>）';
						}
						?>
					</p>
					<p>
					         货物件数：{$msg['total_ticket']}&nbsp;&nbsp;&nbsp;
						残损件数：{$msg['damage_num']}&nbsp;&nbsp;&nbsp;
						开始时间：{$operationMsg['begin_time']}&nbsp;&nbsp;&nbsp;&nbsp;
						结束时间：{$msg['createtime']}&nbsp;&nbsp;&nbsp;&nbsp;</p>
				</div>

				<div class="row" style="text-align: center">
					<div class="col-xs-12">
						<table class="table"
							style="margin-left: 35px; margin-top: 10px; width: 950px">
							<thead>
								<tr>
									<th>关序号</th>
									<th>提单号</th>
									<th>货物件数</th>
									<th>残损件数</th>
									<th>理货员</th>
								</tr>
							</thead>
							<tbody>
								<volist name="levellist" id="l">
								<tr>
									<td>{$l['level_num']}</td>
									<td>{$l['blno']}</td>
									<td>{$l['num']}&nbsp;&nbsp;
									<?php
										$n = count ( $l ['level_cargo_img'] );
										if ($n > 0) {
											echo '（';
											for($i = 0; $i < $n; $i ++) {
												echo '<a href=".'.IMAGE_CFS_CARGO. $l ['level_cargo_img'] [$i] ['level_img'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
												if ($i == 0) {
													echo '查看照片';
												}
												echo '</a>';
											}
											echo '）';
										}else{
											echo '暂无';
										} 
										?></td>
									<td>{$l['damage_num']}
									<?php
									$n = count ( $l ['cargo_damage_img'] );
									if ($n > 0) {
										echo '（';
										for($i = 0; $i < $n; $i ++) {
											echo '<a href=".'.IMAGE_CFS_CDAMAGE.$l ['cargo_damage_img'] [$i] ['img'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
											if ($i == 0) {
												echo '查看残损照片';
											}
											echo '</a>';
										}
										echo '）';
									}
									?></td>
									<td>{$l['user_name']}</td>
								</tr>
								</volist>
							</tbody>
						</table>

						<div class="remark">
							<p>批注：{$msg['remark']}</p>
						</div>
						
						
						<div class="clear" style="width: 950px;"></div>

						<div style="text-align: center; width: 950px;">
							<font style="font-size: 16px;">修改记录</font>
						</div>

						<table class="table"
							style="margin-left: 35px; margin-top: 10px; width: 950px">
							<thead>
								<tr>
									<th>修改目标</th>
									<th>原内容</th>
									<th>修改后内容</th>
									<th>修改人</th>
									<th>修改时间</th>
									<th>修改原因</th>
								</tr>
							</thead>
							<tbody>
								<volist name="amendlist" id="vo">
								<tr>
									<td>
									<?php
									switch ($vo ['field_name']) {
										case 'sealno' :
											echo '铅封号';
											break;
										case 'num' :
											echo '货物件数';
											break;
										case 'damage_num' :
											echo '残损件数';
											break;
										case 'seal_picture' :
											echo '铅封照片';
											break;
									}
									?>
									</td>
									<td>
									<?php
									if (strpos ( $vo ['field_old_value'], '/AppUpload' )) {
										echo '<a href="' . $vo ['field_old_value'] . '" class="firstebox">查看原照片</a>';
									} else {
										echo $vo ['field_old_value'];
									}
									?>
									</td>
									<td>
									<?php
									if (strpos ( $vo ['field_new_value'], '/AppUpload' )) {
										echo '<a href="' . $vo ['field_new_value'] . '" class="firstebox">查看新照片</a>';
									} else {
										echo $vo ['field_new_value'];
									}
									?>
									</td>
									<td>{$vo['user_name']}</td>
									<td>{$vo['date']}</td>
									<td>{$vo['remark']}</td>
								</tr>
								</volist>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
