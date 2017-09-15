<head>
<title>作业地点维护</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/add" class="on">新增作业地点</a>
		      </ul>
		    </div>
		    <table class="search_table" width="100%">
		      <tbody>
		        <tr>
		          <td>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="Location">
		           <input type="hidden" name="a" value="index">
		              作业地点代码：<input type="text" name="location_code" class="input-text">
		              作业地点名称：<input type="text" name="location_name" class="input-text">
		                             类别：<select name="location_type" style="width:150px;background:#fff;">
		            <option value="">请选择</option>
		            <?php 
		            foreach ($location_type as $key=>$value)
		            {
		            	echo '<option value="'.$value.'">'.$location_type_d[$value].'</option>';
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
			      <th>作业地点代码</th>
			      <th>作业地点名称</th>
			      <th>详细地址</th>
			      <th>联系人</th>
			      <th>联系电话</th>
			      <th>类别</th>
			      <th class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     foreach ($locationlist as $ll)
			     {
			     	if($ll ['location_type']!=='')
			     	{
			     		$type = $location_type_d[$ll ['location_type']];
			     	}
			     	echo '<tr>
			               <td>'.$ll['id'].'</td>
				           <td>'.$ll['location_code'].'</td>
		        		   <td>'.$ll['location_name'].'</td>
		        		   <td>'.$ll['address'].'</td>
		        		   <td>'.$ll['linkman'].'</td>
		        		   <td>'.$ll['telephone'].'</td>
		        		   <td>'.$type.'</td>
		        		   <td class="textcenter">
			    		    <a href="__CONTROLLER__/edit/id/'.$ll['id'].'">查看/编辑</a>
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
		</div>
	</div>
	<include file="Public:operationleft" />
</div>