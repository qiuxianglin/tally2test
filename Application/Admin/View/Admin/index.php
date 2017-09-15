<head>
<title>管理员列表</title>
</head>
<script type="text/javascript">
function changestatus(id,status)
{
	if(id!='')
	{
		$.ajax({
			type:"POST",
			url:"<?php echo WEB_URL;?>/admin.php?c=Admin&a=changestatus",
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

function deladmin(id)
{
	if(id!='')
	{
		if(confirm('确定要删除该管理员吗？'))
		{
			$.ajax({
				type:"POST",
				url:"<?php echo WEB_URL;?>/admin.php?c=Admin&a=del",
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
		        <a href="__CONTROLLER__/add" class="on">添加管理员</a>
		      </ul>
		    </div>
		    <table class="search_table" width="100%">
		      <tbody>
		        <tr>
		          <td>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="Admin">
		           <input type="hidden" name="a" value="index">
		              输入用户名、邮箱、手机号码查询：<input type="text" name="search" class="input-text">
		             <input type="submit" class="sub2" value="查询">
		           </form>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="Admin">
		           <input type="hidden" name="a" value="index">
		              输入管理员组名查询：<input type="text" name="group_name" class="input-text">
		             <input type="submit" class="sub2" value="查询">
		           </form>
		          </td>
		        </tr>
		      </tbody>
		    </table>
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
			        <th width="3%">ID</th>
				    <th width="10%">用户名</th>
				    <th width="15%">所属分组</th>
				    <th width="5%">状态</th>
				    <th width="12%">邮箱</th>
					<th width="8%">手机号码</th>
					<th width="6%">登录次数</th>
					<th width="8%">最后登录时间</th>
					<th width="9%">最后登录IP</th>
					<th width="8%" class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     foreach ($alist as $a)
			     {
			     	if($a['status']=='1')
			     	{
			     		$status_str='<a onclick="changestatus('.$a['uid'].',0);">&nbsp;正常&nbsp;</a>';
			     	}else {
			     		$status_str='<a style="color:red" onclick="changestatus('.$a['uid'].',1);">&nbsp;禁用&nbsp;</a>';
			     	}
			     	$group_id=$a['group_id'];
			     	$group=D('AdminGroup');
			     	$res=$group->where("id=$group_id")->field('title')->find();
                    $g_title=$res['title'];
			     	echo '<tr>
			               <td>'.$a['uid'].'</td>
				           <td>'.$a['adminname'].'</td>
		        		   <td>'.$g_title.'</td>
		        		   <td>'.$status_str.'</td>
		        		   <td>'.$a['email'].'</td>
		        		   <td>'.$a['phone'].'</td>
		        		   <td>'.$a['login_num'].'</td>
		                   <td>'.$a['last_login_time'].'</td>
			               <td>'.$a['last_login_ip'].'</td>
		        		   <td class="textcenter">
			    		    <a href="__CONTROLLER__/edit/uid/'.$a['uid'].'">查看/编辑</a>|
		    		        <a href="javascript:;" onclick="deladmin('.$a['uid'].')">删除</a>
			    		   </td>
		        		  </tr>';
			     }
			     ?>
			   </tbody>
			  </table>
			  
			</div>
			<div class="pages">
			    {$page}
			  </div>
		</div>
	</div>
	<include file="Public:systemleft" />
</div>