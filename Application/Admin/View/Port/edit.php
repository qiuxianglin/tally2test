<head>
<title>编辑港口</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__/id/{$msg['id']}" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">港口代码：</th>
		        <td>
		          <input class="input fl" type="text" name="code" id="code" size="25" value="{$msg['code']}">
		          <em class="error tips">必须填，不能重复</em>
		        </td>
		      </tr>
		      <tr>
		        <th>中文港口名称：</th>
		        <td>
		          <input class="input fl" type="text" name="name" id="name" size="25" value="{$msg['name']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>英文港口名称：</th>
		        <td>
		          <input class="input fl" type="text" name="name_en" size="25" value="{$msg['name_en']}">
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="编辑港口" class="sub">
		          &nbsp;&nbsp;<input type="reset" value="重置" class="res">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		 </form>
		</div>
	</div>
	<include file="Public:operationleft" />
</div>
<script>
function check()
{
	if($('#code').val()=='')
	{
		alert('港口代码不能为空！');
		return false;
	}
	if($('#name').val()=='')
	{
		alert('中文港口名称不能为空！');
		return false;
	}
	return true; 
}
</script>