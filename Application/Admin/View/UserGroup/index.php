<head>
<title>用户组别管理</title>
</head>
<script type="text/javascript">
function changestatus(id,status)
{
	if(id!='')
	{
		$.ajax({
			type:"POST",
			url:"<?php echo U('changestatus');?>",
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
</script>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/addgroup" class="on">添加用户组</a>
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
				    <th>用户组名</th>
					<th>用户数量</th>
					<th>查看用户列表</th>
					<th>状态</th>
			      <th class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     $User=new \Common\Model\UserModel();
			     foreach ($glist as $g)
			     {
			     	$group_id=$g['id'];
			     	$unum=$User->where("group_id='$group_id'")->count();
			     	if($g['status']==$usergroup_status['valid'])
			     	{
			     		$status_str='<a onclick="changestatus('.$g['id'].',0);">开启状态</a>';
			     	}else {
			     		$status_str='<a style="color:red" onclick="changestatus('.$g['id'].',1);">关闭状态</a>';
			     	}
			     	echo '<tr>
			               <td>'.$g['id'].'</td>
				           <td>'.$g['title'].'</td>
		        		   <td>'.$unum.'</td>
		        		   <td><a href="__MODULE__/User/index/group_id/'.$g['id'].'" style="color:red">点击查看用户列表</a></td>
		        		   <td>'.$status_str.'</td>
				    		<td class="textcenter">
			    		    <a href="__CONTROLLER__/editgroup/group_id/'.$g['id'].'">查看/编辑</a>
			    		   </td>
		        		  </tr>';
			     }
			     ?>
			   </tbody>
			  </table>
			</div>
		</div>
	</div>
	<include file="Public:userleft" />
</div>