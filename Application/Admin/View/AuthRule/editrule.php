<head>
<title>编辑权限</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
			<form action="__ACTION__/rule_id/{$msg['id']}" method="post">
				<table class="frame_form" cellpadding="0" cellspacing="0"
					width="45%">
					<tbody>
						<tr>
							<th width="110">权限名称：</th>
							<td>
								<input class="input fl" type="text" name="title" size="25" value="{$msg['title']}">
								<em class="error tips">{$error1}</em>
							</td>
						</tr>
						<tr>
							<th>控制器/方法：</th>
							<td>
								<input class="input fl" type="text" name="name" size="25" value="{$msg['name']}">
								<em class="error tips">{$error2}</em>
							</td>
						</tr>
						<tr>
							<th>父级：</th>
							<td>
								<select name="pid">
									<option value="0">--默认顶级--</option>
									<?php 
									foreach ($admin_rule as $v)
									{
										if($v['id']!=$msg['id'])
										{
											if($v['id']==$msg['pid'])
											{
												$select='selected';
											}else {
												$select='';
											}
											echo '<option value="'.$v['id'].'" style="margin-left: 55px;" '.$select.'>'.$v['lefthtml'].''.$v['title'].'</option>';
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th>是否开启：</th>
							<td>
								<input name="status" type="radio" value="1" <?php if($msg['status']=='1') {echo 'checked';} ?> />是<input name="status" type="radio" value="0"  <?php if($msg['status']=='0') {echo 'checked';} ?>/>否
							</td>
						</tr>
						<tr>
							<th>排序（从大到小）：</th>
							<td>
								<input class="input fl" type="text" name="sort" size="25" value="{$msg['sort']}">
							</td>
						</tr>
						<tr>
							<th></th>
							<td>
								<input type="submit" value="编辑权限" class="sub">
								&nbsp;&nbsp;
								<input type="reset" value="重置" class="res">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
	<include file="Public:systemleft" />
</div>