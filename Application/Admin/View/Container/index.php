<head>
<title>箱型信息维护</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/add" class="on">新增箱型</a>
		      </ul>
		    </div>
		    <table class="search_table" width="100%">
		      <tbody>
		        <tr>
		          <td>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="Container">
		           <input type="hidden" name="a" value="index">
		             箱型代码：<input type="text" name="code" class="input-text">
		              箱型：<input type="text" name="type" class="input-text">
		              箱尺寸：<input type="text" name="size" class="input-text">
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
			      <th>箱型代码</th>
			      <th>箱型</th>
			      <th>箱尺寸</th>
			      <th class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     foreach ($cList as $cl)
			     {
			     	echo '<tr>
			               <td>'.$cl['id'].'</td>
				           <td>'.$cl['ctn_type_code'].'</td>
		        		   <td>'.$cl['ctn_type'].'</td>
		        		   <td>'.$cl['ctn_size'].'</td>
		        		   <td class="textcenter">
			    		    <a href="__CONTROLLER__/edit/id/'.$cl['id'].'">查看/编辑</a>
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
	<include file="Public:containerleft" />
</div>