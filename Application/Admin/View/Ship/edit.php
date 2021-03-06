<head>
<title>编辑船舶</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__/id/{$msg['id']}" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">船舶代码：</th>
		        <td>
		          <input class="input fl" type="text" name="ship_code" id="code" size="25" value="{$msg['ship_code']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>中文船名：</th>
		        <td>
		          <input class="input fl" type="text" name="ship_name" id="name" size="25" value="{$msg['ship_name']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>英文船名：</th>
		        <td>
		          <input class="input fl" type="text" name="ship_english_name" id="name_en" size="25" value="{$msg['ship_english_name']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>类型：</th>
		        <td>
		          <select name="ship_type">
		            <?php 
		            foreach ($ship_type as $key=>$value)
		            {
		            	if($msg['ship_type']==$value)
		            	{
		            		$select='selected';
		            	}else {
		            		$select='';
		            	}
		            	echo '<option value="'.$value.'" '.$select.'>'.$ship_type_d[$value].'</option>';
		            }
		            ?>
		           </select>
		        </td>
		      </tr>
		      <tr>
		        <th>航线：</th>
		        <td>
		          <select name="ship_route">
		            <?php 
		            foreach ($ship_line as $key=>$value)
		            {
		            	if($msg['ship_route']==$value)
		            	{
		            		$select='selected';
		            	}else {
		            		$select='';
		            	}
		            	echo '<option value="'.$value.'" '.$select.'>'.$ship_line_d[$value].'</option>';
		            }
		            ?>
		           </select>
		        </td>
		      </tr>
		      <tr>
		        <th>仓数：</th>
		        <td>
		          <input class="input fl" type="text" name="warehouse_number" size="25" value="{$msg['warehouse_number']}">
		          <em class="error tips">填写数字</em>
		        </td>
		      </tr>
		      <tr>
		        <th>IMO号：</th>
		        <td>
		          <input class="input fl" type="text" name="imo" size="25" value="{$msg['imo']}">
		        </td>
		      </tr>
		      <tr>
		        <th>是否为班轮：</th>
		        <td>
		          <?php 
		            foreach ($ship_regular as $key=>$value)
		            {
		            	if($msg['regular_ship']==$value)
		            	{
		            		$check='checked';
		            	}else {
		            		$check='';
		            	}
		            	echo '<input type="radio" name="regular_ship" value="'.$value.'" '.$check.'>'.$ship_regular_d[$value].'&nbsp;';
		            }
		          ?>
		        </td>
		      </tr>
		      <tr>
		        <th>国籍：</th>
		        <td>
		          <input class="input fl" type="text" name="nationality" size="25" value="{$msg['nationality']}">
		        </td>
		      </tr>
		      <tr>
		        <th>联系人：</th>
		        <td>
		          <input class="input fl" type="text" name="linkman" size="25" value="{$msg['linkman']}">
		        </td>
		      </tr>
		      <tr>
		        <th>联系电话：</th>
		        <td>
		          <input class="input fl" type="text" name="telephone" size="25" value="{$msg['telephone']}">
		        </td>
		      </tr>
		      <tr>
		        <th>船代：</th>
		        <td>
		          <select name="agent_id">
		            <option value="">请选择船代</option>
		            <?php
		            foreach ($shipAgentList as $sl)
		            {
		            	if($msg['agent_id']==$sl['id'])
		            	{
		            		$select='selected';
		            	}else {
		            		$select='';
		            	}
		            	echo '<option value="'.$sl['id'].'" '.$select.'>'.$sl['name'].'</option>';
		            }
		            ?>
		          </select>
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="编辑船舶" class="sub">
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
		alert('船舶代码不能为空！');
		return false;
	}
	if($('#name').val()=='')
	{
		alert('中文船名不能为空！');
		return false;
	}
	if($('#name_en').val()=='')
	{
		alert('英文船名不能为空！');
		return false;
	}
	return true; 
}
</script>