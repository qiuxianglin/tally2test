<head>
<title>批量导入用户</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<div style="padding-left:10px">
		  <p>温馨提示：</p>
		  <p>1、批量导入的用户文件必须为EXECL文件，请下载提供的示例文件=><a style="color:red" href="__CONTROLLER__/down">下载示例文件</a>；</p>
		  <p>2、请勿导入重复的用户信息，重复的用户无法导入；</p>
		</div>
		<form action="__ACTION__" method="post"  style="padding-left:10px" enctype="multipart/form-data">
		    上传用户文件：<input type="file" name="file">
		    <input type="text" name="aaa" style="display: none">
		  <input type="submit" value="确认上传" class="sub">
		</form>
		</div>
	</div>
	<include file="Public:userleft" />
</div>