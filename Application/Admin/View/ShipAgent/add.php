<head>
<title>新增船代</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">船代代码：</th>
		        <td>
		          <input class="input fl" type="text" name="code" id="code" size="25">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>中文船代名称：</th>
		        <td>
		          <input class="input fl" type="text" name="name" id="name" size="25">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>英文船代名称：</th>
		        <td>
		          <input class="input fl" type="text" name="name_en" id="name_en" size="25">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>企业代码：</th>
		        <td>
		          <input class="input fl" type="text" name="enterprise_code" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>地址：</th>
		        <td>
		          <input class="input fl" type="text" name="address" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>邮编：</th>
		        <td>
		          <input class="input fl" type="text" name="postcode" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>联系人：</th>
		        <td>
		          <input class="input fl" type="text" name="contacter" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>电话：</th>
		        <td>
		          <input class="input fl" type="text" name="telephone" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>传真：</th>
		        <td>
		          <input class="input fl" type="text" name="fax" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>电传：</th>
		        <td>
		          <input class="input fl" type="text" name="telex" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>邮箱：</th>
		        <td>
		          <input class="input fl" type="text" name="email" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>备注：</th>
		        <td>
		          <textarea name="remark" class="input fl" style="width: 300px;height:100px"></textarea>
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="新增船代" class="sub">
		          &nbsp;&nbsp;<input type="reset" value="重置" class="res">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		 </form>
		</div>
	</div>
	<include file="Public:shipleft" />
</div>
<script>
function check()
{
	if($('#code').val()=='')
	{
		alert('船代代码不能为空！');
		return false;
	}
	if($('#name').val()=='')
	{
		alert('中文船代名不能为空！');
		return false;
	}
	if($('#name_en').val()=='')
	{
		alert('英文船代名不能为空！');
		return false;
	}
	return true; 
}
</script>