<!DOCTYPE HTML>
<html>
<head>
<title>接班开工</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/pages.css">
</head>
<body>
	<div class="tanchuang">
		<form action="__ACTION__" method="post">
			<input type="hidden" name="hand_work_id" value="{$msg['exchanged_id']}">
			<table>
				<tr>
					<td width="40%" align="right" valign="middle">交班人：</td>
					<td><font>{$msg['hand_master']}</font></td>
				</tr>

				<tr>
					<td align="right" valign="middle">交班时间：</td>
					<td><font>{$msg['exchanged_time']}</font></td>
				</tr>

				<tr>
					<td align="right" valign="middle">交班备注：</td>
					<td><font>{$msg['note']}</font></td>
				</tr>

				<tr>
					<td align="right" valign="middle">接班人：</td>
					<td><font>{$msg['succeed_master']}</font></td>
				</tr>

				<tr>
					<td align="right" valign="middle">接班时间：</td>
					<td><font>{$msg['carryon_time']}</font></td>
				</tr>
				
				<?php
					if ($msg ['user_carryon_id']=='') 
                    {
						echo '<tr><td>&nbsp;</td><td><input type="submit" class="qr" value="接&nbsp;班" /></td></tr>';
					}
				?>
			
			</table>
		</form>
	</div>
</body>