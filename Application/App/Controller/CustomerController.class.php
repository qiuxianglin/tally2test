<?php
/**
 * APP接口
 * 客户管理接口
 */
namespace App\Controller;
use App\Common\BaseController;

class CustomerController extends BaseController
{
	/**
	 * 获取客户列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回客户信息列表
	 */
	public function getCustomerList()
	{
		$customer = new \Common\Model\CustomerModel();
		$list = $customer->getCustomerList();
		if($list!==false)
		{
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$list,
			);
		}else{
			//数据库操作错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list'=>'',
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}


?>