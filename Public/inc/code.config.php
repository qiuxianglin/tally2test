<?php
/**
 * 返回码配置文件
 * _zh(display)后缀数组代表内容展示
 */
// 公共返回码
$error_code_common = array (
		'SUCCESS' => 0, // 成功
		'INIT' => 1, // 初始化
		'DB_ERROR' => 2, // 数据库错误
		'PARAMETER_ERROR' => 3, // 参数不正确，参数缺失
		'SIGN_ERROR' => 4, // 验签错误
		'FILE_UPLOAD_ERROR' => 5  // 文件上传失败
);

define ( 'error_code_common', json_encode ( $error_code_common ) );

$error_code_common_zh = array (
		0 => '成功',
		1 => '初始化',
		2 => '数据库错误',
		3 => '参数不正确，参数缺失',
		4 => '验签错误',
		5 => '文件上传失败' 
);

define ( 'error_code_common_zh', json_encode ( $error_code_common_zh ) );

// 用户相关返回码
$error_code_user = array (
		'USER_NOT_EXIST' => 101, // 该用户不存在
		'USER_LOGIN_ERROR' => 102, // 工号或密码错误
		'USER_PASSWORD_NOT_MATCH' => 103, // 两次密码不相同
		'USER_ORIGINALPASSWORD_ERROR' => 104, // 原始密码不正确
		'USER_FROZEN' => 105, // 该用户被冻结
		'USER_NOT_SIGNIN' => 106, // 该用户未签到
		'NEED_PERMISSION_CHIEFTALLY' => 107, // 需要理货长权限
		'NEED_PERMISSION__DEPARTMENTHEAD' => 108  // 需要部门长权限
);

define ( 'error_code_user', json_encode ( $error_code_user ) );

$error_code_user_zh = array ( // /提示信息需要对应修改
		101 => '该用户不存在',
		102 => '工号或密码错误',
		103 => '两次密码不相同',
		104 => '原始密码不正确',
		105 => '该用户被冻结',
		106 => '该用户未签到',
		107 => '需要理货长权限',
		108 => '需要部门长权限' 
);

define ( 'error_code_user_zh', json_encode ( $error_code_user_zh ) );

// 签到工班管理返回码
$error_code_shift = array (
		'SIGN_DATE_INVALID' => 201, // 签到日期无效：不准签入比今天还新的班次
		'NOT_NIGHTSHIF_TIME' => 202, // 当前时间段不可签夜班！
		'HISTORY_SHIFT_NOT_EXCHANGED' => 203, // 历史工班未交班，不能签入同部门组其它班次！
		'NOT_LAST_SHIFT' => 204, // 只允许签入最新工班！
		'SHIFT_EXCHANGED' => 205, // 该工班已交班！
		'SHIFT_NOT_EXIST' => 206, // 该工班不存在！
		'SHIFT_NEED_CHIEFTALLY' => 207, // 该工班尚无理货长！
		'NOT_ONDUTY_CHIEFTALLY' => 208, // 该用户不是当前工班理货长
		'ANY_CONTAINER_IN_OPERATION' => 209, // 该指令存在正在作业的箱子，无法取消派工！
		'SIGN_MSG_INVALID' => 210  // 您当前所在工班日期小于要接班工班！
);

define ( 'error_code_shift', json_encode ( $error_code_shift ) );

$error_code_shift_zh = array (
		201 => '签到日期无效：不准签入比今天还新的班次',
		202 => '当前时间段不可签夜班！',
		203 => '历史工班未交班，不能签入同部门组其它班次！',
		204 => '不能签入以前工班！',
		205 => '该工班已交班！',
		206 => '该工班不存在！',
		207 => '该工班尚无理货长！',
		208 => '该用户不是当前工班理货长！',
		209 => '该指令存在正在作业的箱子，无法取消派工！',
		210 => '您当前所在工班日期小于要接班工班！' 
);

define ( 'error_code_shift_zh', json_encode ( $error_code_shift_zh ) );

// 部门管理返回码
$error_code_department = array (
		'DEPARTMENT_NOT_EXIST' => 301  // 不存在该部门组
);

define ( 'error_code_department', json_encode ( $error_code_department ) );

$error_code_department_zh = array (
		301 => '不存在该部门组' 
);

define ( 'error_code_department_zh', json_encode ( $error_code_department_zh ) );

// 客户管理返回码
$error_code_customer = array (
		'CUSTOMER_NOT_EXIST' => 401, // 客户不存在
		'CUSTOMER_FROSEN' => 402,  // 该客户已被冻结
		'CUSTOMER_LOGIN_ERROR' => 403,  //客户或者密码错误
		'CUSTOMER_PASSWORD_NOT_MATCH' => 404, // 两次密码不相同
		'CUSTOMER_ORIGINALPASSWORD_ERROR' => 405 // 原始密码不正确
);

define ( 'error_code_customer', json_encode ( $error_code_customer ) );

$error_code_customer_zh = array (
		401 => '客户不存在',
		402 => '该客户已被冻结',
		403 => '客户或者密码错误',
		404 => '两次密码不相同',
		405 => '原始密码不正确'
);

define ( 'error_code_customer_zh', json_encode ( $error_code_customer_zh ) );

// 指令管理返回码
$error_code_instruction = array (
		'INSTRUCTION_NOT_EXIST' => 501, // 指令不存在
		'ALREADY_DISPATCH' => 502, // 该指令已派工，无法新增派工！
		'NEED_DISPATCH' => 503, // 该指令尚未派工，请先派工！
		'NOT_ALLOCATION_TASK' => 504  // 该理货员尚未被分配任务！
);

define ( 'error_code_instruction', json_encode ( $error_code_instruction ) );

$error_code_instruction_zh = array (
		501 => '指令不存在',
		502 => '该指令已派工，无法新增派工！',
		503 => '该指令尚未派工，请先派工！',
		504 => '该理货员尚未被分配任务！' 
);

define ( 'error_code_instruction_zh', json_encode ( $error_code_instruction_zh ) );

// 作业管理返回码
$error_code_operation = array (
		'OPERATION_ALREADY_HANDLED' => 601, // 该配箱已被其他理货员操作，不得再次操作
		'NEED_SEAL_PICTURE' => 602, // 该箱必须实际作业，请拍摄铅封照片
		'NEED_CLOSE_DOOR_PICTURE' => 603, // 该箱必须实际作业，请拍摄箱门照片
		'NEED_EMPTY_CTN_PICTURE' => 604, // 该箱必须实际作业，请拍摄空箱照片
		'NEED_CTN_DAMAGE_PICTURE' => 605, // 该箱必须实际作业，请拍摄箱残损照片
		'NEED_HALFCLOSE_DOOR_PICTURE' => 606, // 该箱必须实际作业，请拍摄半关门照片
		'NEED_OPEN_DOOR_PICTURE' => 607, // 该箱必须实际作业，请拍摄整箱货物照片
		'NEED_CARGO_PICTURE' => 610, // 该箱必须实际作业，请拍摄货物照片
		'NEED_CARGO_DAMAGE_PICTURE' => 611, // 该箱必须实际作业，请拍摄货残损照片
		'NO_REPEAT_OPERATION' => 620, // 该箱已存在操作记录，请勿重复操作！
		'OPERATION_NOT_EXIST' => 621, // 该作业记录不存在，请核实！
		'NEED_ACCEPT_TASK' => 622, // 该箱尚无操作人员，请先接单！
		'NOT_LAST_LEVEL' => 623, // 该关不是最后一关，请先删除最后一关，再进行操作！
		'HAVE_LEVEL_RECORD' => 624, // 该作业下有关操作，请先删除关，再继续操作！
		'OPREATION_FINISHED' => 625, // 该箱已完成，请勿重复操作！
		'NO_LEVEL_RECORD' => 626, // 该配箱尚未录关，无法进行操作，请先录关！
		'HAVE_THREEOPERATION_CTN' => 627, // '您已有3个正在工作的箱，不允许接更多箱进行作业！
		'NO_BARSE' => 628, // 该提单号下没有配置驳船,
		'NO_LOCATION' => 629, // '该提单号下没有配置来源场地'
		'PLAN_NOT_EXIST' => 630, // 预报计划不存在,
		'SEALNO_EXIST' => 631, // 铅封号已存在
		'NO_LOADINGREQUIRE' => 632, // 无装箱要求
		'CTN_OPERATION_NOTDEL' => 633, // 该箱已作业，不能删除
		'CTN_WORKIN' => 634, // 工作中，不可修改
		'EDITREASON_NOT_EMPTY' => 635, // 修改原因不能为空
		'NOT_EDIT' => 636,  // 没有修改项
		'EXCEED_BIG_LOAD'  => 637,   //超过最大载重
		'NOT_EMPTY'   =>  638,       //总箱数或者总货重不能为空
		'NOT_SUPPLEMENT'  => 639,     //没有拍摄补充照片
		'NOT_REVISE'   =>   640     //正常工作状态，无需修改！
);

define ( 'error_code_operation', json_encode ( $error_code_operation ) );

$error_code_operation_zh = array (
		601 => '该配箱已被其他理货员操作，不得再次操作',
		602 => '请拍摄铅封照片',
		603 => '请拍摄关门照片',
		604 => '请拍摄空箱照片',
		605 => '请拍摄箱残损照片',
		606 => '请拍摄半关门照片',
		607 => '请拍摄开门照片',
		610 => '请拍摄货物照片',
		611 => '请拍摄货残损照片',
		620 => '该箱已存在操作记录，请勿重复操作！',
		621 => '该作业记录不存在，请核实！',
		622 => '该箱尚无操作人员，请先接单！',
		623 => '该关不是最后一关，请先删除最后一关，再进行操作！',
		624 => '该作业下有关操作，请先删除关，再继续操作！',
		625 => '该箱已完成，请勿重复操作！',
		626 => '该配箱尚未录关，无法进行操作，请先录关！',
		627 => '您已有3个正在工作的箱，不允许接更多箱进行作业！',
		628 => '该提单号下没有配置驳船',
		629 => '该提单号下没有配置来源场地',
		630 => '预报计划不存在',
		631 => '该铅封号已存在',
		632 => '无装箱要求',
		633 => '该箱已作业，不能删除',
		634 => '工作中，不可修改',
		635 => '修改原因不能为空',
		636 => '没有修改项',
		637 => '超过最大载重，不允许铅封',
		638 => '总箱数或者总货重不能为空',
		639 => '没有拍摄补充照片' ,
		640 => '正常工作状态，无需修改！'
);

define ( 'error_code_operation_zh', json_encode ( $error_code_operation_zh ) );

// 基础信息管理-船舶管理返回码
$error_code_ship = array (
		'SHIPNAME_NOT_EXIST' => 701  // 该船舶名称不存在！
);

define ( 'error_code_ship', json_encode ( $error_code_ship ) );

$error_code_ship_zh = array (
		701 => '该船舶名称不存在！' 
);

define ( 'error_code_ship_zh', json_encode ( $error_code_ship_zh ) );

// 基础信息管理-作业地点管理返回码
$error_code_location = array (
		'LOCATION_NOT_EXIST' => 801  // 该作业地点不存在！
);

define ( 'error_code_location', json_encode ( $error_code_location ) );

$error_code_location_zh = array (
		801 => '该作业地点不存在！' 
);

define ( 'error_code_location_zh', json_encode ( $error_code_location_zh ) );

// 单证管理返回码
$error_code_document = array (
		'DOCUMENT_ALREADY_EXIST' => 901  // 单证已存在！
);

define ( 'error_code_document', json_encode ( $error_code_document ) );

$error_code_document_zh = array (
		901 => '单证已存在' 
);

define ( 'error_code_document_zh', json_encode ( $error_code_document_zh ) );
