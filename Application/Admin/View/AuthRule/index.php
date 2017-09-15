<head>
<title>权限管理</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/ad/css/system.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
<script type="text/javascript">
function changestatus(id,status)
{
	if(id!='')
	{
		$.ajax({
			type:"POST",
			url:"<?php echo WEB_URL;?>/admin.php?c=AuthRule&a=changestatus",
			dataType:"html",
			data:"id="+id+"&status="+status,
			success:function(msg)
			{
				if(msg==1)
				{
					alert('修改状态成功！');
				}else {
					alert('修改状态失败！');
				}
			    location.reload();
			}
		});
	}
}

function delrule(id)
{
	if(id!='')
	{
		if(confirm('确定要删除该条权限规则吗？'))
		{
			$.ajax({
				type:"POST",
				url:"<?php echo WEB_URL;?>/admin.php?c=AuthRule&a=delrule",
				dataType:"html",
				data:"id="+id,
				success:function(msg)
				{
					if(msg==1)
					{
						alert('删除成功！');
					}else {
						alert('操作失败！');
					}
				    location.reload();
				}
			});
		}
	}
}
</script>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px" class="main_box">
		    <div style="margin-top: 20px"></div>
			<form action="__CONTROLLER__/addrule" method="post">
				<div class="addrule">
					<small>状态：</small>
					<small>
						<select name="status">
							<option value="1">显示</option>
							<option value="0">不显示</option>
						</select>
					</small>
					<small class="sl-left10">父级：</small>
					<small>
						<select name="pid" style="width: 210px;">
							<option value="0">--默认顶级--</option>
							<foreach name="admin_rule" item="v">
							<option value="{$v.id}" style="margin-left: 55px;">{$v.lefthtml}{$v.title}</option>
							</foreach>
						</select>
					</small>
					<small class="sl-left10">名称：</small>
					<small>
						<input name="title" class="rule" />
					</small>
					<small class="sl-left10">控制器/方法：</small>
					<small>
						<input name="name" class="rule" />
					</small>
					<small class="sl-left10">排序：</small>
					<small>
						<input name="sort" class="wh30" value="" />
					</small>
					<small>
						<input type="submit" value="添加权限" class="ruleadd"
							style="margin-left: 3px">
					</small>
				</div>
			</form>
			<div class="ruleintro" style="color: red">
				1、不设置父级，则默认为顶级
				<br />
				2、《控制器/方法》：意思是按照 控制器/方法 名来对应设置文件访问权限，即ControllerName/ActionName
				<br />
				3、《控制器/方法》不能重复添加，否则报错，请确保每个权限的“控制器/方法”字段名称不同
				<br />
				4、排序控制显示顺序，数字越大越在前面显示
			</div>
			<div style="clear: both"></div>

			<div class="row" style="width:98%;margin-left:10px">
				<div class="col-xs-12">
					<div>
						<form action="__CONTROLLER__/changesort" method="post">
							<table class="table">
								<thead>
									<tr>
										<th width="6%">ID</th>
										<th width="22%">权限名称</th>
										<th width="25%">控制器/方法</th>
										<th width="9%">菜单状态</th>
										<th width="18%">添加时间</th>
										<th width="7%">排序</th>
										<th width="6%">操作</th>
									</tr>
								</thead>
								<tbody>
									<foreach name="admin_rule" item="v">
									<tr>
										<td height="28">{$v.id}</td>
										<td style='padding-left:<if   condition="$v.leftpin neq 0">
											{$v.leftpin}px
											</if>
											' >{$v.lefthtml}{$v.title}
										</td>
										<td>{$v.name}</td>
										<td>
											<if condition='$v[status] eq 1'>
											<button type="button" class="btn btn-minier btn-yellow"
												onclick="changestatus({$v.id},0);">显示状态</button>
											<else />
											<button type="button" class="btn btn-minier btn-danger"
												onclick="changestatus({$v.id},1);">隐藏状态</button>
											</if>
										</td>
										<td>{$v.create_time}</td>
										<td>
											<input name="sort[{$v.id}]" value="{$v.sort}"
												class="list_order" />
										</td>
										<td>
											<a href="__CONTROLLER__/editrule/rule_id/{$v.id}" title="修改">
												<img src="__PUBLIC__/admin/images/wzfl_05.png" />
											</a>
											<a href="javascript:;" onclick="delrule({$v.id});" title="删除">
												<img src="__PUBLIC__/admin/images/wzfl_11.png" />
											</a>
										</td>
									</tr>
									</foreach>
									<tr>
										<td colspan="8" align="left">
											<button type="submit" class="btn btn-white btn-yellow btn-sm" style="padding:0 5px;line-height:0">排序</button>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>



		</div>
	</div>
	<include file="Public:systemleft" />
</div>