<head>
<title>编辑箱型</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__/id/{$msg['id']}" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">箱型代码：</th>
		        <td>
		          <input class="input fl" type="text" name="ctn_type_code" id="code" size="25" value="{$msg['ctn_type_code']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>箱型：</th>
		        <td>
		          <input class="input fl" type="text" name="ctn_type" id="type" size="25" value="{$msg['ctn_type']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>箱尺寸：</th>
		        <td>
		          <input class="input fl" type="text" name="ctn_size" id="size" size="25" value="{$msg['ctn_size']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="编辑箱型" class="sub">
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
		alert('箱型代码不能为空！');
		return false;
	}
	if($('#type').val()=='')
	{
		alert('箱型不能为空！');
		return false;
	}
	if($('#size').val()=='')
	{
		alert('箱尺寸不能为空！');
		return false;
	}
	return true; 
}
</script>