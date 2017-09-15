<?php
/**
 * APP接口
 * 指令管理接口
 */
namespace App\Controller;
use App\Common\BaseController;
header ( "Access-Control-Allow-Origin: *" );

class QbzxInstructionController extends BaseController
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
			$instruction=new \Common\Model\QbzxInstructionModel();
			$content=$instruction->getInstructionMsg($id);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'content'=>$content
			);
		}else {
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
	 * @return array
	 * @return code:返回码
	 * @return msg:返回码说明
	 * @return list:指令列表
	 */
	public function getInstructionListByWork()
	{
		if(I('post.uid'))
		{
			$uid=I('post.uid');
			$instruction=new \Common\Model\QbzxInstructionModel();
			$res=$instruction->getInstructionListByWork($uid);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>
