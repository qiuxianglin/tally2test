<head>
<title>起驳装箱_完成作业详情</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript"
	src="__PUBLIC__/js/jquery.firstebox.pack.js"></script>
<script src="__PUBLIC__/layer/layer.js"></script>
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
				当前位置：<a href="__MODULE__/QbzxSearch/RealTime">起驳装箱查询</a>&nbsp;&gt;&nbsp;<a href="__MODULE__/QbzxSearch/OperationFinish">完成作业查询</a>&nbsp;&gt;&nbsp;完成作业详情
			</div>
		</div>

		<div class="right_t">
			<div class="amsg">
				<p>
					箱号：{$msg['ctnno']}
						<?php
						if(in_array('1',$photoauth)){
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
						}
						?>
						&nbsp;&nbsp;&nbsp;&nbsp; 铅封号：{$msg['sealno']}
						<?php if(in_array('1',$photoauth)){?>
						<?php
						if ($operationMsg ['seal_picture']) 
                        {
							echo '（<a href=".'.$IMAGE_QBZX_SEAL.$operationMsg ['seal_picture'].'" class="firstebox">查看铅封照片</a>）';
						}
						?>
						<?php
						if ($operationMsg ['halfclose_door_picture']) 
                        {
							echo '（<a href=".'.$IMAGE_QBZX_HALFCLOSEDOOR.$operationMsg ['halfclose_door_picture'].'" class="firstebox">查看半关门照片</a>）';
						}
						?>
						<?php
						if ($operationMsg ['close_door_picture']) 
                        {
							echo '（<a href=".'.$IMAGE_QBZX_CLOSEDOOR.$operationMsg ['close_door_picture'].'" class="firstebox">查看全关门照片</a>）';
						}
						?>
						<?php }?>
						</p>
				<p>
				
					         货物件数：{$msg['total_package']}&nbsp;&nbsp;&nbsp;
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
								<th>驳船</th>
								<th>标志</th>
								<th>包装</th>
								<th>货物件数</th>
								<th>残损件数</th>
								<th>残损说明</th>
								<th>备注</th>
								<th>理货员</th>
							</tr>
						</thead>
						<tbody>
							<volist name="levellist" id="l">
							<tr>
								<td>{$l['level_num']}</td>
								<td>{$l['billno']}</td>
								<td>{$l['ship_name']}</td>
								<td>{$content[0]['mark']}</td>
								<td>{$content[0]['package']}</td>
								<td>{$l['cargo_number']}
								<?php 
									$n = count ( $l ['cargo_picture'] );
									if(count($levellist) < 3){
										if($a || $b){
											if($n > 0){
												echo '（';
												for($j = 0; $j < $n; $j ++) {
													echo '<a href=".'.$l ['cargo_picture'] [$j] ['cargo_picture'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
													if ($j == 0) {
														echo '查看照片';
													}
													echo '</a>';
												}
												echo '）';
											}
										}
									}
									if(count($levellist) >2){
										if($a){
											if($n > 0){
												echo '（';
												for($j = 0; $j < $n; $j ++) {
													echo '<a href=".'.$l ['cargo_picture'] [$j] ['cargo_picture'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
													if ($j == 0) {
														echo '查看照片';
													}
													echo '</a>';
												}
												echo '）';
											}
										}
										if($b){
											if($i == 1 || $i == count($levellist)){
												if($n > 0){
													echo '（';
													for($j = 0; $j < $n; $j ++) {
														echo '<a href=".'.$l ['cargo_picture'] [$j] ['cargo_picture'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
														if ($j == 0) {
															echo '查看照片';
														}
														echo '</a>';
													}
													echo '）';
												}
											}
										}
										if($c){
											if($i != 1 && $i != count($levellist)){
												if($n > 0){
													echo '（';
													for($j = 0; $j < $n; $j ++) {
														echo '<a href=".'.$l ['cargo_picture'] [$j] ['cargo_picture'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
														if ($j == 0) {
															echo '查看照片';
														}
														echo '</a>';
													} 
													echo '）';
												}
											}
										}
									}
								?>
								</td>
								<td>{$l['damage_num']}
									<?php
									$n = count ( $l ['damage_picture'] );
									if(count($levellist) < 3){
										if($a || $b){
											if ($n > 0) {
												echo '（';
												for($j = 0; $j < $n; $j ++) {
													echo '<a href=".'.$l ['damage_picture'] [$j] ['damage_picture'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
													if ($j == 0) {
														echo '查看残损照片';
													}
													echo '</a>';
												}
												echo '）';
											}
										}
									}
									if(count($levellist) >2){
										if($a){
											if ($n > 0) {
												echo '（';
												for($j = 0; $j < $n; $j ++) {
													echo '<a href=".'.$l ['damage_picture'] [$j] ['damage_picture'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
													if ($j == 0) {
														echo '查看残损照片';
													}
													echo '</a>';
												}
												echo '）';
											}
										}
										if($b){
											if($i == 1 || $i == count($levellist)){
												if ($n > 0) {
													echo '（';
													for($j = 0; $j < $n; $j ++) {
														echo '<a href=".'.$l ['damage_picture'] [$j] ['damage_picture'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
														if ($j == 0) {
															echo '查看残损照片';
														}
														echo '</a>';
													}
													echo '）';
												}
											}
										}
										if($c){
											if($i != 1 && $i != count($levellist)){
												if ($n > 0) {
													echo '（';
													for($j = 0; $j < $n; $j ++) {
														echo '<a href=".'.$l ['damage_picture'] [$j] ['damage_picture'].'" class="firstebox" rel="gallery' . $l ['level_num'] . '">';
														if ($j == 0) {
															echo '查看残损照片';
														}
														echo '</a>';
													}
													echo '）';
												}
											}
										}
									}
								?></td>
									<td><a href="javascript:;"
										onclick="alert('{$l['damage_explain']}');">
										<?php 
											if($l['damage_explain'] == ''){
												echo '';
											}else{
												echo '查看残损说明';
											}
										?></a></td>
									<td><a href="javascript:;"
										onclick="alert('{$l['remark']}');">
										<?php 
											if($l['remark'] == ''){
												echo '';
											}else{
												echo '查看备注';
											}
										?></a></td>
								<td>{$l['user_name']}</td>
							</tr>
							</volist>
						</tbody>
					</table>

					<div class="remark">
						<p>批注：{$msg['remark']}</p>
					</div>

					 

						
						 
				</div>
			</div>
		</div>
	</div>
</div>
