<?php
/**
 * 基础类
 * 计费维护-费率本管理类
 */
namespace Common\Model;
use Think\Model;

class RateModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('code','require','计费代码不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空	
			array('code','preg_match_chinese','计费代码不能使用中文',self::EXISTS_VALIDATE,'function'),//存在即验证，不能为中文
			array('code','1,20','计费代码长度不能超过20个字符',self::EXISTS_VALIDATE,'length'),//存在即验证，长度不能超过20个字符
			array('name','1,30','费率名称长度不能超过30个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证，长度不能超过30个字符
			array('discount','is_decimal','折扣率必须为不大于1的小数',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为不大于1的小数
			array('tax_rate','is_decimal','税率必须为不大于1的小数',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为不大于1的小数
			array('flag',array('Y','N'),'请选择是否使用分档',self::EXISTS_VALIDATE,'in'),//存在验证，只能是Y是 N否
			array('first_amount','currency','一档金额必须为货币',self::VALUE_VALIDATE),//值不为空验证，必须为货币形式
			array('first_rate','is_decimal','一档折扣率必须为不大于1的小数',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为不大于1的小数
			array('second_amount','currency','二档金额必须为货币',self::VALUE_VALIDATE),//值不为空验证，必须为货币形式
			array('second_rate','is_decimal','二档折扣率必须为不大于1的小数',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为不大于1的小数
			array('third_amount','currency','三档金额必须为货币',self::VALUE_VALIDATE),//值不为空验证，必须为货币形式
			array('third_rate','is_decimal','三档折扣率必须为不大于1的小数',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为不大于1的小数
			array('fourth_amount','currency','四档金额必须为货币',self::VALUE_VALIDATE),//值不为空验证，必须为货币形式
			array('fourth_rate','is_decimal','四档折扣率必须为不大于1的小数',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为不大于1的小数
			array('fifth_amount','currency','五档金额必须为货币',self::VALUE_VALIDATE),//值不为空验证，必须为货币形式
			array('fifth_rate','is_decimal','五档折扣率必须为不大于1的小数',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为不大于1的小数
	);
	
	/**
	 * 获取费率本列表
	 * @param string $status 采用分档优惠标志：默认空，查找全部;Y 采用 N 禁用
	 * @return array
	 */
	public function getRateList($flag='')
	{
		if($flag!=='')
		{
			$where=array(
					'flag'=>$flag
			);
		}else {
			$where='1';
		}
		$rateList=$this->where($where)->select();
		return $rateList;
	}
	
	/**
	 * 获取费率本详情
	 * @param int $id 费率本ID
	 * @return array 一条费率本详情记录
	 */
	public function getRateMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		return $msg;
	}
	
	/**
	 * 计算应付实际总价
	 * @param int $id:费率标准ID
	 * @param int $totalPrice:原价
	 * @return number 应付总价
	 */
	public function due($id,$totalPrice)
	{
		if($totalPrice>0)
		{
			$res=$this->getRateMsg($id);
			if($res!==false)
			{
				if($res['flag']=='Y')
				{
					//采用分档优惠
					//分档税率
					$rate_level=array(
							1 => $res['first_rate'],
							2 => $res['second_rate'],
							3 => $res['third_rate'],
							4 => $res['fourth_rate'],
							5 => $res['fifth_rate'],
					);
					//分档金额
					$amount_level=array(
							1 => $res['first_amount'],
							2 => $res['second_amount'],
							3 => $res['third_amount'],
							4 => $res['fourth_amount'],
							5 => $res['fifth_amount'],
					);
					//计算总价所处分档
					if($totalPrice<=$amount_level[5])
					{
						for($level=1;$level<=4;$level++)
						{
							if($totalPrice<=$amount_level[$level])
							{
								//跳出循环
								break;
							}
						}
						$level=$level-1;
					}else {
						$level=5;
					}
					//计算应付实际总价
					if($level==0)
					{
						//未达标准，普通收费标准
						$due=$totalPrice*$res['discount']*(1+$res['tax_rate']);
					}else {
						$due=0;
						for($level;$level>0;$level--)
						{
							$due+=($totalPrice-$amount_level[$level])*$rate_level[$level];
							$totalPrice=$amount_level[$level];
						}
						$due=$due+$amount_level[1];
						$due=$due*(1+$res['tax_rate']);
					}
				}else {
					//普通收费标准
					$due=$totalPrice*$res['discount']*(1+$res['tax_rate']);
				}
			}else {
				return false;
			}
		}else {
			$due=0;
		}
		$due=round($due,2);
		return $due;
	}
}
?>