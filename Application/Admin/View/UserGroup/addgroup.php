<head>
<title>添加用户组</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/ad/css/system.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css" />
</head>
<div id="Main_content">
	<div id="MainBox">
		<div style="margin-left: 224px" class="main_box">
			<div style="margin-top: 20px"></div>
			<form action="__ACTION__" method="post">
        	<table border="0" cellspacing="0" cellpadding="0" style="margin-left:0px">
              <tr>
                <td width="120" height="36" align="right" valign="middle">用户组名：</td>
                <td><input style="width:300px" type="text" name="title" value=""/>&nbsp;<span>*{$error1}</span></td>
              </tr>
              <tr>
                <td align="right" valign="middle">是否开启：</td>
                <td>
                   <?php 
                   $i=0;
                   foreach ($usergroup_status as $k=>$v)
                   {
                   	if($i==0)
                   	{
                   		$check='checked';
                   	}else {
                   		$check='';
                   	}
                   	echo '<input name="status" type="radio" value="'.$v.'" '.$check.'/>'.$usergroup_status_d[$v].'&nbsp;';
                   	$i++;
                   }
                   ?>
                </td>
              </tr>
            </table>
            <div class="rulelist">
               <div class="ch" style="padding-left: 0px;">
                 <h1>权限列表</h1>
               </div>
              <?php 
                 foreach($rlist as $r)
                 {
                    if($r['pid']==0)
                    {
                       $bcss='border-bottom: 1px solid #999;';
                    }else {
                       $bcss='';
                    }
                    $rid=$r['id'];
                    $pleft=$r['lvl']*20;
                    $plcss='padding-left:'.$pleft.'px';
                    echo '<div class="ch" style="'.$bcss.' '.$plcss.'">
                 <input type="checkbox" name="rules[]" value="'.$rid.'"> '.$r['title'].'
               </div>';
                 }
              ?>
            </div>
            <table>
              <tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="qr" value="确&nbsp;认" />&nbsp;&nbsp;&nbsp;<input type="reset" class="qr" value="重&nbsp;置" />&nbsp;&nbsp;&nbsp;</td>
              </tr>
            </table>
        </form>

		</div>
	</div>
	<include file="Public:userleft" />
</div>