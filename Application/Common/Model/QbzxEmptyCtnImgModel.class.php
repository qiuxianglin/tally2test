<?php
/**
 * 起泊装箱业务类
 * 空箱照片管理
 */

namespace Common\Model;
use Think\Model;

class QbzxEmptyCtnImgModel extends Model
{
	
	//验证规则
	protected $_validate = array(
		array('operation_id','require','作业不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('operation_id','is_positive_int','作业必须为正整数',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
		array('empty_picture','require','空箱照不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('empty_picture','1,255','空箱照长度不能超过255个字符',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不能超过255个字符	
	);
}