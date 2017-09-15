<?php
return array (
		'TMPL_ACTION_SUCCESS'=>'./Public/tpl/dispatch_jump.php', //自定义success和error的提示页面模板
		'TMPL_ACTION_ERROR'=>'./Public/tpl/dispatch_jump.php',
		//'SHOW_PAGE_TRACE' => true,  //开启页面Trace功能
        'URL_MODEL' =>3,
		'DB_TYPE'	=>'mysql',
		'DB_HOST'	=>'localhost',
		'DB_NAME'	=>'tally2_data',  // 数据库名
		'DB_USER'	=>'root',  //账号
		'DB_PWD'	=>'root', //密码
		'DB_PORT'	=>'3306',
		'DB_CHARSET' => 'utf8',
		'DB_PREFIX'	=>'tally_', //数据表前缀
		'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
);