<?php
/**
 * APP接口
 * 船舶管理接口
 */
namespace App\Controller;
use App\Common\BaseController;
header ( "Access-Control-Allow-Origin: *" );

class ShipController extends BaseController
{
	/**
	 * 获取船舶列表
	 * @param int type:船舶类型：默认空，查找全部 1集装箱船 2杂货船 3散货船 4滚装船 5油船 6木材船 7冷藏船 8危险品船 9货驳船
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回船舶列表
	 */
	public function getShipList()
	{
		if(I('post.type'))
		{
			$type = I('post.type');
		}else{
			$type = "";
		}
		$ship = new \Common\Model\ShipModel();
		$list = $ship->getShipList($type);
		if($list!==false){
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$list,
			);
		}else{
			//数据库连接错误
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