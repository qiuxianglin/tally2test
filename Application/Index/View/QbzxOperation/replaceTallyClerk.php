
<head>
<title>起泊装箱-替换理货员</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript" src="__PUBLIC__/admin/js/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$('.row').find('table tbody tr:even').css('background','#fff')	
});
</script>
</head>

<body>
	<div class="row"
		style="width: 400px; margin: 0 auto; text-align: center;">
		<div class="col-xs-12">
			<form action="__ACTION__" method="post">
				<input type="hidden" name="instruction_id" value="{$instruction_id}">
				<input type="hidden" name="operation_id" value="{$operation_id}">
				<input type="hidden" name="ctn_id" value="{$ctn_id}">
			<h5 class="hh">替换理货员</h5>
	        <hr style="width:300px;margin:5px auto;background:#abcdef;height:2px;">
				<table width="100%" class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>工号</th>
							<th>姓名</th>
							<th>职务</th>
						</tr>
					</thead>
					<tbody>
						<volist name="workerlist" id="u">
						<tr>
							<td height="36">
							   <input type="radio" name="operator" value="{$u['uid']}">
							</td>
							<td>{$u['staffno']}</td>
							<td>{$u['user_name']}</td>
							<td>{$u['position']}</td>
						</tr>
						</volist>
						<tr>
							<td colspan="4">
							  <textarea name="reason" style="width:99%;height:100px" onfocus="if(this.value=='请填写修改原因'){this.value=''}" onblur="if(this.value==''){this.value='请填写修改原因'}">请填写修改原因</textarea>
							</td>
						</tr>
						<tr>
							<td colspan="4">
							  <input type="submit" style="background-color: #3398db !important; float: left; border-color: #3398db; color: #fff; font-size: 16px; text-align: center; padding: 3px 15px; border: 0" value="替换理货员" />
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</body>