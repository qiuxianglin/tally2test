<?php
/**
 * 门到门拆箱业务类
 * 预报计划管理
 */
namespace Common\Model;
use Think\Model;

class DdPlanModel extends Model
{
	//验证规则
	//因门到门拆箱的预报计划不做验证规则的判断
	protected $_validate = array(
		array('voyage','1,15','航次不能超过15个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 ，不能超过15个字符
		array('applycode','1,15','申报公司代码不能超过15个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 ，不能超过15个字符
		array('applyname','1,100','申报公司名称不能超过100个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 ，不能超过15个字符
		array('orderid','1,30','委托编号不能超过30个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 ，不能超过15个字符
	);
	
	/**
	 * 获取预报计划详情
	 * 含配箱列表
	 * @param int $id:预报计划ID
	 * @return array|boolean
	 */
	public function getPlanMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		if($msg!==false)
		{
			if($msg['operating_type']=='1')
			{
				$msg['operating_type_zh']='机械';
			}else {
				$msg['operating_type_zh']='人工';
			}
			if($msg['lcl'] == 'N')
			{
				$msg['lcl_zh'] = '整箱';
			}else{
				$msg['lcl_zh'] = '拼箱';
			}
			//客户代码
			$Customer=new \Common\Model\CustomerModel();
			$customer_code=$msg['paycode'];
			$res_c=$Customer->where("customer_code='$customer_code'")->field('customer_name,customer_shortname')->find();
			if($res_c)
			{
				$msg['customer_name']= $res_c['customer_name'];
				$msg['customer_shortname']= $res_c['customer_shortname'];
			}else {
				$msg['customer_name']= '';
				$msg['customer_shortname']= '';
			}
			$PlanContainer=new \Common\Model\DdPlanContainerModel();
			$ctns=$PlanContainer->getContainerList($id);
			if($ctns!==false)
			{
				$msg['ctns']=$ctns;
			}
			return $msg;
		}else {
			return false;
		}
	}
}