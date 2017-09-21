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

	//修改关
	public function editlevel($operation_id,$level_id)
	{
		layout(false);
		$amend = new \Common\Model\AmendModel();
		$this->assign ( 'operation_id', $operation_id );
		$this->assign ( 'level_id', $level_id );
		$uid = $_SESSION ['uid'];
		$where = array (
				'uid' => $uid
		);
		$user = new \Common\Model\UserModel();
		$user=$user->where ( $where )->find ();
		$shift_id=$user ['shift_id'];
		// 如果是部门长，可以直接修改，否则需要判断是否为当班理货长
		$common=new \Common\Model\ShiftModel();
		if($_SESSION ['u_group_id']!='13')
		{
			//判断用户是否为当班理货长
			$res_m=$common->isWorkMaster($uid, $shift_id);
			if($res_m['code']!=0)
			{
				$this->error('非当班理货长不得操作!');
			}
		}
		//根据关ID获取关信息
		$level = new \Common\Model\DdOperationLevelModel();
		$msg=$level->getLevelMsg($level_id);
		$this->assign('msg',$msg);
		if (I('post.'))
		{
			if(I('post.remark'))
			{
				$remark=I('post.remark');
			}else {
				$this->error('修改原因不能为空！');
			}
			$cargo_number = I('post.cargo_number');
			$damage_num = I('post.damage_num');
// 			$billno = strtoupper(I('post.billno'));
			//获取作业信息
// 			$operation = new \Common\Model\DdOperationModel();
			$ddoperation = new \Common\Model\DdOperationModel();
			$res_c = $ddoperation->where ( "id='$operation_id'")->find ();

			//作业ID对应的箱ID
			$ctn_id=$res_c ['ctn_id'];

// 			$trans = M();
// 			$trans->startTrans();   // 开启事务

			//获取关信息
			$g = $level->where ( "id='$level_id'" )->find ();

			if ($g ['num'] != $cargo_number) 
			{
				//修改关的货物件数
				$data_cn=array(
						'num'=>$cargo_number
				);
				$res_cn = $level->where("id='$level_id'")->save($data_cn);
				if($res_cn!==false)
				{
					//保存修改记录
					$data = array (
							'business' => 'dd',
							'category' => 'operation_level',
							'operation_id' => $operation_id,
							'info_id' => $level_id,
							'field_name' => 'num',
							'field_old_value' => $g ['num'],
							'field_new_value' => $cargo_number,
							'uid' => $uid,
							'date' => date ( 'Y-m-d H:i:s'),
							'remark' => $remark
					);
					if(!$amend->create($data))
					{
						//对data数据进行验证
						$this->error($amend->getError());
					}else{
						//验证通过 可以对数据进行操作
						$amend->add($data);
					}
				}
			}
			
			if ($g ['damage_num'] != $damage_num) 
			{
				//修改关的残损件数
				$data_dn=array(
						'damage_num'=>$damage_num
				);
				if(!$level->create($data_dn))
				{
					//对数据进行验证
					$this->error($level->getError());
				}else{
					//验证通过 可以对数据进行操作
				    $res_dn = $level->where("id='$level_id'")->save($data_dn);	
				}
				if($res_dn!==false)
				{
					//保存修改记录
					$data = array (
							'business' => 'dd',
							'category' => 'operation_level',
							'operation_id' => $operation_id,
							'info_id' => $level_id,
							'field_name' => 'damage_num',
							'field_old_value' => $g ['damage_num'],
							'field_new_value' => $damage_num,
							'uid' => $uid,
							'date' => date ( 'Y-m-d H:i:s', time () ),
							'remark' => $remark
					);
					if(!$amend->create($data))
					{
						//对data数据进行验证
						$this->error($amend->getError());
					}else{
						//验证通过 可以对数据进行操作
						$amend->add($data);
					}
				}	
			}
			
			$container = new \Common\Model\DdPlanContainerModel();
			$ctnMsg = $container->where("id='$ctn_id'")->field('status')->find();
			$status = $ctnMsg['status'];
			if($res_c['operation_examine'] != '2')
			{
				echo '<script>alert("修改成功");top.location.reload(true);window.close();</script>';
				exit ();
			}else{
				// 因对工作中的箱子不允许修改，所以修改的都是已铅封的箱子，所以需要删除原单证，重新生成单证
				// 根据箱id找出单证
				$prove = new \Common\Model\DdProveModel();
				$ctn_certify = $prove->where ( "ctn_id=$ctn_id" )->find ();
				// 将原单证备注保存
				$ccremark = $ctn_certify ['remark'];
				// 删除原单证
				$res_d =$prove->where ( "ctn_id='$ctn_id'" )->delete();
				if($res_d!==false)
				{
					// 重新生成单证
					$prove->generateDocument($ctn_id,$ccremark);
					echo '<script>alert("修改成功");top.location.reload(true);window.close();</script>';
					exit ();
				}else {
					$this->error('修改成功，重新生成单证失败！');
				}
			}
		}else {
			$this->display ();
		}
	}
}
?>