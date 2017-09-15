<?php
/**
 * 公用业务类
 * 派工明细管理类
 */
namespace Common\Model;
use Think\Model;

class DispatchDetailModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('dispatch_id','require','派工记录不能为空',self::EXISTS_VALIDATE), //存在即验证,不能为空
			array('dispatch_id','is_positive_int','派工记录不存在',self::EXISTS_VALIDATE,'function'),//存在即验证，必须为正整数
			array('clerk_id','require','理货员不能为空',self::EXISTS_VALIDATE), //存在即验证,不能为空
			array('clerk_id','is_positive_int','理货员不存在',self::EXISTS_VALIDATE,'function'),//存在即验证，必须为正整数
			array('dispatch_time','require','派工时间不能为空',self::EXISTS_VALIDATE), //存在即验证，不能为空
			array('dispatch_time','is_datetime','派工时间不是正确的时间格式',self::EXISTS_VALIDATE,'function'),//存在即验证，必须为正确的时间格式
	);
	/*
	 * 获取派工的人员列表
	 * @param string $dispatch_id :派工ID
	 * @param array
	 * */
	public function getclerkidlist($dispatch_id){
		$arr = $this->where("dispatch_id = '$dispatch_id'")->field('clerk_id')->select();
		foreach($arr as $vo){
			$res[] =$vo['clerk_id'];
		}
		return $res;
	}
}
?>