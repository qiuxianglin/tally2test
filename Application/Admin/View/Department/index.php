<head>
<title>部门管理</title>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		    <div class="mainnav_title">
		      <ul>
		        <a href="__CONTROLLER__/add" class="on">新增部门</a>
		      </ul>
		    </div>
		    <table class="search_table" width="100%">
		      <tbody>
		        <tr>
		          <td>
		           <form action="" method="get">
		           <input type="hidden" name="p" value="1">
		           <input type="hidden" name="c" value="Department">
		           <input type="hidden" name="a" value="index">
		             部门代码：<input type="text" name="code" class="input-text">
		             部门名称：<input type="text" name="name" class="input-text">
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
			      <th>部门代码</th>
			      <th>部门名称</th>
			      <th>上级部门代码</th>
			      <th class="textcenter">操作</th>
			    </tr>
			   </thead>
			   <tbody>
			     <?php 
			     foreach ($dList as $dl)
			     {
			     	$pid=$dl['pid'];
			     	$department = new \Common\Model\DepartmentModel();
			     	$res=$department->where("id='$pid'")->field('department_code')->find();
			     	$superdepartment=$res['department_code'];
			     	echo '<tr>
			               <td>'.$dl['id'].'</td>
				           <td>'.$dl['department_code'].'</td>
		        		   <td>'.$dl['department_name'].'</td>
		        		   <td>'.$superdepartment.'</td>
		        		   <td class="textcenter">
			    		    <a href="__CONTROLLER__/edit/id/'.$dl['id'].'">查看/编辑</a>
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
	<include file="Public:deptleft" />
</div>