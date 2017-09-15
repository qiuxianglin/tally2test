<?php
/**
 * APP接口
 * 箱主管理接口
 */
namespace App\Controller;
use App\Common\BaseController;
header ( "Access-Control-Allow-Origin: *" );

class CtnMasterController extends BaseController
{
	/**
	 * 获取箱主列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回船舶列表
	 */
	public function getCtnMasterList(){
			$cmaster = new \Common\Model\ContainerMasterModel();
			$list = $cmaster->getContainerMasterList();
			if($list!==false){
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'list'=>$list,
				);
			}else{
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
