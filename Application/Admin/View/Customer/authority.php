<head>
	<title>客户管理</title>
	<style>
		.zpqx{
			height: 100px;
			weidth: 100%;
			margin-left:30px;
			margin-top:30px;
			font-size: 16px;
		}
		.subbtn{
			height: 30px;
			weidth: 100%;
			margin-left:30px;
			font-size: 16px;
		}
	</style>
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px;" class="main_box">
		<form method="post" action="{:U('Customer/authority')}">
		<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
		    <!--div>
				装拆箱理货单证可显示项权限
				<?php 
					foreach($qbzx as $k => $v){
						if(in_array($v['options'],$authority['stuff_strip'])){
							$check = 'checked="checked"';
						}else{
							$check = '';
						}
						echo '<input type="checkbox" name="stuff_strip[]" value="'.$v['options'].'" '.$check.' 
							  >'.$v['name'].' &nbsp;';
					}
				?>
					
				
			</div-->
			<div class="zpqx">
				照片查看权限<br/><br/>
				<input type="checkbox" name="photo[]" value="1" <?php if(in_array('1',$authority['photo'])){echo 'checked="checked"';}?>>基本照片 
				<input type="checkbox" name="photo[]" value="2" <?php if(in_array('2',$authority['photo'])){echo 'checked="checked"';}?>>高级照片 
			</div>
			<div class="subbtn">
			<input type="submit" value="保存">  <input type="reset" value="重置">
			</div>
		</form>
			 <div style="clear:both"></div>
		</div>
	</div>
	<include file="Public:customerleft" />
</div>