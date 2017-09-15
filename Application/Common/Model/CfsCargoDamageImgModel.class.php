<?php
/**
 * CFS装箱业务类
 * 货残损管理类
 */

namespace Common\Model;
use Think\Model;

class CfsCargoDamageImgModel extends Model
{
	//验证规则
	protected $_validate = array(
		array('level_id','require','关不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('level_id','is_positive_int','关必须为正整数',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
		array('level_num','is_natural_num','关数必须为自然数',self::VALUE_VALIDATE,'function'),//值不为空即验证 必须为自然数
		array('img','1,255','残损照片长度不能超过255个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证  长度不能超过255个字符	
	);
}