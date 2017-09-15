<?php
return array(
		'TMPL_TEMPLATE_SUFFIX' => '.php',
		'LAYOUT_ON' => true,  //开启布局模板功能
		'LAYOUT_NAME' => 'Public/layout',  //设置布局入口文件名
		'AUTH_CONFIG' => array(
				'AUTH_ON' => true, //是否开启权限
				'AUTH_TYPE' => 1, //
				'AUTH_GROUP' => 'tally_admin_group', //用户组
				'AUTH_GROUP_ACCESS' => 'tally_admin', //用户-用户组关系表
				'AUTH_RULE' => 'tally_auth_rule', //权限规则表
				'AUTH_USER' => 'tally_admin'// 管理员表
		)
);