<?php
/**
 * 基础类
 * 费率明细维护类
 */
namespace Common\Model;
use Think\Model;

class RateDetailModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('container_size','require','箱尺寸不能为空！',self::EXISTS_VALIDATE),  //存在即验证，不能为空
			array('container_size','1,10','箱尺寸不超过10个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过10个字符
			array('container_type','require','箱型不能为空！',self::EXISTS_VALIDATE),  //存在即验证，不能为空
			array('container_type','1,10','箱型不超过10个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过10个字符
			array('full_rate','require','整箱费率不能为空！',self::EXISTS_VALIDATE),  //存在即验证，不能为空
			array('full_rate','currency','整箱费率不能为空！',self::EXISTS_VALIDATE),  //存在即验证，必须是货币
			array('mixed_rate','require','拼箱费率不能为空！',self::EXISTS_VALIDATE),  //存在即验证，不能为空
			array('mixed_rate','currency','拼箱费率不能为空！',self::EXISTS_VALIDATE),  //存在即验证，必须是货币
	);
	
	/**
	 * 获取费率明细信息
	 * @param int $id:费率ID
	 * @return array
	 */
	public function getRateDetail($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 根据箱列表计算出总价
	 * @param array $ctns:箱列表
	 * @return 总价
	 */
	public function calculateTotalPrice($ctns)
	{
		$totalPrice=0;
		if(count($ctns)>0)
		{
			foreach ($ctns as $c)
			{
				$ctnsize=$c['CTNSIZE'];
				$ctntype=$c['CTNTYPE'];
				$flag=$c['FLFLAG'];
				$msg=$this->where("container_size='$ctnsize' and container_type='$ctntype'")->find();
				if($msg)
				{
					if($flag=='F')
					{
						$totalPrice+=$msg['full_rate'];
					}else {
						$totalPrice+=$msg['mixed_rate'];
					}
				}
			}
		}
		return $totalPrice;
	}
}