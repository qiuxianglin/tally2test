<?php
/**
 * 起驳管理业务类
 * 关货物照管理
 */

namespace Common\Model;
use Think\Model;

class QbzxLevelCargoImgModel extends Model
{
	
	//验证规则
	protected $_validate = array(
			array('level_id','require','关不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('level_id','is_positive_int','关必须为正整数',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('level_num','require','关数不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('level_num','is_positive_int','关数必须为正整数',self::EXISTS_VALIDATE,'function'),      //存在即验证，必须为正整数
			array('cargo_picture','require','残损照片不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('cargo_picture','1,255','残损照片长度不能超过255个字符',self::EXISTS_VALIDATE,'length'), //存在即验证，长度不能超过255个字符
	);
}