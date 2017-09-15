<?php
/**
 * APP接口
 * 预报计划接口
 */

namespace App\Controller;
use App\Common\BaseController;

class DdPlanController extends BaseController
{
	/**
	 * 获取预报计划信息
	 * @param int $id:预报计划ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回单条信息
	 */
	public function getPlanMsg()
	{
		if(I('post.id'))
		{
			$id=I('post.id');
			$Plan=new \Common\Model\DdPlanModel();
			$plan_msg=$Plan->getPlanMsg($id);
			
			if($plan_msg!==false)
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'content'=>$plan_msg,
				);
			}else{
				// 数据库操作错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
				);
			}
		}else{
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
					'content'=>''
			);
		}
		echo json_encode($res);
	}
	
	/**
	 * 根据配箱ID获取预报计划详情
	 * @param int ctn_id:箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param content:成功时返回单条预报计划详情
	 */
	public function getPlanMsgByCtn()
	{
		if(I('post.ctn_id'))
		{
			$ctn_id=I('post.ctn_id');
			$sql="select p.* from __PREFIX__dd_plan_container c,__PREFIX__dd_plan p where c.plan_id=p.id and c.id='$ctn_id'";
			$res=M()->query($sql);
			if($res!==false)
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'content'=>$res[0]
				);
			}else{
				// 数据库操作错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
				);
			}
		}else{
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
					'content'=>''
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
}

?>