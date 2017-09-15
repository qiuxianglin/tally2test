<?php
return array (
		'AUTH_CONFIG' => array(
				'AUTH_ON' => true, //是否开启权限
				'AUTH_TYPE' => 1, //
				'AUTH_GROUP' => 'user_group', //用户组
				'AUTH_GROUP_ACCESS' => 'user', //用户-用户组关系表
				'AUTH_RULE' => 'auth_rule_user', //权限规则表
				'AUTH_USER' => 'user'// 管理员表
		)
);