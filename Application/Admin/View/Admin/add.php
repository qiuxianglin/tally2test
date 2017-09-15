<head>
<title>新增管理员</title>
<script>
$(document).ready(function(){
	//检验用户名
	$('#adminname').blur(function(){
		adminname=$('#adminname').val();
		if(adminname=='')
		{
			$('#nameAjax').html('用户名不能为空！');
			return false;
		}else {
			$('#nameAjax').html('');
		}
		$.ajax({
			type:"POST",
			url:"<?php echo WEB_URL;?>/admin.php?c=Admin&a=add",
			dataType:"html",
			data:"adminname="+adminname,
			success:function(msg)
			{
				$('#nameAjax').html(msg);
			}
		});
	});
	
	//检验密码
	$('#password2').blur(function(){
		password=$('#password').val();
		password2=$('#password2').val();
		if(password=='' || password2=='')
		{
			$('#pwdAjax').html('密码不能为空！');
			return false;
		}else {
			$('#pwdAjax').html('');
		}
		$.ajax({
			type:"POST",
			url:"<?php echo WEB_URL;?>/admin.php?c=Admin&a=add",
			dataType:"html",
			data:"password="+password+"&password2="+password2,
			success:function(msg)
			{
				$('#pwdAjax').html(msg);
			}
		});
	});
	
	//检验EMAIL
	$('#email').blur(function(){
		email=$('#email').val();
		if(email!='')
		{
			$.ajax({
				type:"POST",
				url:"<?php echo WEB_URL;?>/admin.php?c=Admin&a=add",
				dataType:"html",
				data:"email="+email,
				success:function(msg)
				{
					$('#emailAjax').html(msg);
				}
			});
		}else {
			$('#emailAjax').html('');
		}
	});
	
	//检验手机号码
	$('#phone').blur(function(){
		phone=$('#phone').val();
		if(phone!='')
		{
			$.ajax({
				type:"POST",
				url:"<?php echo WEB_URL;?>/admin.php?c=Admin&a=add",
				dataType:"html",
				data:"phone="+phone,
				success:function(msg)
				{
					$('#phoneAjax').html(msg);
				}
			});
		}else {
			$('#phoneAjax').html('');
		}
	});
	
	//提交注册
	$('#sub').click(function(){
		adminname=$('#adminname').val();
		password=$('#password').val();
		password2=$('#password2').val();
		email=$('#email').val();
		phone=$('#phone').val();
		group_id=$('#group_id').val();
		if(group_id=='')
		{
			$('#gAjax').html('X请选择管理员组');
			return false;
		}
		status=$('input:radio[name="status"]:checked').val();
		$.ajax({
			type:"POST",
			url:"<?php echo WEB_URL;?>/admin.php?c=Admin&a=add",
			dataType:"html",
			data:"adminname="+adminname+"&password="+password+"&password2="+password2+"&email="+email+"&phone="+phone+"&group_id="+group_id+"&status="+status,
			success:function(msg)
			{
				if(msg=='1')
				{
					alert('新增管理员成功！');
					location.href='index';
				}else {
					alert('操作失败！');
					location.reload();
				}
			}
		});
	});
});
</script>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">登录用户名：</th>
		        <td>
		          <input class="input fl" type="text" name="adminname" id="adminname" size="25">
		          <em class="error tips" id="nameAjax">必须填，不能重复</em>
		        </td>
		      </tr>
		      <tr>
		        <th>密码：</th>
		        <td>
		          <input class="input fl" type="password" id="password" name="password" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>重复密码：</th>
		        <td>
		          <input class="input fl" type="password" id="password2" name="password2" size="25">
		          <em class="error tips" id="pwdAjax">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>EMAIL：</th>
		        <td>
		          <input class="input fl" type="text" id="email" name="email" size="25">
		          <em class="error tips" id="emailAjax"></em>
		        </td>
		      </tr>
		      <tr>
		        <th>手机号码：</th>
		        <td>
		          <input class="input fl" type="text" id="phone" name="phone" size="25">
		          <em class="error tips" id="phoneAjax"></em>
		        </td>
		      </tr>
		      <tr>
		        <th>所属分组：</th>
		        <td>
		          <select name="group_id" id="group_id">
                    <option value="">--请选择所属分组--</option>
                    <?php 
                      foreach($glist as $g)
                      {
                         echo '<option value="'.$g['id'].'">--'.$g['title'].'--</option>';
                      }
                    ?>
                  </select>
                  <em class="error tips" id="gAjax"></em>
		        </td>
		      </tr>
		      <tr>
		        <th>是否禁用：</th>
		        <td>
		          <input name="status" type="radio" value="1" checked/>正常
		          <input name="status" type="radio" value="0"/>禁用
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" id="sub" value="新增管理员" class="sub">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		</div>
	</div>
	<include file="Public:systemleft" />
</div>