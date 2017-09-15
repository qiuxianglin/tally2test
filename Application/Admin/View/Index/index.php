<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- ie browser -->
		<!--[if IE]>
			<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
			<script type="text/javascript" src="js/html5.js"></script>
		<![endif]-->
		<!--[if lt IE 7]>
			<script src="js/IE7.js">var IE7_PNG_SUFFIX = ".png";</script>
		<![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台登录</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/style.css">
</head>
<?php
if($_COOKIE['remember']=='ok')
{
  $checked='checked';
}else {
  $checked="";
}
?>
<body>
 <div id="main">
   <div class="left">
      <img src="__PUBLIC__/ad/img/logo2.png"/>
      <img src="__PUBLIC__/admin/images/login.png" class="loginimg"/>
   </div>
   <div class="right">
      <img src="__PUBLIC__/ad/img/future.png" class="future" />
      <div class="login">
        <p><strong>登录系统</strong></p>
        <form class="loginform" action="__CONTROLLER__/loginin" method="post">
         <tr>
           <td>账&nbsp;&nbsp;号</td>
           <td><input type="text" class="user" name="adminuser" value="<?php echo $_COOKIE['loginname'];?>"></td>
         </tr>
         <tr>
           <td>密&nbsp;&nbsp;码</td>
           <td><input type="password" class="pwd" name="adminpwd" value="<?php echo $_COOKIE['loginpwd'];?>"></td>
         </tr>
         <tr>
           <td>验证码</td>
           <td><input type="text" class="auth" name="auth"></td>
           <td><img class="codeimg" src="__CONTROLLER__/verify" alt="点击刷新验证码" onclick="this.src='__CONTROLLER__/verify&'+Math.random()"/></td>
         </tr>        
         <tr>
           <td><input  type="checkbox" name="remember" value="ok"  class="remember" <?php echo $checked;?> ></td>
           <td><font size="4" color="#FFFFFF">记住密码</font></td>
           <td><input type="submit" value="登录" class="sumbit"></td>
         </tr>
        </form>
      </div>
      <p style="font-size:18px;color:red;margin-left:100px;margin-top:10px">{$error}</p>
   </div>
 </div>
</body>
</html>