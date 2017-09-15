<head>
<title>新增作业地点</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">作业地点代码：</th>
		        <td>
		          <input class="input fl" type="text" name="location_code" id="code" size="25">
		          <em class="error tips">必须填，不能重复</em>
		        </td>
		      </tr>
		      <tr>
		        <th>作业地点名称：</th>
		        <td>
		          <input class="input fl" type="text" name="location_name" id="name" size="25">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>详细地址：</th>
		        <td>
		          <input class="input fl" type="text" name="address" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>联系人：</th>
		        <td>
		          <input class="input fl" type="text" name="linkman" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>联系电话：</th>
		        <td>
		          <input class="input fl" type="text" name="telephone" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>类别：</th>
		        <td>
		          <select name="location_type">
		            <?php 
		            foreach ($location_type as $key=>$value)
		            {
		            	echo '<option value="'.$value.'">'.$location_type_d[$value].'</option>';
		            }
		            ?>
		          </select>
		        </td>
		      </tr>
		      <tr>
		        <th>上级作业地点：</th>
		        <td>
		          <select name="pid">
		            <option value="0">默认顶级作业地点</option>
		            <foreach name="locationList" item="v">
                     <option value="{$v.id}" style="margin-left:55px;">{$v.lefthtml}{$v.location_name}</option>
                     </foreach>
		          </select>
		        </td>
		      </tr>
		      <tr>
		        <th>备注：</th>
		        <td>
		          <textarea rows="3" cols="30" name="comment"></textarea>
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="新增作业地点" class="sub">
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
		alert('作业地点代码不能为空！');
		return false;
	}
	if($('#name').val()=='')
	{
		alert('作业地点名称不能为空！');
		return false;
	}
	return true; 
}
</script>