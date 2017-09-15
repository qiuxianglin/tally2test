<head>
<title>编辑管理员</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__/uid/{$msg['uid']}" method="post">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">登录用户名：</th>
		        <td>
		          <input class="input fl" type="text" name="adminname" value="{$msg['adminname']}" readonly size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>新密码：</th>
		        <td>
		          <input class="input fl" type="password" name="password" value="" size="25">
		          <em class="error tips">不填写则保持原有密码</em>
		        </td>
		      </tr>
		      <tr>
		        <th>EMAIL：</th>
		        <td>
		          <input class="input fl" type="text" name="email" value="{$msg['email']}" size="25">
		          <em class="error tips">{$error1}</em>
		        </td>
		      </tr>
		      <tr>
		        <th>手机号码：</th>
		        <td>
		          <input class="input fl" type="text" name="phone" value="{$msg['phone']}" size="25">
		          <em class="error tips">{$error2}</em>
		        </td>
		      </tr>
		      <tr>
		        <th>所属分组：</th>
		        <td>
		          <select name="group_id">
                    <?php 
                      foreach($glist as $g)
                      {
                         if($g['id']==$msg['group_id'])
                         {
                             $select='selected';
                         }else {
                             $select='';
                         }
                         echo '<option value="'.$g['id'].'" '.$select.'>--'.$g['title'].'--</option>';
                      }
                    ?>
                  </select>
		        </td>
		      </tr>
		      <tr>
		        <th>注册时间：</th>
		        <td>
		          <label>{$msg['register_time']}</label>
		        </td>
		      </tr>
		      <tr>
		        <th>注册IP：</th>
		        <td>
		          <label>{$msg['register_ip']}</label>
		        </td>
		      </tr>
		      <tr>
		        <th>最后登录时间：</th>
		        <td>
		          <label>{$msg['last_login_time']}</label>
		        </td>
		      </tr>
		      <tr>
		        <th>最后登录IP：</th>
		        <td>
		          <label>{$msg['last_login_ip']}</label>
		        </td>
		      </tr>
		      <tr>
		        <th>登录次数：</th>
		        <td>
		          <label>{$msg['login_num']}</label>
		        </td>
		      </tr>
		      <tr>
		        <th>是否禁用：</th>
		        <td>
		          <input name="status" type="radio" value="1" <?php if($msg['status']=='1'){echo 'checked';} ?> />正常
		          <input name="status" type="radio" value="0" <?php if($msg['status']=='0'){echo 'checked';} ?> />禁用
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" id="sub" value="编辑管理员" class="sub">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		 </form>
		</div>
	</div>
	<include file="Public:systemleft" />
</div>