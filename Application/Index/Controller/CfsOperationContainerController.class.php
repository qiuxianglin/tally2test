<?php
/**
 * CFS-配箱作业详情
 */
namespace Index\Controller;
use Index\Common\BaseController;

class CfsOperationContainerController extends BaseController
{
	//作业详情
	public function detail($ctn_id)
	{
		//根据箱ID获取箱详情
		$container=new \Common\Model\CfsInstructionCtnModel();
		$res_c=$container->getContainerMsg($ctn_id);
		$ctnMsg=$res_c[0];
		$this->assign('ctnMsg',$ctnMsg);
		//根据箱ID获取作业详情
		$operation=new \Common\Model\CfsOperationModel();
		$msg=$operation->getOperationMsgByCtn($ctn_id);
		$this->assign('msg',$msg);
		if($msg)
		{
			$operation_id=$msg['id'];
			//根据作业ID获取空箱照片
			$empty = new \Common\Model\CfsCtnEmptyImgModel();
			$emptylist = $empty->where("operation_id='$operation_id'")->select();
			$this->assign('emptylist',$emptylist);
			//根据作业ID获取关列表
			$level=new \Common\Model\CfsOperationLevelModel();
			$levelList=$level->getLevelList($operation_id);
			$this->assign('levelList',$levelList);
		}
	
		$this->display();
	}
	
	//箱子的作业内容审核通过
	public function operation_examine()
	{
		layout(false);
		$instruction_ctn = new \Common\Model\CfsInstructionCtnModel();
		$operation = new \Common\Model\CfsOperationModel();
		$uid = $_SESSION['uid'];
		//检验用户是否是当班理货长
		$user = new \Common\Model\UserModel();
		$instruction_id = I('get.instruction_id');
		$res_is = $user->isPermissionsforexamine($uid, $instruction_id,$business='cfs');
		if($res_is['code']!=0)
		{
			$this->error($res_is['msg']);
		}
		//判断箱子是否铅封
		$ctn_id = I('get.ctn_id');
		$res = $instruction_ctn->field('status,instruction_id')->where("id='$ctn_id'")->find();
	
		if($res['status'] != 2)
		{
			$this->error('该箱子尚未铅封！');
		}else{
			//铅封，生成单证，改变审核状态
			$remark='';
			$prove=new \Common\Model\CfsProveModel();
			$res_p = $prove->generateDocumentByCfs($ctn_id,$remark);
			if($res_p['code']==0)
			{
				// 判断指令下的配箱是否都已经完成，都完成将指令状态改为完成
				$instruction_id = $res['instruction_id'];
				$no_container_num=$instruction_ctn->where("instruction_id='$instruction_id' and status not in(2,3)")->count();
				if($no_container_num==0)
				{
					//修改指令状态为已完成
					$data_i=array(
							'status'=>'2'
					);
					$instruction = new \Common\Model\CfsInstructionModel();
					$instruction->where("id='$instruction_id'")->save($data_i);
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