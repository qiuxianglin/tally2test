<head>
<title>编辑部门</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__/id/{$msg['id']}" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">部门代码：</th>
		        <td>
		          <input class="input fl" type="text" name="department_code" id="code" size="25" value="{$msg['department_code']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>部门名称：</th>
		        <td>
		          <input class="input fl" type="text" name="department_name" id="name" size="25" value="{$msg['department_name']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>上级部门：</th>
		        <td>
		          <select name="pid">
		            <option value="0">顶级部门</option>
		            <?php 
		            foreach($deptList as $dl)
		            {
		            	if($msg['id']!=$dl['id'])
		            	{
		            		if($msg['pid']==$dl['id'])
		            		{
		            			$select='selected';
		            		}else {
		            			$select='';
		            		}
		            		echo '<option value="'.$dl['id'].'" '.$select.'>'.$dl['department_name'].'</option>';
		            	}
		            }
		            ?>
		          </select> 
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="编辑部门" class="sub">
		          &nbsp;&nbsp;<input type="reset" value="重置" class="res">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		 </form>
		</div>
	</div>
	<include file="Public:deptleft" />
</div>
<script>
function check()
{
	if($('#code').val()=='')
	{
		alert('部门代码不能为空！');
		return false;
	}
	if($('#name').val()=='')
	{
		alert('部门名称不能为空！');
		return false;
	}
	return true; 
}
</script>