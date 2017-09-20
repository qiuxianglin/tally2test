<head>
<title>CFS装箱_查看作业详情</title>
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

<body>
	<div id="wapper">
		<div class="right">
			<div class="right_top" style="background: none; border: 0;">
				<div class="right_l" style="font-size: 16px;">
					当前位置：<a href="__MODULE__/CfsInstruction/index">CFS装箱</a>&nbsp;&gt;&nbsp;查看作业详情
				</div>
			</div>

			<div class="right_t">
				<div class="amsg">
					<p>
						箱号：{$ctnMsg['ctnno']}&nbsp;&nbsp;&nbsp;
						空箱照片：	<?php
						$n = count($emptylist);
						if($n>0)
						{
							for($i=0;$i<$n;$i++)
							{
							    echo '<a href=".'.IMAGE_CFS_EMPTY.$emptylist[$i] ['empty_img'].'" class="firstebox" rel="empty">';
								if($i == 0)
								{
								   echo '查看空箱照片';
								}
								echo '</a>';
							}
						}
						?>&nbsp;&nbsp;&nbsp;
						<?php
						if ($msg ['halfclose_door_picture']) 
                        {
							echo '（<a href=".'.IMAGE_CFS_HALFCLOSEDOOR.$msg ['halfclose_door_picture'].'" class="firstebox">查看半关门照片</a>）';
						}
						?>&nbsp;&nbsp;&nbsp;
						<?php
						if ($msg ['close_door_picture']) 
                        {
							echo '（<a href=".'.IMAGE_CFS_CLOSEDOOR.$msg ['close_door_picture'].'" class="firstebox">查看全关门照片</a>）';
						}
						?>
					</p>
					<p>
						作业详情审核状态：
						<if condition="$msg['operation_examine'] eq 1">
							未审核
						<elseif condition="$msg['operation_examine'] eq 2" />
							审核通过
						<elseif condition="$msg['operation_examine'] eq 3" />
							审核未通过
						</if>&nbsp;&nbsp;&nbsp;
						铅封号：{$msg['sealno']}&nbsp;&nbsp;&nbsp;
						临时铅封号：{$msg['tmp_sealno']}&nbsp;&nbsp;&nbsp;
						空箱重量：{$msg['empty_weight']}&nbsp;kg&nbsp;&nbsp;
						货物重量：{$msg['cargo_weight']}&nbsp;kg&nbsp;&nbsp;
						铅封照片：<?php 
						if($msg['seal_picture']!='')
						{
							echo '<a href=".'.IMAGE_CFS_SEAL.$msg['seal_picture'].'" class="firstebox">查看铅封照片</a>';
						}else {
							echo '暂无';
						}
						?>&nbsp;&nbsp;&nbsp;
					</p>
				</div>

				<div class="row" style="text-align: center">
					<div class="col-xs-12">
						<div>
							<table class="table"
								style="margin-left: 35px; margin-top: 10px; width: 950px">
								<thead>
									<tr>
										<th>关序号</th>
										<th>货物件数</th>
										<th>残损件数</th>
										<th>货残损照片</th>
										<th>理货员</th>
<!-- 										<th>操作</th> -->
									</tr>
								</thead>
								<tbody>
									<volist name="levelList" id="l">
									<tr>
										<td>{$l['level_num']}</td>
										<td>{$l['num']}&nbsp;&nbsp;<?php
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
										<td>{$l['damage_num']}</td>
										<td>
												<?php
												$n = count ( $l ['cargo_damage_img'] );
												if ($n > 0) {
													echo '（';
													for($i = 0; $i < $n; $i ++) {
														echo '<a href=".'.IMAGE_CFS_CDAMAGE. $l ['cargo_damage_img'] [$i] ['img'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
														if ($i == 0) {
															echo '查看货残损照片';
														}
														echo '</a>';
													}
													echo '）';
												}else{
													echo '暂无';
												}
												?>
										</td>
										<td>{$l['user_name']}</td>
<!-- 										<td> 
										   <a href="__MODULE__/CfsOperationLevel/edit/id/{$vo['operation_id']}/level_id/{$vo['levelpictureid']}" style="background-color: rgb(51, 152, 219) ! important; border-color: rgb(213, 213, 213); color: rgb(255, 255, 255); font-size: 16px; text-align: center; padding: 3px 15px;" class="box">修改</a>
 										</td>  -->
									</tr>
									</volist>
								</tbody>
							</table>
							<div class="pages">{$page}</div>
						</div>
					</div>
				</div>
				<if condition="($ctnMsg['status'] eq 2) and ($msg['operation_examine'] neq 2)">
					<div style="margin-left:700px;font-size:15px;width:300px;height:50px;line-height:50px;">
						审核作业情况：
						<a onclick="return confirm('你确认要审核通过该作业信息吗？');" href="__CONTROLLER__/operation_examine/operation_id/{$msg['id']}/ctn_id/{$ctnMsg['id']}/operation_examine/2" style="background-color: #f1691e; border-color: rgb(213, 213, 213); color: rgb(255, 255, 255); font-size: 16px; text-align: center; padding: 3px 15px;">通过</a>
					</div>
				</if>
			</div>
		</div>
	</div>
</body>
