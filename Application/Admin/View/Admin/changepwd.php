<head>
<title>修改密码</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__" method="post">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">原密码：</th>
		        <td>
		          <input class="input fl" type="password" name="oldpwd" size="25">
		          <em class="error tips">{$error1}</em>
		        </td>
		      </tr>
		      <tr>
		        <th>新密码：</th>
		        <td>
		          <input class="input fl" type="password" name="pwd1" value="" size="25">
		          <em class="error tips">{$error2}</em>
		        </td>
		      </tr>
		      <tr>
		        <th>重复密码：</th>
		        <td>
		          <input class="input fl" type="password" name="pwd2" value="" size="25">
		          <em class="error tips">{$error3}</em>
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" id="sub" value="修改密码" class="sub">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		 </form>
		</div>
	</div>
	<include file="Public:systemleft" />
</div>