<head>
<title>客户管理</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/add" class="on">新增客户</a>
		      </ul>
		    </div>
		    <table class="search_table" width="100%">
		      <tbody>
		        <tr>
		          <td>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="Customer">
		           <input type="hidden" name="a" value="index">
		             客户代码：<input type="text" name="code" class="input-text" style="width:90px">
		             客户名称：<input type="text" name="name" class="input-text" style="width:90px">
		             客户类别：<select name="category">
		             <option value="">全部</option>
		             <?php 
		             foreach ($customer_category as $key=>$value)
		             {
		             	echo '<option value="'.$value.'">'.$customer_category_d[$value].'</option>';
		             }
		             ?>
		             </select>
		              结算方式：<select name="paytype">
		             <option value="">全部</option>
		             <?php 
		             foreach ($customer_paytype as $key=>$value)
		             {
		             	echo '<option value="'.$value.'">'.$customer_paytype_d[$value].'</option>';
		             }
		             ?>
		             </select>
		             状态：<select name="status">
		             <option value="">全部</option>
		             <?php 
		             foreach ($customer_status as $key=>$value)
		             {
		             	echo '<option value="'.$value.'">'.$customer_status_d[$value].'</option>';
		             }
		             ?>
		             </select>
		             <input type="submit" class="sub2" value="查询">
		           </form>
		          </td>
		        </tr>
		      </tbody>
		    </table>
			<div class="table-list">
			  <table cellspacing="0" width="100%">
			   <colgroup>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col></col>
			    <col align="center" width="180"></col>
			   </colgroup>
			   <thead>
			    <tr>
			      <th>ID</th>
			      <th width="6%">客户代码</th>
			      <th width="11%">客户名称</th>
			      <th width="8%">客户简称</th>
			      <th width="6%">客户类别</th>
			      <th width="6%">结算方式</th>
			      <th width="6%">费率标准</th>
			      <th width="5%">联系人</th>
			      <th width="10%">联系电话</th>
			      <th width="6%">合同号</th>
			      <th width="7%">合同有效期</th>
			      <th width="6%">客户状态</th>
			      <th width="7%">冻结/恢复</th>
			      <th class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     $Rate=new \Common\Model\RateModel();
			     foreach ($cList as $cl)
			     {
			     	if($cl ['customer_category']!=='')
			     	{
			     		$category = $customer_category_d[$cl ['customer_category']];
			     	}
			     	if($cl ['paytype']!=='')
			     	{
			     		$paytype = $customer_paytype_d[$cl ['paytype']];
			     	}
			     	if($cl['customer_status']==$customer_status['valid'])
			     	{
			     		$customerstatus='正常';
			     		$action='冻结客户';
			     		$color="#000";
			     		$status='N';
			     	}else {
			     		$customerstatus='冻结';
			     		$action='解冻客户';
			     		$color="red";
			     		$status='Y';
			     	}
			     	//费率标准
			     	if($cl['rate_id'])
			     	{
			     		$rate=$Rate->getRateMsg($cl['rate_id']);
			     		$ratestr=$rate['code'];
			     	}else {
			     		$ratestr='';
			     	}
			     	echo '<tr>
			               <td>'.$cl['id'].'</td>
				           <td>'.$cl['customer_code'].'</td>
		        		   <td>'.$cl['customer_name'].'</td>
		        		   <td>'.$cl['customer_shortname'].'</td>
		      		       <td>'.$category.'</td>
		        		   <td>'.$paytype.'</td>
		        		   <td>'.$ratestr.'</td>
		           		   <td>'.$cl['linkman'].'</td>
		        		   <td>'.$cl['telephone'].'</td>
		             	   <td>'.$cl['contract_number'].'</td>
		             	   <td>'.$cl['contract_life'].'</td>
		        		   <td>'.$customerstatus.'</td>
			    		   <td><a href="javascript:;" onclick="changestatus('.$cl['id'].',\''.$status.'\')" style="color:'.$color.'">'.$action.$status.'</a></td>
		        		   <td class="textcenter">
			      		    <a href="javascript:;" onclick="resetpwd('.$cl['id'].')">重置密码</a>|
			    		    <a href="__CONTROLLER__/edit/id/'.$cl['id'].'">编辑</a>|
							<a href="__CONTROLLER__/authority/id/'.$cl['id'].'">权限</a>
			    		   </td>
		        		  </tr>';
			     }
			     ?>
			   </tbody>
			  </table>
			  
			</div>
			<div class="pages">
			    {$page}
			 </div>
			 <div style="clear:both"></div>
		</div>
	</div>
	<include file="Public:customerleft" />
</div>
<script>
function changestatus(id,status)
{
	if(status=='N')
	{
		if(confirm('确定要冻结该客户吗？'))
		{
			flag=true;
		}else {
			flag=false;
		}
	}
	if(status=='Y')
	{
		if(confirm('确定要恢复该客户吗？'))
		{
			flag=true;
		}else {
			flag=false;
		}
	}
	if(flag==true)
	{
		$.ajax({
			type:"POST",
			url:"{:U('Customer/changestatus')}",
			dataType:"html",
			data:"id="+id+'&status='+status,
			success:function(msg)
			{
			    if(msg==1)
			    {
				    
				}else {
					alert('操作失败！');
				}
			    location.reload();
			 // alert(msg)
			}
		});
	}
}

function resetpwd(id)
{
	if(confirm('确定要重置该客户密码吗？'))
	{
		$.ajax({
			type:"POST",
			url:"{:U('Customer/resetpwd1')}",
			dataType:"html",
			data:"id="+id,
			success:function(msg)
			{
			    if(msg=='1')
			    {
				    alert('重置密码成功！');
				}else {
					alert('重置密码失败！');
				}
			    location.reload();
			}
		});
	}
}
</script>