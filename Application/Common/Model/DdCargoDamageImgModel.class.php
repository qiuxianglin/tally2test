<?php
/**
 * 门到门拆箱业务类
 * 货残损管理类
 */

namespace Common\Model;
use Think\Model;

class DdCargoDamageImgModel extends Model
{
	//验证规则
	protected $_validate = array(
		array('level_id','require','请选择货残损照片所属的关',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('level_id','is_positive_int','请选择货残损照片所属的关',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
		array('img','require','货残损照片不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('img','1,255','货残损照片路径不正确',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不能超过255个字符	
	);
}