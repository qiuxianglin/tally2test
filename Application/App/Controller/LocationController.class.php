<?php
/**
 * APP接口
 * 作业地点管理接口
 */
namespace APP\Controller;
use App\Common\BaseController;

class LocationController extends BaseController
{
	/**
	 * 获取作业地点列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回作业地点列表
	 */
	public function getLocationList()
	{
		$location = new \Common\Model\LocationModel();
		$list = $location->getLocationList();
		if($list!==false)
		{
			$res = array(
					'code'=> $this->ERROR_CODE_COMMON['SUCCESS'],
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