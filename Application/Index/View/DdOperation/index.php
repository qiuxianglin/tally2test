<head>
<title>门到门拆箱_查看作业详情</title>
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
					当前位置：<a href="__MODULE__/DdInstruction/index">门到门拆箱</a>&nbsp;&gt;&nbsp;查看作业详情
				</div>
			</div>

			<div class="right_t">
				<div class="amsg">
					<p>
						箱号：{$ctnMsg['ctnno']}&nbsp;&nbsp;&nbsp;
						铅封号：{$ctnMsg['sealno']}&nbsp;&nbsp;&nbsp;
						<?php 
						if($msg['true_sealno']!='')
						{
							echo '实际铅封号：'.$msg['true_sealno'].'';
						}
						?>&nbsp;&nbsp;&nbsp;
						箱门照片：<?php 
						if($msg['door_picture']!='')
						{
							echo '<a href=".'.IMAGE_DD_DOOR.$msg['door_picture'].'" class="firstebox">查看箱门照片</a>';
						}else {
							echo '暂无';
						}
						?>&nbsp;&nbsp;&nbsp;
						铅封照片：<?php 
						if($msg['seal_picture']!='')
						{
							echo '<a href=".'.IMAGE_DD_SEAL.$msg['seal_picture'].'" class="firstebox">查看铅封照片</a>';
						}else {
							echo '暂无';
						}
						?>&nbsp;&nbsp;&nbsp;
						箱残损照片：<?php
							$n = count ( $msg['ctn_damage_img'] );
							if ($n > 0)
                            {
                            	echo '（';
                            	for($i = 0; $i < $n; $i ++)
                                {
                                	echo '<a href=".'.IMAGE_DD_DAMAGE. $msg['ctn_damage_img'] [$i] ['img'].'" class="firstebox" rel="ctn_damage_img">';
                                	if ($i == 0)
                                    {
                                    	echo '查看残损照片';
                                    }
                                    echo '</a>';
                                }
                                echo '）';
                             }else {
                             	echo '暂无';
                             }
						?>
					</p>
					<p>
					   <?php
						if($msg['damage_remark']!='')
						{
							echo '箱残损说明：<a href="javascript:;" onclick="alert(\''.$msg['damage_remark'].'\');">查看说明</a>&nbsp;&nbsp;&nbsp;';
						}
						?>
					     整体货物照片：<?php
						if($msg['cargo_picture']!='')
						{
							echo '<a href=".'.IMAGE_DD_CARGO.$msg['cargo_picture'].'" class="firstebox">查看整体货物照片</a>';
						}else {
							echo '暂无';
						}
						?>&nbsp;&nbsp;&nbsp;
						空箱照片：<?php 
						if($msg['empty_picture']!='')
						{
							echo '<a href=".'.IMAGE_DD_EMPTY.$msg['empty_picture'].'" class="firstebox">查看空箱照片</a>';
						}else {
							echo '暂无';
						}
						?>
					</p>
					<p>
					作业中造成的箱残损照片：<?php
							$n = count ( $msg['ctn_damage_after_img'] );
							if ($n > 0)
                            {
                            	echo '（';
                            	for($i = 0; $i < $n; $i ++)
                                {
                                	echo '<a href=".'.IMAGE_DD_DAMAGEAFTER. $msg['ctn_damage_after_img'] [$i] ['img'].'" class="firstebox" rel="ctn_damage_after_img">';
                                	if ($i == 0)
                                    {
                                    	echo '查看箱残损照片';
                                    }
                                    echo '</a>';
                                }
                                echo '）';
                             }else {
                             	echo '暂无';
                             }
						?>&nbsp;&nbsp;&nbsp;
						<?php
						if($msg['damage_after_remark']!='')
						{
							echo '作业中造成的箱残损说明：<a href="javascript:;" onclick="alert(\''.$msg['damage_after_remark'].'\');">点击查看说明</a>';
						}
						?>&nbsp;&nbsp;&nbsp;
						作业详情审核状态：
						<if condition="$msg['operation_examine'] eq 1">
							未审核
						<elseif condition="$msg['operation_examine'] eq 2" />
							审核通过
						<elseif condition="$msg['operation_examine'] eq 3" />
							审核未通过
						</if>
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
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="levelList" id="l">
									<tr>
										<td>{$l['level_num']}</td>
										<td>{$l['num']}
											<?php
												$n = count ( $l ['cargo_level_img'] );
												if ($n > 0) {
													echo '（';
													for($i = 0; $i < $n; $i ++) {
														echo '<a href=".'.IMAGE_DD_CARGO. $l ['cargo_level_img'] [$i] ['level_img'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
														if ($i == 0) {
															echo '查看货照片';
														}
														echo '</a>';
													}
													echo '）';
												}else{
													echo '暂无';
												}
												?>
										</td>
										<td>{$l['damage_num']}</td>
										<td>
												<?php
												$n = count ( $l ['cargo_damage_img'] );
												if ($n > 0) {
													echo '（';
													for($i = 0; $i < $n; $i ++) {
														echo '<a href=".'.IMAGE_DD_CDAMAGE. $l ['cargo_damage_img'] [$i] ['img'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
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
										<td><a
											href="__CONTROLLER__/editlevel/operation_id/{$l['operation_id']}/level_id/{$l['id']}"
											class="box">修改</a></td>
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
						<a onclick="return confirm('你确认要审核通过该作业信息吗？');" href="__CONTROLLER__/operation_examine/operation_id/{$msg['id']}/ctn_id/{$ctnMsg['id']}/operation_examine/2/instruction_id/{$ctnMsg['instruction_id']}" style="background-color: #f1691e; border-color: rgb(213, 213, 213); color: rgb(255, 255, 255); font-size: 16px; text-align: center; padding: 3px 15px;">通过</a>
				
					</div>
				</if>
			</div>
		</div>
	</div>
</body>