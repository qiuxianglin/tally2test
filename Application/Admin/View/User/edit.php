<head>
<title>编辑用户</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__/id/{$msg['uid']}" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">工号：</th>
		        <td>
		          <input class="input fl" type="text" name="staffno" id="code" size="25" value="{$msg['staffno']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>登录密码：</th>
		        <td>
		          <input class="input fl" type="password" name="user_pwd" id="pwd" size="25" value="">
		          <em class="error tips">不填写代表保持原来密码</em>
		        </td>
		      </tr>
		      <tr>
		        <th>姓名：</th>
		        <td>
		          <input class="input fl" type="text" name="user_name" size="25" value="{$msg['user_name']}">
		        </td>
		      </tr>
		      <tr>
		        <th>所属部门：</th>
		        <td>
		           <select name="department_id">
		           <?php 
		           foreach($deptList as $dl)
		           {
		           	if($dl['id']==$msg['department_id'])
		           	{
		           		$select1='selected';
		           	}else {
		           		$select1='';
		           	}
		           	 echo '<option value="'.$dl['id'].'" '.$select1.'>'.$dl['lefthtml'].''.$dl['department_name'].'</option>';
		           }
		           ?>
		          </select>
		        </td>
		      </tr>
		      <tr>
		        <th>职务：</th>
		        <td>
		          <input class="input fl" type="text" name="position" size="25" value="{$msg['position']}">
		        </td>
		      </tr>
		      <tr>
		        <th>用户组：</th>
		        <td>
		          <select name="group_id">
		           <?php 
		           foreach($glist as $gl)
		           {
		           	 if($gl['id']==$msg['group_id'])
		           	 {
		           	 	$select='selected';
		           	 }else {
		           	 	$select='';
		           	 }
		           	 echo '<option value="'.$gl['id'].'" '.$select.'>'.$gl['title'].'</option>';
		           }
		           ?>
		          </select>
		        </td>
		      </tr>
		      <tr>
		        <th>用户状态：</th>
		        <td>
		          <?php 
		          foreach ($user_status as $key=>$value)
		          {
		          	if($msg['user_status']==$value)
		          	{
		          		$check='checked';
		          	}else {
		          		$check='';
		          	}
		          	echo '<input type="radio" name="user_status" size="25" value="'.$value.'" '.$check.'>'.$user_status_d[$value].'&nbsp;';
		          }
		          ?>
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="编辑用户" class="sub">
		          &nbsp;&nbsp;<input type="reset" value="重置" class="res">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		 </form>
		</div>
	</div>
	<include file="Public:userleft" />
</div>
<script>
function check()
{
	if($('#code').val()=='')
	{
		alert('工号不能为空！');
		return false;
	}
	return true; 
}
</script>