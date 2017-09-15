<?php
/**
 * cfs管理业务类
 * 箱审核超重管理
 */

namespace Common\Model;
use Think\Model;

class CfsCtnOverweightReviewedModel extends Model
{
	
	//验证规则
	protected $_validate = array(
			array('instruction_id','require','指令ID不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('instruction_id','is_positive_int','指令ID必须为正整数',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('ctn_id','require','箱ID不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('ctn_id','is_positive_int','箱ID必须为正整数',self::EXISTS_VALIDATE,'function'),      //存在即验证，必须为正整数
			array('reason','require','原因不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
	);
}