<?php
/**
 * APP接口
 * 指令管理接口
 */
namespace App\Controller;
use App\Common\BaseController;

class DdInstructionController extends BaseController
{
	/**
	 * 获取指令详情
	 * @param int $id:指令ID
	 * @return array
	 */
	public function getInstructionMsg()
	{
		if(I('post.id'))
		{
			$id=I('post.id');
			$DdInstruction=new \Common\Model\DdInstructionModel();
			$content=$DdInstruction->getInstructionMsg($id);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'content'=>$content
			);
		}else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
					'content'=>''
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取理货长所在部门的指令列表
	 * @param int $uid:用户ID
	 * @param string $status:指令状态 0.未派工 1.已派工  2.已完成 
	 * @return array
	 * @return code:返回码
	 * @return msg:返回码说明
	 * @return list:指令列表
	 */
	public function getInstructionListByWork()
	{
		if(I('post.uid') and I('post.status')!=='')
		{
			$uid=I('post.uid');
			$status=I('post.status');
			$DdInstruction=new \Common\Model\DdInstructionModel();
			$res=$DdInstruction->getInstructionListByWork($uid, $status);
		}else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>