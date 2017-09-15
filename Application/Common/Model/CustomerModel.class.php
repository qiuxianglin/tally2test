<?php
/**
 * 基础类
 * 客户管理类
 */
namespace Common\Model;
use Think\Model;

class CustomerModel extends Model
{
	public $ERROR_CODE_COMMON =array();     // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();  // 公共返回码中文描述
	public $ERROR_CODE_CUSTOMER =array();       // 客户相关返回码
	public $ERROR_CODE_CUSTOMER_ZH =array();    // 客户相关返回码中文描述
	
	//初始化
	protected function _initialize()
	{
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_CUSTOMER = json_decode(error_code_customer,true);
		$this->ERROR_CODE_CUSTOMER_ZH = json_decode(error_code_customer_zh,true);
	}
	
	//验证规则
	protected $_validate = array(
			array('customer_code','require','客户代码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('customer_code','preg_match_chinese','客户代码不能使用中文！',self::EXISTS_VALIDATE,'function'),  //存在即验证，不准使用中文
			array('customer_code','1,10','客户代码不超过10个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过10个字符
			array('customer_pwd','require','客户密码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('customer_pwd','32','客户密码无效！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度为32个字符
			array('customer_name','1,60','客户名称不超过60个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过60个字符
			array('customer_shortname','1,20','客户简称不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过20个字符
			array('customer_category',array(1,4),'客户类别不正确！',self::VALUE_VALIDATE,'between'),  //值不为空的时候验证 ，范围在1-4之间的数字
			array('paytype',array(0,3),'结算方法不正确！',self::VALUE_VALIDATE,'between'),  //值不为空的时候验证 ，范围在0-3之间的数字
			array('rate_id','is_positive_int','请选择正确的费率标准！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须为正整数
			array('linkman','1,20','联系人不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过20个字符
			array('telephone','1,30','联系电话不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过30个字符
			array('customer_status','require','请选择是否冻结！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('customer_status',array('Y','N'),'请选择是否冻结！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('contract_number','1,30','合同编号不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过30个字符
			array('contract_life','is_date','合同有效期不是正确的时间格式！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是正确的时间格式
	);
	
	/**
	 * 获取客户列表
	 * @param int $customer_category:客户类别 1代理 2货主 3港区 4其他
	 * @param string $customer_status:客户状态 Y正常 N冻结
	 * @return array
	 */
	public function getCustomerList($customer_category='',$customer_status='Y')
	{
		$where='1';
		if(!empty($customer_category))
		{
			$where.=" and customer_category='$customer_category'";
		}
		if(!empty($customer_category))
		{
			$where.=" and customer_status='$customer_status'";
		}
		$customerList=$this->where($where)->select();
		return $customerList;
	}
	
	/**
	 * 获取客户信息
	 * @param int $id 客户ID
	 * @return array 一条客户详情记录
	 */
	public function getCustomerMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		return $msg;
	}
	
	/**
	 * 根据客户代码获取客户信息
	 * @param string $code 客户代码
	 * @return array 一条客户详情记录
	 */
	public function getMsgByCode($code)
	{
		$sql="SELECT c.*,r.code as rate_code FROM __PREFIX__customer c,__PREFIX__rate r WHERE c.customer_code='$code' AND c.rate_id=r.id";
		$msg=$this->query($sql);
		$msg=$msg[0];
		return $msg;
	}
	
	/**
	 * 判断客户是否合法
	 * @param string $code 客户代码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function is_valid($code)
	{
		$customer_status=json_decode(customer_status,true);
		$msg=$this->where("customer_code='$code'")->find();
		if($msg)
		{
			if($msg['customer_status']==$customer_status['valid'])
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'客户合法'
				);
			}else {
				// 该客户已被冻结
				$res=array(
						'code'=>$this->ERROR_CODE_CUSTOMER['CUSTOMER_FROSEN'],
						'msg'=>$this->ERROR_CODE_CUSTOMER_ZH[$this->ERROR_CODE_CUSTOMER['CUSTOMER_FROSEN']]
				);
			}
		}else {
			// 客户不存在
			$res=array(
					'code'=>$this->ERROR_CODE_CUSTOMER['CUSTOMER_NOT_EXIST'],
					'msg'=>$this->ERROR_CODE_CUSTOMER_ZH[$this->ERROR_CODE_CUSTOMER['CUSTOMER_NOT_EXIST']]
			);
		}
		return $res;
	}
	
	/**
	 * 判断客户是否存在
	 * @param string $name:客户名称
	 * @return boolean
	 */
	public function is_exist($name)
	{
		$customer_status=json_decode(customer_status,true);
		$msg=$this->where("shortcall='$name'")->find();
		if($msg['customerinfoid']!='')
		{
			if($msg['customer_status']==$customer_status['valid'])
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'客户存在'
				);
			}else {
				// 该客户已被冻结
				$res=array(
						'code'=>$this->ERROR_CODE_CUSTOMER['CUSTOMER_FROSEN'],
						'msg'=>$this->ERROR_CODE_CUSTOMER_ZH[$this->ERROR_CODE_CUSTOMER['CUSTOMER_FROSEN']]
				);
			}
		}else {
			// 客户不存在
			$res=array(
					'code'=>$this->ERROR_CODE_CUSTOMER['CUSTOMER_NOT_EXIST'],
					'msg'=>$this->ERROR_CODE_CUSTOMER_ZH[$this->ERROR_CODE_CUSTOMER['CUSTOMER_NOT_EXIST']]
			);
		}
		return $res;
	}
	
	/**
	 * 密码加密算法
	 * @param string $pwd:客户密码
	 * @return string:加密后的密文
	 */
	public function encrypt($pwd)
	{
		$password = md5($pwd.'customer'.substr($pwd,0,2));
		return $password;
	}


	/**
	 * 计算客户应付实际总价
	 * @param string $code:客户代码
	 * @param int $totalPrice:原价
	 * @return number 应付总价
	 */
	public function due($code,$totalPrice)
	{
		$msg=$this->getMsgByCode($code);
		if($msg)
		{
			$rate_id=$msg['rate_id'];
			$rate=new \Common\Model\RateModel();
			$due=$rate->due($rate_id, $totalPrice);
			return $due;
		}else {
			return false;
		}
	}

	/**
	 * 客户登录
	 * @param string $customer_code:客户代码
	 * @param string $customer_pwd:密码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:内容、说明
	 */
	public function login($customer_code,$customer_pwd)
	{
		//检验客户是否存在
		$msg=$this->where("customer_code='$customer_code'")->field('id,customer_pwd')->find();
		if($msg)
		{
			//检验原密码是否正确
			$pwd=$this->encrypt($customer_pwd);
			if($pwd != $msg['customer_pwd'])
			{
				// 客户代码或密码错误
				$res=array(
					'code'=>$this->ERROR_CODE_CUSTOMER['CUSTOMER_LOGIN_ERROR'],
					'msg'=>$this->ERROR_CODE_CUSTOMER_ZH[$this->ERROR_CODE_CUSTOMER['CUSTOMER_LOGIN_ERROR']]
				);
			}else {
				// 修改最后登录时间
				$data=array(
						'operationtime'=>date('Y-m-d H:i:s')
				);
				$this->where("customer_code='$customer_code'")->save($data);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'登录成功',
						'id'=>$msg['id']
				);
			}
		}else {
			// 客户不存在CUSTOMER_NOT_EXIST
			$res=array(
					'code'=>$this->ERROR_CODE_CUSTOMER['CUSTOMER_NOT_EXIST'],
					'msg'=>$this->ERROR_CODE_CUSTOMER_ZH[$this->ERROR_CODE_CUSTOMER['CUSTOMER_NOT_EXIST']]
			);
		}
		return $res;
	}

	/**
	 * 用户修改密码
	 * @param int $id:客户代码
	 * @param string $oldpwd:原密码
	 * @param string $pwd1:新密码
	 * @param string $pwd2:重复新密码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:内容、说明
	 */
	public function changePwd($id,$oldpwd,$pwd1,$pwd2)
	{
		if($pwd1!=$pwd2)
		{
			// 两次密码不相同
			$res=array(
					'code'=>$this->ERROR_CODE_CUSTOMER['CUSTOMER_PASSWORD_NOT_MATCH'],
					'msg'=>$this->ERROR_CODE_CUSTOMER_ZH[$this->ERROR_CODE_CUSTOMER['CUSTOMER_PASSWORD_NOT_MATCH']]
			);
		}else {
			//检验原密码是否正确
			$msg=$this->where("id=$id")->field('customer_pwd')->find();
			if($msg!='')
			{
				$oldpwd=$this->encrypt($oldpwd);
				if($oldpwd!=$msg['customer_pwd'])
				{
					// 原始密码不正确
					$res=array(
							'code'=>$this->ERROR_CODE_CUSTOMER['CUSTOMER_ORIGINALPASSWORD_ERROR'],
							'msg'=>$this->ERROR_CODE_CUSTOMER_ZH[$this->ERROR_CODE_CUSTOMER['CUSTOMER_ORIGINALPASSWORD_ERROR']]
					);
				}else {
					$data=array(
							'customer_pwd'=>$this->encrypt($pwd1)
					);
					$res1=$this->where("id=$id")->save($data);
					{
						if($res1!==false)
						{
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'修改密码成功！'
							);
						}else {
							// 数据库操作错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			}else {
				// 该用户不存在
				$res=array(
						'code'=>$this->ERROR_CODE_CUSTOMER['CUSTOMER_NOT_EXIST'],
						'msg'=>$this->ERROR_CODE_CUSTOMER_ZH[$this->ERROR_CODE_CUSTOMER['CUSTOMER_NOT_EXIST']]
				);
			}
		}
		return $res;
	}
}

?>