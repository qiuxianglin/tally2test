<?php
/**
 * 门到门拆箱-指令管理
 */
namespace Index\Controller;
use Index\Common\BaseController;

class DdInstructionController extends BaseController
{
	//指令列表
	public function index()
	{
		$Instruction=new \Common\Model\DdInstructionModel();
		$sql="select i.id as instruction_id,p.*,i.is_must,i.status from __PREFIX__dd_instruction i,__PREFIX__dd_plan p where i.plan_id=p.id order by i.id desc";
		$list=M()->query($sql);
		$count=count($list);
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		// 分页显示输出
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);
		$this->assign('page',$show);

		$begin_num=($p-1)*$per;
		$sql="select i.id as instruction_id,p.*,i.is_must,i.status from __PREFIX__dd_instruction i,__PREFIX__dd_plan p where i.plan_id=p.id order by i.id desc limit $begin_num,$per";
		$list=M()->query($sql);
		//开关      判断指令下面未完成的指令
		if(I('post.show_icon') == '2')
		{
			foreach($list as $key => $vo )
			{
				static $list = "";
				//通过预报Id获取判断指令列表是否完成
				$ins = $Instruction->where("plan_id='" . $vo ['id'] ."' and status != '2'")->find ();
				if($ins)
				{
					$list[] = $vo;
				}
			}
			$this->assign('select','2');
		}
		//判断指令是否是重点


		$this->assign('list',$list);
		$this->display();
	}

	//查看指令详情
	public function view()
	{
		$location = new \Common\Model\LocationModel ();
		if(I('post.'))
		{
			layout(false);
			// 检验理货地址名称是否正确
			$location_name = I ( 'post.location_name' );
			$data_l = filterString ( $location_name );
			if ($data_l == false) {
				$this->error ( '不能含有特殊字符' );
				exit ();
			}
			$res_l = $location->where ( "location_name='$location_name'" )->field ( 'id' )->find ();
			if ($res_l ['id'] == '') {
				$this->error ( '理货地点不存在！' );
			} else {
				$unpackagingplace = $location_name;
			}
			$plan = new \Common\Model\DdPlanModel();
			$data = array(
					'unpackagingplace'   =>   $unpackagingplace
			);
			$plan_id = I('post.plan_id');
			$res = $plan->where("id = '$plan_id'")->save($data);
			if($res !== false)
			{
				$this->success ( '修改门到门作业地点成功！' );
			}else{
				$this->error( '修改门到门作业地点失败！' );
			}
		}else{
			$plan_id = I('get.plan_id');
			$this->assign('plan_id',$plan_id);
			$instruction_id = I('get.instruction_id');
			//指令对应的预报计划详情
			$DdPlan=new \Common\Model\DdPlanModel();
			$msg=$DdPlan->getPlanMsg($plan_id);
			$this->assign('msg',$msg);
			$DdInstruction=new \Common\Model\DdInstructionModel();
			$instruction_msg=$DdInstruction->getInstructionMsg($instruction_id);
			$this->assign('instruction_msg',$instruction_msg);
			//根据指令ID获取派工详情
			$Dispatch=new \Common\Model\DispatchModel();
			$dispatch_detail=$Dispatch->getDetail($instruction_id, 'dd');
			$this->assign('dispatch_detail',$dispatch_detail);
			//根据预报计划ID获取配货详情
			$plancargo = new \Common\Model\DdPlanCargoModel();
			$cargolist = $plancargo->getCargoList($plan_id);
			$this->assign('cargolist',$cargolist);
			// 理货地点列表
			$location_type = json_decode ( location_type, true );
			$locationlist = $location->getLocationList ( $location_type ['port'] );
			$this->assign ( 'locationlist', $locationlist );
			$this->display();
		}
	}
}