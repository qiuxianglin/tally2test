<?php
/**
 * 状态配置文件
 * _d(display)后缀数组代表内容展示
 */

//门到门拆箱部门组ID
$dtd_departmentid=53;
define('dtd_departmentid',53);
define('zg_departmentid',56);

//理货公司代码
define('COMPANY_CODE', '0000');

//理货云平台与港航系统对接秘钥
define('KEY', 'tally');

//箱状态
$ctn_status = array(
		'nostart' => '0',  //未开始
		'workin' => '1',   //工作中
		'finished' => '2', //已完成
		'damage' => '-1',  //箱残损
);
define('ctn_status',json_encode($ctn_status));
$ctn_status_d = array(
		'0' => '未开始',
		'1' => '工作中',
		'2' => '已铅封',
		'-1' => '箱残损'
);
define('ctn_status_d',json_encode($ctn_status_d));

//作业地点类别
$location_type = array (
		'port' => 1,                 // 港内
		'offport_resident' => 2,     // 港外常驻
		'offport_non_resident' => 3  // 港外非常驻
);
define('location_type', json_encode($location_type));
$location_type_d = array (
		1 => '港内',
		2 => '港外常驻',
		3 => '港外非常驻' 
);
define('location_type_d', json_encode($location_type_d));


//船舶类型
$ship_type = array (
		'container' => 1,       // 集装箱船
		'general_cargo' => 2,   // 杂货船
		'bulk_cargo' => 3,      // 散货船
		'ro_ro' => 4,           // 滚装船
		'tanker' => 5,          // 油船
		'timber' => 6,          // 木材船
		'refrigerator' => 7,    // 冷藏船
		'dangerous' => 8,       // 危险品船
		'barge' => 9            // 货驳船
);
define('ship_type', json_encode($ship_type));
$ship_type_d = array (
		1 => '集装箱船',
		2 => '杂货船',
		3 => '散货船',
		4 => '滚装船',
		5 => '油船',
		6 => '木材船',
		7 => '冷藏船',
		8 => '危险品船',
		9 => '货驳船' 
);
define('ship_type_d', json_encode($ship_type_d));

//船舶航线
$ship_line = array (
		'domestic_trade_feeder' => 1, // 内贸支线
		'foreign_trade_trunk' => 2,   // 外贸干线
		'foreign_trade_feeder' => 3   // 外贸支线
);
define('ship_line', json_encode($ship_line));
$ship_line_d = array (
		1 => '内贸支线',
		2 => '外贸干线',
		3 => '外贸支线'
);
define('ship_line_d', json_encode($ship_line_d));

//是否为班轮
$ship_regular = array (
		'yes' => 1,  // 班轮
		'no' => 2    // 非班轮
);
define('ship_regular', json_encode($ship_regular));
$ship_regular_d = array (
		1 => '班轮',
		2 => '非班轮'
);
define('ship_regular_d', json_encode($ship_regular_d));


//客户类别
$customer_category = array (
		'agent' => 1,  // 代理
		'owner' => 2,  // 货主
		'minato' => 3, // 港区
		'other' => 4   // 其他
);
define('customer_category', json_encode($customer_category));
$customer_category_d=array(
		1=>'代理',
		2=>'货主',
		3=>'港区',
		4=>'其他'
);
define('customer_category_d', json_encode($customer_category_d));

//客户结算方式
$customer_paytype = array (
		'offline' =>0,  // 线下结算
		'cash' => 1,   // 现结
		'month' => 2,    // 月结
		'prepay' => 3   // 预付
);
define('customer_paytype', json_encode($customer_paytype));
$customer_paytype_d = array (
		0 => '线下结算',
		1 => '现结',
		2 => '期结',
		3 => '预付' 
);
define('customer_paytype_d', json_encode($customer_paytype_d));

//客户状态
$customer_status = array (
		'valid' => 'Y',  // 正常
		'frozen' => 'N'  // 冻结
);
define('customer_status', json_encode($customer_status));
$customer_status_d = array (
		'Y' => '正常',
		'N' => '冻结'
);
define('customer_status_d', json_encode($customer_status_d));


//用户组状态
$usergroup_status = array (
		'valid' => '1',  // 开启
		'frozen' => '0'  // 关闭
);
define('usergroup_status', json_encode($usergroup_status));
$usergroup_status_d = array (
		'1' => '开启',
		'0' => '关闭'
);
define('usergroup_status_d', json_encode($usergroup_status_d));

//用户状态
$user_status = array (
		'valid' => 'Y',  // 正常
		'frozen' => 'N'  // 冻结
);
define('user_status', json_encode($user_status));
$user_status_d = array (
		'Y' => '正常',
		'N' => '冻结'
);
define('user_status_d', json_encode($user_status_d));


//费率本管理-是否采用分档优惠
$rate_flag = array (
		'valid' => 'Y',  // 使用
		'frozen' => 'N'  // 不使用
);
define('rate_flag', json_encode($rate_flag));
$rate_flag_d = array (
		'Y' => '使用',
		'N' => '不使用'
);
define('rate_flag_d', json_encode($rate_flag_d));

//箱子是否允许超重
$whether_overweight = array(
		'valid' => 'Y',  // 允许
		'frozen' => 'N'  // 不允许
);
define('whether_overweight', json_encode($whether_overweight));
$whether_overweight_d = array(
		'Y' => '允许',  // 允许
		'N' => '不允许'  // 不允许
);
define('whether_overweight_d', json_encode($whether_overweight_d));

//指令状态
$instruction_status = array (
		'not_start' => 0, // 未派工
		'start' => 1,     // 已派工
		'finish' => 2,    // 已完成
);
define('instruction_status', json_encode($instruction_status));
$instruction_status_d = array (
		0 => '未派工',
		1 => '已派工',
		2 => '已完成'
);
define('instruction_status_d', json_encode($instruction_status_d));

//对箱的操作方式
$operate_contanier_method = array (
		'man' => 0,     // 人工
		'machine' => 1  // 机械
);
define('operate_contanier_method', json_encode($operate_contanier_method));
$operate_contanier_method_d = array (
		0 => '人工',
		1 => '机械',
);
define('operate_contanier_method_d', json_encode($operate_contanier_method_d));

//起驳装箱工作步骤
$qbzx_step = array(
	'nostart'=>0 ,  //初始化
	'check'=>1,     //装箱作业信息核对
	'levelin'=>2 ,    //录关中
	'halfclosedoor'=>3,  //半关门
	'closedoor'=>4 ,     //全关门
	'finished'=>5,       //装箱作业完成，拍摄铅封照片
	'temporarily_seal'=>6,   //装箱作业完成，暂不施封
	'supplement_picture' => 7   //补充照片
);
define("qbzx_step",json_encode($qbzx_step));

//门到门拆箱工作步骤
$dd_step=array(
		'nostart' => 0,  //初始化
		'check' => 1,    //已核对完信息
		'opened' => 2,   //已开门，拍摄整箱货物照片
		'level' => 3,    //录关中
		'finished' => 4,  //已完成，拍摄空箱照片
		'supplement_picture' => 5   //补充照片
);
define('dd_step', json_encode($dd_step));

//cfs装箱工作步骤
$cfs_step = array(
		'nostart'=>0 ,  //初始化
		'check'=>1,     //装箱作业信息核对
		'levelin'=>2 ,    //录关中
		'halfclosedoor'=>3,  //半关门
		'closedoor'=>4 ,     //全关门
		'finished'=>5,       //装箱作业完成，拍摄铅封照片
		'supplement_picture' => 6,   //补充照片
		'temporarily_seal'=>7,   //装箱作业完成，暂不施封
);
define("cfs_step",json_encode($cfs_step));

//图片路径
define('IMAGE_URL', '/Public/upload'); 
//起驳装箱
define('IMAGE_QBZX', IMAGE_URL.'/qbzx');
define('IMAGE_QBZX_EMPTY',IMAGE_QBZX.'/empty/');
define('IMAGE_QBZX_CARGO',IMAGE_QBZX.'/cargo/');
define('IMAGE_QBZX_SEAL',IMAGE_QBZX.'/seal/');
define('IMAGE_QBZX_CDAMAGE',IMAGE_QBZX.'/cdamage/');
define('IMAGE_QBZX_CLOSEDOOR',IMAGE_QBZX.'/closedoor/');
define('IMAGE_QBZX_HALFCLOSEDOOR',IMAGE_QBZX.'/halfclosedoor/');
define('IMAGE_QBZX_SUPPLEMENT', IMAGE_QBZX.'/supplement/');
//门到门拆箱
define('IMAGE_DD', IMAGE_URL.'/dd');
define('IMAGE_DD_DOOR',IMAGE_DD.'/door/'); //箱门照片
define('IMAGE_DD_SEAL',IMAGE_DD.'/seal/'); //铅封照片 
define('IMAGE_DD_DAMAGE',IMAGE_DD.'/damage/'); //箱体残损照片
define('IMAGE_DD_CARGO',IMAGE_DD.'/cargo/'); //整箱货物照片
define('IMAGE_DD_CDAMAGE',IMAGE_DD.'/cdamage/'); //货物残损照片
define('IMAGE_DD_EMPTY',IMAGE_DD.'/empty/'); //空箱照片
define('IMAGE_DD_DAMAGEAFTER',IMAGE_DD.'/damageAfter/'); //作业中箱残损照片
define('IMAGE_DD_SUPPLEMENT', IMAGE_DD.'/supplement/');
//CFS装箱
define('IMAGE_CFS', IMAGE_URL.'/cfs');
define('IMAGE_CFS_EMPTY',IMAGE_CFS.'/empty/');
define('IMAGE_CFS_CARGO',IMAGE_CFS.'/cargo/');
define('IMAGE_CFS_SEAL',IMAGE_CFS.'/seal/');
define('IMAGE_CFS_CDAMAGE',IMAGE_CFS.'/cdamage/');
define('IMAGE_CFS_CLOSEDOOR',IMAGE_CFS.'/closedoor/');
define('IMAGE_CFS_HALFCLOSEDOOR',IMAGE_CFS.'/halfclosedoor/');
define('IMAGE_CFS_SUPPLEMENT', IMAGE_CFS.'/supplement/');
?>