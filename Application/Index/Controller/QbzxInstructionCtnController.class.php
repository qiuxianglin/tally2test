<?php
/**
 * 起泊装箱-指令配箱管理
*/
namespace Index\Controller;
use Index\Common\BaseController;

class QbzxInstructionCtnController extends BaseController
{
	//指令下的配箱列表
	public function index($instruction_id)
	{
		$this->assign('instruction_id',$instruction_id);
		$instruction=new \Common\Model\QbzxInstructionModel();
		//获取指令详情
		$msg=$instruction->getInstructionMsg($instruction_id);
		$this->assign('msg',$msg);
		//已配箱数
		$instructionContainer=new \Common\Model\QbzxInstructionCtnModel();
		$has_container_num=$instructionContainer->hasContainerNum($instruction_id);
		$this->assign('has_container_num',$has_container_num);
		//作业班组
		$department_id=$msg['department_id'];
		$department=new \Common\Model\DepartmentModel();
		$dMsg=$department->getDepartmentMsg($department_id);
		$this->assign('dMsg',$dMsg);
		//根据计划ID获取预报计划信息
		$plan_id=$msg['plan_id'];
		$plan=new \Common\Model\QbzxPlanModel();
		$planMsg=$plan->getPlanMsg($plan_id);
		$this->assign('planMsg',$planMsg);
		//作业场地
		$location=new \Common\Model\LocationModel();
		$locationlist=$location->getLocationList();
		$this->assign('locationlist',$locationlist);
		//获取指令下的配箱列表
		$containerlist=$instructionContainer->getCtnOperationList($instruction_id);
		$this->assign('containerlist',$containerlist);
		//根据指令ID获取派工详情
		$dispatch=new \Common\Model\DispatchModel();
		$dispatch_detail=$dispatch->getDetail($instruction_id, 'qbzx');
		$this->assign('dispatch_detail',$dispatch_detail);
		$this->display();
	}
	
	//新增指令配箱
	public function add($instruction_id)
	{
		layout(false);
		$instructionContainer=new \Common\Model\QbzxInstructionCtnModel();
		$this->assign('instruction_id',$instruction_id);
		//箱型尺寸
		$container=new \Common\Model\ContainerModel();
		$containerlist=$container->getContainerList();
		$this->assign('contanierlist',$containerlist);
		//箱主
		$containerMaster=new \Common\Model\ContainerMasterModel();
		$cmlist=$containerMaster->getContainerMasterList();
		$this->assign('cmlist',$cmlist);
		if(I('post.'))
		{
			if(I('post.ctnno'))
			{
				$ctnno=strtoupper(I('post.ctnno'));
				//检验箱号是否正确
				$ContainerCheck=new \Common\Model\ContainerCheckModel();
				$res_check=$ContainerCheck->checkNo($ctnno);
				if($res_check===false)
				{
					$this->error('箱号不符合国际标准！');
				}
			}else {
				$this->error('箱号不能为空！');
			}
			$data=array(
					'instruction_id'=>$instruction_id,
					'ctnno'=>$ctnno,
					'ctn_type_code'=>I('post.ctn_type_code'),
					'ctn_master'=>I('post.ctn_master'),
					'status'=>'0'
			);
			if(!$instructionContainer->create($data))
			{
				//对data数据进行验证
				$this->error($instructionContainer->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$instructionContainer->add($data);
				if($res!==false)
				{
					echo '<script>alert("新增指令配箱成功!");top.location.reload(true);window.close();</script>';
				}else {
					echo '<script>alert("操作失败!");top.location.reload(true);window.close();</script>';
				}
			}
		}else {
			$this->display();
		}
	}
	
	//编辑指令配箱
	public function edit($id)
	{
		layout(false);
		$this->assign('id',$id);
		//箱型尺寸
		$container=new \Common\Model\ContainerModel();
		$containerlist=$container->getContainerList();
		$this->assign('contanierlist',$containerlist);
		//箱主
		$containerMaster=new \Common\Model\ContainerMasterModel();
		$cmlist=$containerMaster->getContainerMasterList();
		$this->assign('cmlist',$cmlist);
		//根据ID获取配箱信息
		$instructionContainer=new \Common\Model\QbzxInstructionCtnModel();
		$msg=$instructionContainer->getContainerMsga($id);
		$this->assign('msg',$msg);
		if(I('post.'))
		{
			if(I('post.ctnno'))
			{
				$ctnno=strtoupper(I('post.ctnno'));
				//检验箱号是否正确
				$ContainerCheck=new \Common\Model\ContainerCheckModel();
				$res_check=$ContainerCheck->checkNo($ctnno);
				if($res_check===false)
				{
					$this->error('箱号不符合国际标准！');
				}
			}else {
				$this->error('箱号不能为空！');
			}
			$data=array(
					'ctnno'=>$ctnno,
					'ctn_type_code'=>I('post.ctn_type_code'),
					'ctn_master'=>I('post.ctn_master')
			);
			if(!$instructionContainer->create($data))
			{
				//对data数据进行验证
				$this->error($instructionContainer->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$instructionContainer->where("id=$id")->save($data);
				if($res!==false)
				{
					echo '<script>alert("编辑指令配箱成功!");top.location.reload(true);window.close();</script>';
				}else {
					echo '<script>alert("操作失败!");top.location.reload(true);window.close();</script>';
				}
			}
		}else {
			$this->display();
		}
	}
	
	//删除配箱
	public function del($id)
	{
		layout(false);
		$instructionContainer=new \Common\Model\QbzxInstructionCtnModel();
		//配箱是否作业
		$operation = new \Common\Model\QbzxOperationModel();
		$res_o = $operation->where("ctn_id='$id'")->find();
		if($res_o !== null)
		{
			$this->error('该箱作业中，不可删除');
		}else{
			$res=$instructionContainer->where("id=$id")->delete();
			if($res!==false)
			{
				$this->success('删除配箱成功！');
			}else {
				$this->error('操作失败！');
			}
		}
	}
}
?>