<head>
<title>门到门拆箱_完成作业查询</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
<script src="__PUBLIC__/js/jquery.bigautocomplete.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigautocomplete.css" type="text/css" />
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
					当前位置：<a href="__MODULE__/Search/index">查询统计</a>&nbsp;&gt;&nbsp;完成作业查询
				</div>
			</div>
			<div class="right_list2">
				<div class="addrule">
					<form class="select" action="__ACTION__" method="post">
					<input type="hidden" name="p" value="1">
					<input type="hidden" name="c" value="DdSearch">
					<input type="hidden" name="a" value="complete">
						船名： <input type="text" class="input1" name="ship_name" id="shipname" autocomplete="off" style="text-transform: uppercase;"/> &nbsp;&nbsp;&nbsp;&nbsp;
						航次：<input type="text" name="vargo" class="input1"> &nbsp;&nbsp;&nbsp;&nbsp;
						作业地点： <select class="input1" name="location_id" style="width: 135px">
							      <option value="">--默认全部--</option>
							      <volist name="locationlist" id="l">
							      <option value="{$l['id']}">{$l['location_name']}</option>
							      </volist>
						        </select>
						提单号：<input type="text" name="bl_no" class="input1">
						        <br> <br>
						整拼： <select class="input1" name="flflag" style="width: 135px">
							<option value="">--默认全部--</option>
							<option value="F">整箱</option>
							<option value="L">拼箱</option>
						</select> &nbsp;&nbsp;&nbsp;&nbsp;
						 箱型：<input type="text" name="ctn_type_code" class="input1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<!--
						箱型： <select class="input1" name="cube" style="width: 135px">
							<option value="">--默认全部--</option>
							<volist name="containerlist" id="cl">
							<option value="{$cl['containertypecode']}">{$cl['containertypecode']}</option>
							</volist>
						</select> &nbsp;&nbsp;&nbsp;&nbsp;
						-->
						 集装箱号：<input type="text" name="ctn_no" class="input1">&nbsp;&nbsp;&nbsp;&nbsp;
						完成时间： 从 <input type="text" name="begin_time" class="input1 Wdate" onClick="WdatePicker()" style="width: 90px"> 到 <input type="text" name="end_time"
							class="input1 Wdate" onClick="WdatePicker()" style="width: 90px">
						&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="查询"
							style="background-color: #3398db !important; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px;">
					</form><br>
					<form action="__CONTROLLER__/pack_img" method="post">
					<!--<volist name="list" id="l">
						<input type="hidden" name="ctn_id[]" value="{$l['ctn_id']}"/>
					</volist> -->
					<input type="submit"
						value="图片下载"
						style="background-color: #3398db !important; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px;">
				</div>
				<div style="clear: both;"></div>
				<div class="row" style="margin-top: 10px">
					<div class="col-xs-12">
						<div>
							<table width="100%" class="table">
								<thead>
									<tr>
									<th><input id="checkAll" type="checkbox" id="checkAll" />&nbsp;全选</th>
										<th>船名</th>
										<th>航次</th>
										<th>业务系统</th>
										<th>作业场地</th>
										<th>箱号</th>
										<th>箱型尺寸</th>
										<th>铅封号</th>
										<th>总票数</th>
										<th>总件数</th>
										<th>总重量</th>
										<th>总残损</th>
										<th>完成时间</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<volist name="list" id="l">
									<tr>
										<td>
											<input type="checkbox" name="ctn_id[]" value="{$l['ctn_id']}"/>
										</td>
										<td>{$l['ship_name']}</td>
										<td>{$l['vargo']}</td>
										<td>
										<if condition="$l['business'] eq 'dd'">
										门到门拆箱系统
										<elseif condition="$l['business'] eq 'cfs'" />
										CFS拆箱系统
										<else/>

										</if>
										</td>
										<td>{$l['location_name']}</td>
										<td>{$l['ctn_no']}</td>
										<td>{$l['ctn_type_code']}</td>
										<td>{$l['sealno']}</td>
										<td>{$l['total_ticket']}</td>
										<td>{$l['total_package']}</td>
										<td>{$l['total_weight']}</td>
										<td>{$l['damaged_quantity']}</td>
										<td>{$l['createtime']}</td>
										<td>
										   <a href="__CONTROLLER__/completeDetail/ctn_id/{$l['ctn_id']}">查看</a>
										</td>
									</tr>
									</volist>
								</tbody>
							</table>
							</form>
							<div class="pages">{$page}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<script>
$(function(){
	$("#shipname").bigAutocomplete({
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
})
</script>
<script type="text/javascript">
    $(function() {
       $("#checkAll").click(function() {
            $('input[name="ctn_id[]"]').attr("checked",this.checked); 
        });
        var $cost_id = $("input[name='ctn_id']");
        $cost_id.click(function(){
            $("#checkAll").attr("checked",$cost_id.length == $("input[name='ctn_id']:checked").length ? true : false);
        });
    });
</script>