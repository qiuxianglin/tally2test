<?php

/**
 * 工班管理类
 * 交接班详情
 */

namespace Common\Model;
use Think\Model;

class ShiftDetailModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('exchanged_id','1,20','交班工班不超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空的时候验证，长度不能超过20个字符
			array('user_exchanged_id','is_positive_int','交班理货长不存在',self::VALUE_VALIDATE,'function'),//值不为空的时候验证，必须为正整数
			array('exchanged_time','is_datetime','交班时间不是正确的时间格式',self::VALUE_VALIDATE,'function'),//值不为空的时候验证，必须为正确的时间格式
			array('carryon_id','1,20','接班工班不超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空的时候验证，长度不能超过20个字符
			array('user_carryon_id','is_positive_int','接班理货长不存在',self::VALUE_VALIDATE,'function'),//值不为空的时候验证，必须为正整数
			array('carryon_time','is_datetime','接班时间不是正确的时间格式',self::VALUE_VALIDATE,'function'),//值不为空的时候验证，必须为正确的时间格式
			array('note','1,255','交班信息不超过255个字符',self::VALUE_VALIDATE,'length'),//值不为空的时候验证，长度不能超过255个字符	
	);
}