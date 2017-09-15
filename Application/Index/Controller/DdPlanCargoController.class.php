<?php
/**
 * 门到门拆箱-预报计划配货
 */
namespace Index\Controller;
use Index\Common\BaseController;

class DdPlanCargoController extends BaseController
{
	//新增配货
	public function add($plan_id)
	{
		layout(false);
		$customer = new \Common\Model\CustomerModel();
		if(I('post.'))
		{
			if(I('post.billno') and I('post.cargoname') and I('post.payman')  and I('post.package') and I('post.mark')){
				// 检验付费方名称是否正确
				$payman = I ( 'post.payman' );
				$data_l = filterString ( $payman );
				if ($data_l == false) {
					$this->error ( '付费方名称不能含有特殊字符' );
					exit ();
				}
				$res_l = $customer->where ( "customer_name='$payman'" )->field ( 'customer_code,customer_name,id' )->find ();
				if ($res_l ['id'] == '') {
					$this->error ( '付费方名称不存在！' );
				} else {
					$customer_code = $res_l ['customer_code'];
					$customer_name = $res_l ['customer_name'];
				}
				//提单号唯一
				$planCargo=new \Common\Model\DdPlanCargoModel();
				$billno= I ('post.billno');
				$data_b = filterString($billno);
				if($data_b == false){
					$this->error('不能含有特殊字符');
					exit();
				}
				$res_b=$planCargo->where("blno='$billno'")->find();
				if($res_b['id']!='')
				{
					$this->error('该提单号已存在，不能重复！');
				}
				$data=array(
						'plan_id'=>$plan_id,
						'blno'=>$billno,
						'cargoname'=>I ('post.cargoname'),
						'numbersofpackages'=>I ('post.number'),
						'package'=>trim(I ('post.package'),"'"),
						'mark'=>trim(I ('post.mark'),"'"),
						'classes'=>trim(I ('post.classes'),"'"),
						'last_operator'=>$_SESSION['uid'],
						'last_operationtime'=>date('Y-m-d H:i:s'),
						'payman'   => $customer_name,
						'paycode'   => $customer_code,
						'consignee'  => trim(I ('post.consignee'),"'"),
						'undgno'     => trim(I ('post.undgno'),"'"),
						'contactuser'  => trim(I ('post.contactuser'),"'"),
						'contact'  => trim(I ('post.contact'),"'")
				);
				if(!$planCargo->create($data))
				{
					//对data数据进行验证
					$this->error($planCargo->getError());
				}else{
					//通过验证 可以对数据进行操作
					$res = $planCargo->add($data);
					if ($res !==false)
					{
						echo '<script>alert("新增配货成功!");top.location.reload(true);window.close();</script>';
					} else {
						echo '<script>alert("新增失败!");top.location.reload(true);window.close();</script>';
					}
				}
			}else{
				$this->error('提单号，付费方，货名，包装，标志不能为空！');
			}
		}else{
			//客户列表
			$customerlist = $customer->select();
			$this->assign('customerlist',$customerlist);
			$this->assign('plan_id',$plan_id);
			$this->display();
		}	
	}
	
	//修改配货
	public function edit($id)
	{
		layout(false);
		$customer = new \Common\Model\CustomerModel();
		$planCargo=new \Common\Model\DdPlanCargoModel();
		if(I('post.'))
		{
			if(I('post.billno') and I('post.cargoname') and I('post.payman') and I('post.package') and I('post.mark'))
			{
				// 检验付费方名称是否正确
				$payman = I ( 'post.payman' );
				$data_l = filterString ( $payman );
				if ($data_l == false) {
					$this->error ( '付费方名称不能含有特殊字符' );
					exit ();
				}
				$res_l = $customer->where ( "customer_name='$payman'" )->field ( 'customer_code,customer_name,id' )->find ();
				if ($res_l ['id'] == '') {
					$this->error ( '付费方名称不存在！' );
				} else {
					$customer_code = $res_l ['customer_code'];
					$customer_name = $res_l ['customer_name'];
				}
				//提单号唯一
				$id = I('post.id');
				$planCargo=new \Common\Model\DdPlanCargoModel();
				$billno= I ('post.billno');
				$data_b = filterString($billno);
				if($data_b == false){
					$this->error('不能含有特殊字符');
					exit();
				}
				$res_b=$planCargo->where("blno='$billno' and id != $id")->find();
				if($res_b['id']!='')
				{
					$this->error('该提单号已存在，不能重复！');
				}
				$data=array(
						'blno'=>$billno,
						'cargoname'=>I ('post.cargoname'),
						'numbersofpackages'=>I ('post.number'),
						'package'=>trim(I ('post.package'),"'"),
						'mark'=>trim(I ('post.mark'),"'"),
						'classes'=>trim(I ('post.classes'),"'"),
						'last_operator'=>$_SESSION['uid'],
						'last_operationtime'=>date('Y-m-d H:i:s'),
						'payman'   => $customer_name,
						'paycode'   => $customer_code,
						'consignee'  => trim(I ('post.consignee'),"'"),
						'undgno'     => trim(I ('post.undgno'),"'"),
						'contactuser'  => trim(I ('post.contactuser'),"'"),
						'contact'  => trim(I ('post.contact'),"'")
				);
				if(!$planCargo->create($data))
				{
					//对data数据进行验证
					$this->error($planCargo->getError());
				}else{
					//验证通过 可以对数据进行操作
					$res = $planCargo->where("id=$id")->save($data);
					if ($res !==false)
					{
						echo '<script>alert("编辑配货成功!");top.location.reload(true);window.close();</script>';
					} else {
						echo '<script>alert("操作失败");top.location.reload(true);window.close();</script>';
					}
				}
			}else{
				$this->error('提单号，货名，包装，标志不能为空');
			}
		}else{
			//客户列表
			$customerlist = $customer->select();
			$this->assign('customerlist',$customerlist);
			//配货详情
			$msg=$planCargo->getCargoMsg($id);
			$this->assign('msg',$msg);
			$this->display();
		}
	}
	
	//删除配货
	public function del($plan_id,$id)
	{
		layout(false);
		//限制条件
		//配货所属的预报计划的指令已经派工的情况下不允许删除
		$instruction = new \Common\Model\DdInstructionModel();
		$res_i=$instruction->where("plan_id=$plan_id and status!='0'")->field('id')->find();
		if($res_i['id']!='')
		{
			$this->error('配货所属的预报计划已经开始装箱作业，禁止删除!');
		}else {
			$planCargo=new \Common\Model\DdPlanCargoModel();
			$res=$planCargo->where("id=$id")->delete();
			if($res!==false)
			{
				$this->success('删除配货成功!');
			}else {
				$this->error('删除配货失败!');
			}
		}
	}
}