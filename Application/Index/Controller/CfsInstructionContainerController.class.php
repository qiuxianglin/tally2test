<?php
/**
 * CFS指令-配箱管理
 */
namespace Index\Controller;
use Index\Common\BaseController;

class CfsInstructionContainerController extends BaseController
{
	//新增配箱
	public function add($instruction_id)
	{
		layout(false);
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
			$instructionContainer=new \Common\Model\CfsInstructionCtnModel();
			//检查该箱在该指令是否已存在
			$res = $instructionContainer->field("ctnno")->where("instruction_id='$instruction_id' and ctnno='$ctnno'")->find();
			if($res['ctnno'] != '')
			{
				$this->error('该箱号已存在！');
			}
			$data=array(
					'instruction_id'=>$instruction_id,
					'ctnno'=>$ctnno,
					'ctn_size'=>I('post.ctn_size'),
					'ctn_master'=>I('post.ctn_master'),
					'lcl'=>I('post.lcl'),
					'pre_number'=>I('post.pre_number'),
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
					$this->success("新增指令配箱成功",U('CfsInstruction/edit',array('id'=>$instruction_id)));
				}else {
					$this->error("操作失败！");
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
		$instructionContainer=new \Common\Model\CfsInstructionCtnModel();
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
					'ctn_size'=>I('post.ctn_size'),
					'ctn_master'=>I('post.cmaster'),
					'lcl'=>I('post.lclo'),
					'pre_number'=>I('post.pre_number')
			);
			if(!$instructionContainer->create($data))
			{
				//对data数据进行验证
				$this->error($instructionContainer->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$instructionContainer->where("id=$id")->save($data);
				$res_i = $instructionContainer->where("id='$id'")->field('instruction_id')->find();
				$instruction_id=$res_i['instruction_id'];
				if($res!==false)
				{
					$this->success("编辑指令配箱成功!",U('CfsInstruction/edit',array('id'=>$instruction_id)));
				}else {
					$this->error("操作失败");
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
		$instructionContainer=new \Common\Model\CfsInstructionCtnModel();
		$res=$instructionContainer->where("id=$id")->delete();
		if($res!==false)
		{
			$this->success('删除配箱成功！');
		}else {
			$this->error('操作失败！');
		}
	}
}