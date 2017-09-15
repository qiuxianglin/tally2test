<?php
/**
 * 基础类
 * 船舶信息维护类
 */
namespace Common\Model;
use Think\Model;

class ShipModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('ship_code','require','船舶代码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('ship_code','preg_match_chinese','船舶代码不能使用中文！',self::EXISTS_VALIDATE,'function'),  //存在即验证，不准使用中文
			array('ship_code','1,20','船舶代码不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过20个字符
			array('ship_name','require','船舶名称不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('ship_name','1,20','船舶名称不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过20个字符
			array('ship_english_name','1,100','船舶英文名称不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，长度不超过100个字符
			array('ship_type',array(1,9),'船舶类型不正确！',self::VALUE_VALIDATE,'between'),  //值不为空的时候验证 ，范围在1-9之间的数字
			array('nationality','1,20','国籍不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过20个字符
			array('linkman','1,20','联系人不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过20个字符
			array('telephone','1,20','联系方式不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过20个字符
			array('ship_route',array(1,3),'船舶航线不正确！',self::VALUE_VALIDATE,'between'),  //值不为空的时候验证 ，范围在1-3之间的数字
			array('regular_ship',array(1,2),'请选择是否为班轮！',self::VALUE_VALIDATE,'between'),  //值不为空的时候验证 ，范围在1-2之间的数字
			array('warehouse_number','number','仓数必须为数字！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，必须为数字
			array('imo','1,100','IMO号不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，长度不超过100个字符
			array('agent_id','is_positive_int','请选择正确的船代！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须为正整数
	);
	
	/**
	 * 获取船舶列表
	 * @param int $type 船舶类型：默认空，查找全部 1集装箱船 2杂货船 3散货船 4滚装船 5油船 6木材船 7冷藏船 8危险品船 9货驳船
	 * @return array
	 */
	public function getShipList($type='')
	{
		if($type!=='')
		{
			$where=array(
					'ship_type'=>$type
			);
		}else {
			$where='1';
		}
		$shipList=$this->where($where)->order('ship_code asc')->select();
		return $shipList;
	}
	
	/**
	 * 获取船舶信息
	 * @param int $id 船舶ID
	 * @return array 一条船舶详情记录
	 */
	public function getShipMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		return $msg;
	}
	
	/**
	 * 判断船名是否存在
	 * @param string $shipname:船名
	 * @return boolean
	 */
	public function is_exist($shipname)
	{
		$res=$this->where("ship_name='$shipname' or ship_english_name='$shipname'")->field('id')->find();
		if($res['id']!='')
		{
			return true;
		}else {
			return false;
		}
	}
}
?>
