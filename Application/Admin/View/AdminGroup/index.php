<head>
<title>组别管理</title>
</head>
<script type="text/javascript">
function changestatus(id,status)
{
	if(id!='')
	{
		$.ajax({
			type:"POST",
			url:"<?php echo WEB_URL;?>/admin.php?c=AdminGroup&a=changestatus",
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

function delgroup(id)
{
	if(id!='')
	{
		if(confirm('分组下的所有管理员会被一起删除，确定要删除该分组吗？'))
		{
			$.ajax({
				type:"POST",
				url:"<?php echo WEB_URL;?>/admin.php?c=AdminGroup&a=delgroup",
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
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/addgroup" class="on">添加管理员组</a>
		      </ul>
		    </div>
			<div class="table-list">
			  <table cellspacing="0" width="100%">
			   <colgroup>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col align="center" width="180"></col>
			   </colgroup>
			   <thead>
			    <tr>
			        <th>ID</th>
				    <th width="15%">管理员组名</th>
				    <th width="35%">组别描述</th>
				    <th width="7%">状态</th>
				    <th width="13%">添加时间</th>
					<th width="6%">用户数量</th>
					<th width="11%">查看用户列表</th>
			      <th class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     foreach ($glist as $g)
			     {
			     	if($g['status']=='1')
			     	{
			     		$status_str='<a onclick="changestatus('.$g['id'].',0);">开启状态</a>';
			     	}else {
			     		$status_str='<a style="color:red" onclick="changestatus('.$g['id'].',1);">关闭状态</a>';
			     	}
			     	$group_id=$g['id'];
			     	$admin=D('admin');
			     	$unum=$admin->where("group_id=$group_id")->count();
			     	echo '<tr>
			               <td>'.$g['id'].'</td>
				           <td>'.$g['title'].'</td>
		        		   <td>'.$g['introduce'].'</td>
		        		   <td>'.$status_str.'</td>
		        		   <td>'.$g['create_time'].'</td>
		        		   <td>'.$unum.'</td>
		        		   <td><a href="__MODULE__/Admin/index/group_id/'.$g['id'].'" style="color:red">点击查看用户列表</a></td>
		        		   <td class="textcenter">
			    		    <a href="__CONTROLLER__/editgroup/group_id/'.$g['id'].'">查看/编辑</a>|
		    		        <a href="javascript:;" onclick="delgroup('.$g['id'].')">删除</a>
			    		   </td>
		        		  </tr>';
			     }
			     ?>
			   </tbody>
			  </table>
			  
			</div>
		</div>
	</div>
	<include file="Public:systemleft" />
</div>