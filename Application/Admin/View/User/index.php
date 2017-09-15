<head>
<title>用户列表</title>
</head>

<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/add" class="on">新增用户</a>
		      </ul>
		    </div>
		    <table class="search_table" width="100%">
		      <tbody>
		        <tr>
		          <td>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="User">
		           <input type="hidden" name="a" value="index">
		             工号：<input type="text" name="staffno" class="input-text" style="width:90px">
		             姓名：<input type="text" name="user_name" class="input-text" style="width:90px">
		             所属部门：<select name="department_id">
		             <option value="">全部</option>
		             <?php 
		              foreach($deptList as $dl)
		              {
		              	echo '<option value="'.$dl['id'].'">'.$dl['lefthtml'].''.$dl['department_name'].'</option>';
		              }
		           ?>
		             </select>
		              用户组：<select name="group_id">
		              <option value="">全部</option>
		             <?php
		             foreach($glist as $gl)
		             {
		             	echo '<option value="'.$gl['id'].'">'.$gl['title'].'</option>';
		             }
		             ?>
		             </select>
		             状态：<select name="status">
		             <option value="">全部</option>
		             <?php 
		          foreach ($user_status as $key=>$value)
		          {
		          	echo '<option value="'.$value.'">'.$user_status_d[$value].'</option>';
		          }
		          ?>
		             </select>
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
			        <th>ID</th>
				    <th>工号</th>
					<th>姓名</th>
					<th>所属部门</th>
					<th>职务</th>
					<th>用户组</th>
					<th>最后登录时间</th>
					<th>用户状态</th>
					<th>冻结/恢复 </th>
					<th class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     $department=new \Common\Model\DepartmentModel();
			     $UserGroup=new \Common\Model\UserGroupModel();
			     foreach ($ulist as $u)
			     {
			     	//部门
			     	$res_d=$department->getDepartmentMsg($u['department_id']);
			     	$department_name=$res_d['department_name'];
			     	//用户组
			     	$res_g=$UserGroup->getUserGroupMsg($u['group_id']);
			     	$group_name=$res_g['title'];
			     	//状态
			     	$status=$u['user_status'];
			     	if($status==$user_status['valid'])
			     	{
			     		$status_str='正常';
			     		$action='冻结用户';
			     		$color="#000";
			     		$status='N';
			     	}else{
			     		$status_str='冻结';
			     		$action='解冻用户';
			     		$color="red";
			     		$status='Y';
			     	}
			     	echo '<tr>
			               <td>'.$u['uid'].'</td>
				           <td>'.$u['staffno'].'</td>
		        		   <td>'.$u['user_name'].'</td>
		        		   <td>'.$department_name.'</td>
						   <td>'.$u['position'].'</td>
		        		   <td>'.$group_name.'</td>
						   <td>'.$u['last_logintime'].'</td>
						   <td>'.$status_str.'</td>
			    		   <td><a href="javascript:;" onclick="changestatus('.$u['uid'].',\''.$status.'\')" style="color:'.$color.'">'.$action.'</a></td>
		        		   <td class="textcenter">
		      		        <a href="javascript:;" onclick="resetpwd('.$u['uid'].')">重置密码</a>|
			    		    <a href="__CONTROLLER__/edit/id/'.$u['uid'].'">查看/编辑</a>
			    		   </td>
		        		  </tr>';
			     }
			     ?>
			   </tbody>
			  </table>
			</div>
			<div class="pages">{$page}</div>
		</div>
	</div>
	<include file="Public:userleft" />
</div>
<script type="text/javascript">
function changestatus(id,status)
{
	if(status=='N')
	{
		if(confirm('确定要冻结该用户吗？'))
		{
			flag=true;
		}else {
			flag=false;
		}
	}
	if(status=='Y')
	{
		if(confirm('确定要恢复该用户吗？'))
		{
			flag=true;
		}else {
			flag=false;
		}
	}
	if(flag==true)
	{
		$.ajax({
			type:"POST",
			url:"{:U('User/changestatus')}",
			dataType:"html",
			data:"id="+id+'&status='+status,
			success:function(msg)
			{
			    if(msg==1)
			    {
			    	alert('操作成功！');
				}else {
					alert('操作失败！');
				}
			    location.reload();
			}
		});
	}
}

function resetpwd(id)
{
	if(confirm('确定要重置该用户密码吗？'))
	{
		$.ajax({
			type:"POST",
			url:"{:U('User/resetpwd1')}",
			dataType:"html",
			data:"id="+id,
			success:function(msg)
			{
			    if(msg=='1')
			    {
				    alert('重置密码成功！');
				}else {
					alert('重置密码失败！');
				}
			    location.reload();
			}
		});
	}
}
</script>