<head>
<title>费率本列表</title>
</head>
<script type="text/javascript">
</script>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/add" class="on">新增费率本</a>
		      </ul>
		    </div>
		    <table class="search_table">
		      <tbody>
		        <tr>
		          <td>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="Rate">
		           <input type="hidden" name="a" value="index">
		             计费代码：<input type="text" name="code" class="input-text" style="width:90px">
		             费率名称：<input type="text" name="name" class="input-text" style="width:90px">
		             状态：<select name="flag">
		             <option value="">全部</option>
		             <?php 
		          foreach ($rate_flag as $key=>$value)
		          {
		          	echo '<option value="'.$value.'">'.$rate_flag_d[$value].'</option>';
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
			        <th width="2%">ID</th>
				    <th width="5%">计费代码</th>
					<th width="7%">费率名称</th>
					<th width="3%">折扣率</th>
					<th width="3%">税率</th>
					<th width="4%">采用分档优惠</th>
					<th width="3%">一档金额</th>
					<th width="5%">一档折扣率</th>
					<th width="3%">二档金额</th>
					<th width="5%">二档折扣率</th>
					<th width="3%">三档金额</th>
					<th width="5%">三档折扣率</th>
					<th width="3%">四档金额</th>
					<th width="5%">四档折扣率</th>
					<th width="3%">五档金额</th>
					<th width="5%">五档折扣率</th>
					<th width="6%" class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     foreach ($ratelist as $r)
			     {
			     	//状态
			     	$flag_str=$rate_flag_d[$r['flag']];
			     	
			     	echo '<tr>
			               <td>'.$r['id'].'</td>
				           <td>'.$r['code'].'</td>
		        		   <td>'.$r['name'].'</td>
		        		   <td>'.$r['discount'].'</td>
						   <td>'.$r['tax_rate'].'</td>
		        		   <td>'.$flag_str.'</td>
						   <td>'.$r['first_amount'].'</td>
						   <td>'.$r['first_rate'].'</td>
					       <td>'.$r['second_amount'].'</td>
						   <td>'.$r['second_rate'].'</td>
						   <td>'.$r['third_amount'].'</td>
						   <td>'.$r['third_rate'].'</td>
					       <td>'.$r['fourth_amount'].'</td>
						   <td>'.$r['fourth_rate'].'</td>
					       <td>'.$r['fifth_amount'].'</td>
						   <td>'.$r['fifth_rate'].'</td>
			    		   <td class="textcenter">
			    		    <a href="__CONTROLLER__/edit/id/'.$r['id'].'">查看/编辑</a>
			    		   </td>
		        		  </tr>';
			     }
			     /* |<a href="javascript:;" onclick="del('.$u['uid'].')">删除</a> */
			     ?>
			   </tbody>
			  </table>
			</div>
			<div class="pages">{$page}</div>
		</div>
	</div>
	<include file="Public:rateleft" />
</div>