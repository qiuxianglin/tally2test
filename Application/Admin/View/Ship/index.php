<head>
<title>船舶信息维护</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
			<div class="mainnav_title">
				<ul>
					<a href="__CONTROLLER__/add" class="on">新增船舶</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tbody>
					<tr>
						<td>
							<form action="" method="get">
								<input type="hidden" name="p" value="1">
								<input type="hidden" name="c" value="Ship">
		                        <input type="hidden" name="a" value="index">
								船舶代码：
								<input type="text" name="code" class="input-text">
								中文船名：
								<input type="text" name="name" class="input-text">
								类别：
								 <select name="ship_type" style="width:150px;height:22px;background:#fff;">
								 <option value="">请选择</option>
						            <?php 
						            foreach ($ship_type as $key=>$value)
						            {
						            	echo '<option value="'.$value.'">'.$ship_type_d[$value].'</option>';
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
							<th width="4%">ID</th>
							<th width="7%">船舶代码</th>
							<th width="9%">中文船名</th>
							<th width="11%">英文船名</th>
							<th width="6%">类型</th>
							<th width="6%">航线</th>
							<th width="5%">仓数</th>
							<th width="10%">IMO号</th>
							<th width="6%">班轮</th>
							<th width="10%">国籍</th>
							<th width="6%">联系人</th>
							<th width="10%">联系电话</th>
							<th class="textcenter">操作</th>
						</tr>
					</thead>
					<tbody>
			     <?php 
			     foreach ($shipList as $sl)
			     {
			     	if ($sl ['ship_type']!=='') 
			     	{
			     		$shiptype=$ship_type_d[$sl ['ship_type']];
			     	}
			     	if ($sl ['ship_route']!=='')
			     	{
			     		$shipline=$ship_line_d[$sl ['ship_route']];
			     	}
			     	$regular=$ship_regular_d[$sl ['regular_ship']];
						echo '<tr>
			               <td>' . $sl ['id'] . '</td>
				           <td>' . $sl ['ship_code'].'</td>
		        		   <td>'.$sl['ship_name'].'</td>
						   <td>'.$sl['ship_english_name'].'</td>
		        		   <td>'.$shiptype.'</td>
						   <td>'.$shipline.'</td>
		                   <td>'.$sl['warehouse_number'].'</td>
						   <td>'.$sl['imo'].'</td>
						   <td>'.$regular.'</td>
		        		   <td>'.$sl['nationality'].'</td>
		        		   <td>'.$sl['linkman'].'</td>
		        		   <td>'.$sl['telephone'].'</td>
		        		   <td class="textcenter">
			    		    <a href="__CONTROLLER__/edit/id/'.$sl['id'].'">查看/编辑</a>
			    		   </td>
		        		  </tr>';
			     }
			     ?>
			   </tbody>
				</table>

			</div>
			<div class="pages">{$page}</div>
		</div>
	</div>
	<include file="Public:shipleft" />
</div>