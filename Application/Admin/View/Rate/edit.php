<head>
<title>编辑费率本</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__/id/{$msg['id']}" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">计费代码：</th>
		        <td>
		          <input class="input fl" type="text" name="code" id="code" size="25" value="{$msg['code']}">
		        </td>
		      </tr>
		      <tr>
		        <th>费率名称：</th>
		        <td>
		          <input class="input fl" type="text" name="name" size="25" value="{$msg['name']}">
		        </td>
		      </tr>
		      <tr>
		        <th>折扣率：</th>
		        <td>
		          <input class="input fl" type="text" name="discount" size="25" value="{$msg['discount']}">
		          <em class="error tips">折扣率为0到1之间的小数，如：0.98</em>
		        </td>
		      </tr>
		      <tr>
		        <th>税率：</th>
		        <td>
		          <input class="input fl" type="text" name="tax_rate" size="25" value="{$msg['tax_rate']}">
		          <em class="error tips">税率为0到1之间的小数，如：0.06</em>
		        </td>
		      </tr>
		      <tr>
		        <th>采用分档优惠：</th>
		        <td>
		          <?php 
		          foreach ($rate_flag as $key=>$value)
		          {
		          	if($msg['flag']==$value)
		          	{
		          		$check='checked';
		          	}else {
		          		$check='';
		          	}
		          	echo '<input type="radio" name="flag" size="25" value="'.$value.'" '.$check.'>'.$rate_flag_d[$value].'&nbsp;';
		          }
		          ?>
		         </td>
		      </tr>
		      <tr>
		        <th>一档金额：</th>
		        <td>
		          <input class="input fl" type="text" name="first_amount" size="25" value="{$msg['first_amount']}">
		          <em class="error tips">单位元</em>
		        </td>
		      </tr>
		      <tr>
		        <th>一档折扣率：</th>
		        <td>
		          <input class="input fl" type="text" name="first_rate" size="25" value="{$msg['first_rate']}">
		          <em class="error tips">一/二/三/四/五档档折扣率为0到1之间的小数，如：0.95</em>
		        </td>
		      </tr>
		      <tr>
		        <th>二档金额：</th>
		        <td>
		          <input class="input fl" type="text" name="second_amount" size="25" value="{$msg['second_amount']}">
		          <em class="error tips">单位元</em>
		        </td>
		      </tr>
		      <tr>
		        <th>二档折扣率：</th>
		        <td>
		          <input class="input fl" type="text" name="second_rate" size="25" value="{$msg['second_rate']}">
		        </td>
		      </tr>
		      <tr>
		        <th>三档金额：</th>
		        <td>
		          <input class="input fl" type="text" name="third_amount" size="25" value="{$msg['third_amount']}">
		          <em class="error tips">单位元</em>
		        </td>
		      </tr>
		      <tr>
		        <th>三档折扣率：</th>
		        <td>
		          <input class="input fl" type="text" name="third_rate" size="25" value="{$msg['third_rate']}">
		        </td>
		      </tr>
		      <tr>
		        <th>四档金额：</th>
		        <td>
		          <input class="input fl" type="text" name="fourth_amount" size="25" value="{$msg['fourth_amount']}">
		          <em class="error tips">单位元</em>
		        </td>
		      </tr>
		      <tr>
		        <th>四档折扣率：</th>
		        <td>
		          <input class="input fl" type="text" name="fourth_rate" size="25" value="{$msg['fourth_rate']}">
		        </td>
		      </tr>
		      <tr>
		        <th>五档金额：</th>
		        <td>
		          <input class="input fl" type="text" name="fifth_amount" size="25" value="{$msg['fifth_amount']}">
		          <em class="error tips">单位元</em>
		        </td>
		      </tr>
		      <tr>
		        <th>五档折扣率：</th>
		        <td>
		          <input class="input fl" type="text" name="fifth_rate" size="25" value="{$msg['fifth_rate']}">
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="编辑费率本" class="sub">
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