<head>
<title>箱主信息维护</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/add" class="on">新增箱主</a>
		      </ul>
		    </div>
		    <table class="search_table" width="100%">
		      <tbody>
		        <tr>
		          <td>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="ContainerMaster">
		           <input type="hidden" name="a" value="index">
		             箱主代码：<input type="text" name="code" class="input-text">
		              箱主名称：<input type="text" name="name" class="input-text">
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
			      <th>箱主代码</th>
			      <th>箱主名称</th>
			      <th class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     foreach ($cList as $cl)
			     {
			     	echo '<tr>
			               <td>'.$cl['id'].'</td>
				           <td>'.$cl['ctn_master_code'].'</td>
		        		   <td>'.$cl['ctn_master'].'</td>
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