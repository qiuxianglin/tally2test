<head>
<title>船代信息维护</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
			<div class="mainnav_title">
				<ul>
					<a href="__CONTROLLER__/add" class="on">新增船代</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tbody>
					<tr>
						<td>
							<form action="" method="get">
								<input type="hidden" name="p" value="1">
								<input type="hidden" name="c" value="ShipAgent">
		                        <input type="hidden" name="a" value="index">
								船代代码：
								<input type="text" name="code" class="input-text">
								中文船代名称：
								<input type="text" name="name" class="input-text">
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
							<th width="3%">ID</th>
							<th width="6%">船代代码</th>
							<th width="10%">中文船代名称</th>
							<th width="10%">英文船代名称</th>
							<th width="6%">企业代码</th>
							<th width="10%">地址</th>
							<th width="5%">邮编</th>
							<th width="6%">联系人</th>
							<th width="6%">电话</th>
							<th width="10%">传真</th>
							<th width="6%">电传</th>
							<th width="11%">邮箱</th>
							<th class="textcenter">操作</th>
						</tr>
					</thead>
					<tbody>
			     <?php 
			     foreach ($shipAgentList as $sl)
			     {
						echo '<tr>
			               <td>' . $sl ['id'] . '</td>
				           <td>' . $sl ['code'].'</td>
		        		   <td>'.$sl['name'].'</td>
						   <td>'.$sl['name_en'].'</td>
		        		   <td>'.$sl['enterprise_code'].'</td>
						   <td>'.$sl['address'].'</td>
		                   <td>'.$sl['postcode'].'</td>
						   <td>'.$sl['contacter'].'</td>
						   <td>'.$sl['telephone'].'</td>
		        		   <td>'.$sl['fax'].'</td>
		        		   <td>'.$sl['telex'].'</td>
		        		   <td>'.$sl['email'].'</td>
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