<head>
<title>费率明细列表</title>
</head>
<script type="text/javascript">
</script>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/add" class="on">新增费率明细</a>
		      </ul>
		    </div>
		    <table class="search_table">
		      <tbody>
		        <tr>
		          <td>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="RateDetail">
		           <input type="hidden" name="a" value="index">
		              箱尺寸：<select name="container_size">
		             <option value="">请选择箱尺寸</option>
		             <?php 
		              foreach ($clist as $cl)
		              {
		            	echo '<option value="'.$cl['ctn_size'].'">'.$cl['ctn_size'].'</option>';
		              }
		             ?>
		             </select>
		              箱型：<select name="container_type">
		             <option value="">请选择箱型</option>
		             <?php 
		              foreach ($clist2 as $cl2)
		              {
		            	echo '<option value="'.$cl2['ctn_type'].'">'.$cl2['ctn_type'].'</option>';
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
				    <th>箱尺寸</th>
					<th>箱型</th>
					<th>整箱费率</th>
					<th>拼箱费率</th>
					<th class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php
			     foreach ($list as $r)
			     {
			     	echo '<tr>
			               <td>'.$r['id'].'</td>
				           <td>'.$r['container_size'].'</td>
		        		   <td>'.$r['container_type'].'</td>
		        		   <td>'.$r['full_rate'].'</td>
						   <td>'.$r['mixed_rate'].'</td>
			    		   <td class="textcenter">
			    		    <a href="__CONTROLLER__/edit/id/'.$r['id'].'">查看/编辑</a>
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
	<include file="Public:rateleft" />
</div>