<?php
/**
 * 门到门拆箱
 * 作业管理
 */
namespace Index\Controller;
use Index\Common\BaseController;

class DdOperationController extends BaseController
{
	//作业详情
	public function index($ctn_id)
	{
		//根据箱ID获取箱详情
		$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
		$ctnMsg=$DdPlanContainer->getContainerMsg($ctn_id);
		$this->assign('ctnMsg',$ctnMsg);
		//根据箱ID获取作业详情
		$DdOperation=new \Common\Model\DdOperationModel();
		$msg=$DdOperation->getOperationMsgByCtn($ctn_id);
		$this->assign('msg',$msg);

		if($msg)
		{
			$operation_id=$msg['id'];
			//根据作业ID获取关列表
			$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
			$levelList=$DdOperationLevel->getLevelList($operation_id);
			$this->assign('levelList',$levelList);
		}

		$this->display();
	}

	//箱子的作业内容审核通过
	public function operation_examine()
	{
		layout(false);
		$plan_ctn = new \Common\Model\DdPlanContainerModel(); 
		$operation = new \Common\Model\DdOperationModel();
		$uid = $_SESSION['uid'];
		//检验用户是否是当班理货长
		$user = new \Common\Model\UserModel();
		$instruction_id = I('get.instruction_id');
		$res_is = $user->isPermissionsforexamine($uid, $instruction_id,$business='dd');
		if($res_is['code']!=0)
		{
			$this->error($res_is['msg']);
		}
		//判断箱子是否铅封
		$ctn_id = I('get.ctn_id');
		$res = $plan_ctn->field('status,plan_id')->where("id='$ctn_id'")->find();
		
		if($res['status'] != 2)
		{
			$this->error('该箱子尚未铅封！');
		}else{
			//铅封，申城单证，改变审核状态
			$remark='';
			$DdProve=new \Common\Model\DdProveModel();
			$res_p=$DdProve->generateDocument($ctn_id, $remark);
			if($res_p['code'] ==  0)
			{
				// 判断指令下的配箱是否都已经完成，都完成将指令状态改为完成
				// 箱状态
				$ctn_status=json_decode(ctn_status,true);
				$ctn_status_finished=$ctn_status['finished'];
				$plan_id = $res['plan_id'];
				$no_container_num=$plan_ctn->where("plan_id=$plan_id and status!='$ctn_status_finished'")->count();
				if($no_container_num==0)
				{
					$instruction_status=json_decode(instruction_status,true);
					// 修改指令状态为已完成
					$data_i=array(
							'status'=>$instruction_status['finish']
					);
					$DdInstruction=new \Common\Model\DdInstructionModel();
					$DdInstruction->where("id=$instruction_id")->save($data_i);
				}
				// 修改箱作业为相应的审核通过状态
				$data_t = array (
						'operation_examine' => I('get.operation_examine')
				);
				$operation_id = I('get.operation_id');
				$operation->where("id='$operation_id'")->save($data_t);
				$this->success('审核成功');
			} else {
				$this->error('生成单证失败，审核操作失败');
			}	
		}
	}

}
?>