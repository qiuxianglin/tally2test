<head>
<title>新增客户</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form action="__ACTION__" method="post" onsubmit="return check()">
		  <table class="frame_form" cellpadding="0" cellspacing="0" width="50%">
		    <tbody>
		      <tr>
		        <th width="100">客户代码：</th>
		        <td>
		          <input class="input fl" type="text" name="customer_code" id="code" size="25">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>登录密码：</th>
		        <td>
		          <input class="input fl" type="password" name="customer_pwd" id="pwd" size="25" value="88888888">
		          <em class="error tips">初始密码为88888888</em>
		        </td>
		      </tr>
		      <tr>
		        <th>客户名称：</th>
		        <td>
		          <input class="input fl" type="text" name="customer_name" id="name" size="25" required="required">
		          <em class="error tips">必须填</em>
		        </td>
		      </tr>
		      <tr>
		        <th>客户简称：</th>
		        <td>
		          <input class="input fl" type="text" name="customer_shortname" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>客户类别：</th>
		        <td>
		        <?php 
		        $i=0;
		        foreach ($customer_category as $key=>$value)
		        {
		        	if($i==0)
		        	{
		        		$check='checked';
		        	}else {
		        		$check='';
		        	}
		        	echo '<input type="radio" name="category" size="25" value="'.$value.'" '.$check.'>'.$customer_category_d[$value].'&nbsp;';
		        	$i++;
		        }
		        ?>
		        </td>
		      </tr>
		      <tr>
		        <th>结算方式：</th>
		        <td>
		        <?php 
		        $i=0;
		        foreach ($customer_paytype as $key=>$value)
		        {
		        	if($i==0)
		        	{
		        		$check='checked';
		        	}else {
		        		$check='';
		        	}
		        	echo '<input type="radio" name="paytype" size="25" value="'.$value.'" '.$check.'>'.$customer_paytype_d[$value].'&nbsp;';
		        	$i++;
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
		           	echo '<option value="'.$r['id'].'">'.$r['code'].'</option>';
		           }
		           ?>
		          </select>
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
		        <th>合同编号：</th>
		        <td>
		          <input class="input fl" type="text" name="contract_number" size="25">
		        </td>
		      </tr>
		      <tr>
		        <th>合同有效期：</th>
		        <td>
		          <input class="input fl" type="text" name="contract_life" size="20" required="required">
		          <em class="error tips">格式：年-月-日，如2018-08-08</em>
		        </td>
		      </tr>
		      <tr>
		        <th>客户状态：</th>
		        <td>
		          <?php 
		          $i=0;
		          foreach ($customer_status as $key=>$value)
		          {
		        	if($i==0)
		        	{
		        		$check='checked';
		        	}else {
		        		$check='';
		        	}
		        	echo '<input type="radio" name="customer_status" size="25" value="'.$value.'" '.$check.'>'.$customer_status_d[$value].'&nbsp;';
		        	$i++;
		        }
		        ?>
		        </td>
		      </tr>
		      <tr>
		        <th></th>
		        <td>
		          <input type="submit" value="新增客户" class="sub">
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
	if($('#pwd').val()=='')
	{
		alert('登录密码不能为空！');
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