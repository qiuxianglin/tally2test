<?php
/**
 * CFS装箱业务类
 * 空箱照管理类
 */

namespace Common\Model;
use Think\Model;

class CfsCtnEmptyImgModel extends Model
{
	//验证规则
	protected $_validate = array(
		array('empty_img','require','空箱照片不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('empty_img','1,255','空箱照片长度不能超过255个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过255个字符
		array('operation_id','require','作业不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('operation_id','is_positive_int','作业不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数	
	);
}