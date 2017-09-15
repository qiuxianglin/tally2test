<?php
/**
 * 门到门拆箱业务类
 * 作业后箱残损管理类
 */

namespace Common\Model;
use Think\Model;

class DdCtnDamageAfterImgModel extends Model
{
	//验证规则
	protected $_validate = array(
		array('operation_id','require','请选择箱残损照片所属的箱子',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('operation_id','is_positive_int','请选择箱残损照片所属的箱子',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
		array('img','require','作业中箱残损照片不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('img','1,255','作业中箱残损照片路径不正确',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不能超过255个字符	
	);
}

?>