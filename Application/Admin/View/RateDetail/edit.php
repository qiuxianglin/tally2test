<head>
<title>编辑费率明细</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__/id/{$msg['id']}" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">箱尺寸：</th>
		        <td>
		          <select name="container_size">
		            <?php 
		            foreach ($clist as $cl)
		            {
		            	if($msg['container_size']==$cl['ctn_size'])
		            	{
		            		$select='selected';
		            	}else {
		            		$select='';
		            	}
		            	echo '<option value="'.$cl['ctn_size'].'" '.$select.'>'.$cl['ctn_size'].'</option>';
		            }
		            ?>
		          </select>
		        </td>
		      </tr>
		      <tr>
		        <th width="100">箱型：</th>
		        <td>
		          <select name="container_type">
		            <?php 
		            foreach ($clist2 as $cl2)
		            {
		            	if($msg['container_type']==$cl2['ctn_type'])
		            	{
		            		$select='selected';
		            	}else {
		            		$select='';
		            	}
		            	echo '<option value="'.$cl2['ctn_type'].'" '.$select.'>'.$cl2['ctn_type'].'</option>';
		            }
		            ?>
		          </select>
		        </td>
		      </tr>
		      <tr>
		        <th>整箱费率：</th>
		        <td>
		          <input class="input fl" type="text" name="full_rate" size="25" value="{$msg['full_rate']}">
		        </td>
		      </tr>
		      <tr>
		        <th>拼箱费率：</th>
		        <td>
		          <input class="input fl" type="text" name="mixed_rate" size="25" value="{$msg['mixed_rate']}">
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="编辑费率明细" class="sub">
		          &nbsp;&nbsp;<input type="reset" value="重置" class="res">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		 </form>
		</div>
	</div>
	<include file="Public:rateleft" />
</div>
<script>
function check()
{
	if($('#code').val()=='')
	{
		alert('计费代码不能为空！');
		return false;
	}
	return true; 
}
</script>