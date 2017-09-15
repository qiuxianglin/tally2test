<?php
/**
 * 基础类
 * 船代信息维护类
 */
namespace Common\Model;
use Think\Model;

class ShipAgentModel extends Model
{	
	//验证规则
	protected $_validate = array(
			array('code','require','船代代码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('code','preg_match_chinese','船代代码不能使用中文！',self::EXISTS_VALIDATE,'function'),  //存在即验证，不准使用中文
			array('code','1,20','船代代码不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过20个字符
			array('name','require','船代中文名称不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('name','1,50','船代中文名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过50个字符
			array('name_en','require','船代英文名称不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('name_en','1,50','船代英文名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过50个字符
			array('enterprise_code','1,50','企业代码不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过50个字符
			array('address','1,100','地址不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过100个字符
			array('postcode','1,20','邮编不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过20个字符
			array('contacter','1,20','联系人不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过20个字符
			array('telephone','1,50','电话不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过50个字符
			array('fax','1,50','传真不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过50个字符
			array('telex','1,50','电传不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过50个字符
			array('email','email','邮箱格式不正确！',self::VALUE_VALIDATE),  //值不为空的时候验证，必须为邮箱格式
			array('remark','1,200','备注不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过200个字符
	);
	
	/**
	 * 获取船代列表
	 * @return array
	 */
	public function getShipAgentList()
	{
		$shipAgentList=$this->order('id desc')->select();
		return $shipAgentList;
	}
	
	/**
	 * 获取船代信息
	 * @param int $id:船代ID
	 * @return array
	 */
	public function getShipAgentMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		return $msg;
	}
}