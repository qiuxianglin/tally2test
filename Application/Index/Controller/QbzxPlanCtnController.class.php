<?php
/**
 * 起泊装箱-预报计划配箱
 */
namespace Index\Controller;
use Index\Common\BaseController;

class QbzxPlanCtnController extends BaseController
{
	//新增配箱
	public function add($plan_id)
	{
		layout(false);
		$this->assign('plan_id',$plan_id);
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
			if(I('post.quantity'))
			{
				$quantity=I('post.quantity');
			}else {
				$this->error('箱子个数必须为正整数！');
			}
			$data=array(
					'plan_id'=>$plan_id,
					'ctn_type_code'=>I('post.ctn_type_code'),
					'quantity'=>$quantity,
					'ctn_master'=>I('post.ctn_master'),
					'flflag'=>I('post.flflag'),
					'last_operator'=>$_SESSION['uid'],
			);
			$planContainer=new \Common\Model\QbzxPlanCtnModel();
			if(!$planContainer->create($data))
			{
				//对data数据进行验证
				$this->error($planContainer->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$planContainer->add($data);
				if ($res!==false)
				{
					echo '<script>alert("新增配箱成功!");top.location.reload(true);window.close();</script>';
				} else {
					echo '<script>alert("操作失败！");top.location.reload(true);window.close();</script>';
				}
			}
		}else {
			$this->display();
		}
	}
	
	//编辑配箱
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
		//获取配箱详情
		$planContainer=new \Common\Model\QbzxPlanCtnModel();
		$msg=$planContainer->getContainerMsg($id);
		$this->assign('msg',$msg);
		if(I('post.'))
		{
			if(I('post.quantity'))
			{
				$quantity=I('post.quantity');
			}else {
				$this->error('箱子个数不能为空！');
			}
			$data=array(
					'ctn_type_code'=>I('post.ctn_type_code'),
					'quantity'=>$quantity,
					'ctn_master'=>I('post.ctn_master'),
					'flflag'=>I('post.flflag'),
					'last_operator'=>$_SESSION['uid'],
					'last_operationtime'=>date('Y-m-d H:i:s')
			);
			if(!$planContainer->create($data))
			{
				//对data数据进行验证
				$this->error($planContainer->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$planContainer->where("id=$id")->save($data);
				if ($res!==false)
				{
					echo '<script>alert("修改配箱成功!");top.location.reload(true);window.close();</script>';
				} else {
					echo '<script>alert("操作失败！");top.location.reload(true);window.close();</script>';
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
		$planContainer=new \Common\Model\QbzxPlanCtnModel();
		$res=$planContainer->where("id=$id")->delete();
		if($res!==false)
		{
			$this->success('删除配箱成功！');
		}else {
			$this->error('删除配箱失败！');
		}
	}
}
?>