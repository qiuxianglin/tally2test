<head>
<title>编辑客户</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__/id/{$msg['id']}" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="45%">
		    <tbody>
		      <tr>
		        <th width="100">客户代码：</th>
		        <td>
		          <input class="input fl" type="text" name="customer_code" id="code" size="25" value="{$msg['customer_code']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>登录密码：</th>
		        <td>
		          <input class="input fl" type="password" name="customer_pwd" id="pwd" size="25">
		          <em class="error tips">不填写代表保持原来密码</em>
		        </td>
		      </tr>
		      <tr>
		        <th>客户名称：</th>
		        <td>
		          <input class="input fl" type="text" name="customer_name" id="name" size="25" value="{$msg['customer_name']}">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>客户简称：</th>
		        <td>
		          <input class="input fl" type="text" name="customer_shortname" size="25" value="{$msg['customer_shortname']}">
		        </td>
		      </tr>
		      <tr>
		        <th>客户类别：</th>
		        <td>
		          <?php 
		          foreach ($customer_category as $key=>$value)
		          {
		          	if($msg['customer_category']==$value)
		          	{
		          		$check='checked';
		          	}else {
		          		$check='';
		          	}
		          	echo '<input type="radio" name="category" size="25" value="'.$value.'" '.$check.'>'.$customer_category_d[$value].'&nbsp;';
		          }
		          ?>
		         </td>
		      </tr>
		      <tr>
		        <th>结算方式：</th>
		        <td>
		          <?php 
		          foreach ($customer_paytype as $key=>$value)
		          {
		          	if($msg['paytype']==$value)
		          	{
		          		$check='checked';
		          	}else {
		          		$check='';
		          	}
		          	echo '<input type="radio" name="paytype" size="25" value="'.$value.'" '.$check.'>'.$customer_paytype_d[$value].'&nbsp;';
		          }
		          ?>
		         </td>
		      </tr>
		      <tr>
		        <th>费率标准：</th>
		        <td>
		          <select name="rate_id">
		            <option value="">请选择费率标准</option>
		           <?php 
		           foreach ($ratelist as $r)
		           {
		           	if($msg['rate_id']==$r['id'])
		           	{
		           		$select='selected';
		           	}else {
		           		$select='';
		           	}
		           	echo '<option value="'.$r['id'].'" '.$select.'>'.$r['code'].'</option>';
		           }
		           ?>
		          </select>
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
		        <th>合同编号：</th>
		        <td>
		          <input class="input fl" type="text" name="contract_number" size="25" required="required" value="{$msg['contract_number']}">
		        </td>
		      </tr>
		      <tr>
		        <th>合同有效期：</th>
		        <td>
		          <input class="input fl" type="text" name="contract_life" size="20" required="required" value="{$msg['contract_life']}">
		          <em class="error tips">格式：年-月-日，如2018-08-08</em>
		        </td>
		      </tr>
		      <tr>
		        <th>客户状态：</th>
		        <td>
		          <?php 
		          foreach ($customer_status as $key=>$value)
		          {
		          	if($msg['customer_status']==$value)
		          	{
		          		$check='checked';
		          	}else {
		          		$check='';
		          	}
		          	echo '<input type="radio" name="customer_status" size="25" value="'.$value.'" '.$check.'>'.$customer_status_d[$value].'&nbsp;';
		          }
		          ?>
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="编辑客户" class="sub">
		          &nbsp;&nbsp;<input type="reset" value="重置" class="res">
		        </td>
		      </tr>
		    </tbody>
		  </table>
		 </form>
		</div>
	</div>
	<include file="Public:customerleft" />
</div>
<script>
function check()
{
	if($('#code').val()=='')
	{
		alert('客户代码不能为空！');
		return false;
	}
	if($('#name').val()=='')
	{
		alert('客户名称不能为空！');
		return false;
	}
	return true; 
}
</script>