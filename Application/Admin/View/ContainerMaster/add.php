<head>
<title>新增箱主</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">箱主代码：</th>
		        <td>
		          <input class="input fl" type="text" name="containerMasterCode" id="code" size="25">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>箱主名称：</th>
		        <td>
		          <input class="input fl" type="text" name="containerMaster" id="name" size="25">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="新增箱主" class="sub">
		          &nbsp;&nbsp;<input type="reset" value="重置" class="res">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		 </form>
		</div>
	</div>
	<include file="Public:containerleft" />
</div>
<script>
function check()
{
	if($('#code').val()=='')
	{
		alert('箱主代码不能为空！');
		return false;
	}
	if($('#name').val()=='')
	{
		alert('箱主名称不能为空！');
		return false;
	}
	return true; 
}
</script>