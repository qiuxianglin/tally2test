<?php
/**
 * 门到门拆箱-预报计划管理
 */
namespace Index\Controller;
use Index\Common\BaseController;

class DdPlanController extends BaseController
{
	//预报列表
	public function index()
	{
		//集装箱船列表
		$Ship=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$Ship->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		$Plan=new \Common\Model\DdPlanModel();

		$count=$Plan->count();
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

		$list = $Plan->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);

		$this->display();
	}

	public function edit($id)
	{
		$Plan=new \Common\Model\DdPlanModel();
		$msg=$Plan->getPlanMsg($id);
		$this->assign('msg',$msg);
		// 预报计划-配货
		$planCargo=new \Common\Model\DdPlanCargoModel();
		$cargoList = $planCargo->getCargoList ( $id );
		$this->assign ( 'cargolist', $cargoList );

		//  预报计划-配箱
		$planContainer = new \Common\Model\DdPlanContainerModel();
		$plancontainerlist = $planContainer->getContainerList($id);
		$this->assign('plancontainerlist',$plancontainerlist);

		$this->display();
	}

	//新增预报
	public function add()
	{
		$ship = new \Common\Model\ShipModel ();
		$DdPlan = new \Common\Model\DdPlanModel();
		$location = new \Common\Model\LocationModel();
		$dtd_departmentid=dtd_departmentid;
		$zg_departmentid = zg_departmentid;
		$instruction_status=json_decode(instruction_status,true);
		if(I('post.'))
		{
			layout(false);
			//判断委托编号是否存在，存在不准重复提交
			$orderid = I('post.ORDERID');
			$res_dp=$DdPlan->where("orderid='$orderid'")->count();
			if($res_dp>0)
			{
				$this->error("该委托计划已存在，不能重复提交");
			}
			//判断船名是否存在
			$Ship=new \Common\Model\ShipModel();
			$ship_exist=$Ship->is_exist($_POST ['VSLNAME']);
			if($ship_exist!==true)
			{
				$this->error("该船舶名称不存在！");
			}
			
			// 检验目的港名称是否正确
			$location_name = I ( 'post.LOCATION_NAME' );
			$data_l = filterString ( $location_name );
			if ($data_l == false) {
				$this->error ( '拆箱地点不能含有特殊字符' );
				exit ();
			}
			$res_l = $location->where ( "location_name='$location_name'" )->field ( 'id' )->find ();
			if ($res_l ['id'] == '') {
				$this->error ( '拆箱地点不存在！' );
			} else {
				$locationname = $location_name;
			}
			//保存预报计划
			$data = array (
					'orderid'    =>  $orderid,
					'orderdate'  =>  date('YmdHis'),
					'business'   =>  I('post.BUSINESS'),
					'vslname'    =>  I('post.VSLNAME'),
					'voyage'     =>  I('post.VOYAGE'),
					'applycode'  =>  I('post.APPLYCODE'),
					'applyname'  =>  I('post.APPLYNAME'),
					'transit'    =>  I('post.TRANSIT'),
					'category'   =>  I('post.CATEGORY'),
					'note'       =>  I('post.NOTE'),
					'unpackagingplace'  =>   $locationname
			);
			
			if(!$DdPlan->create($data))
			{
				//对data数据进行验证
				$this->error($DdPlan->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$DdPlan->add($data);
				if ($res!==false)
				{
					if(I('post.TRANSIT') === '公路转关')
					{
						$departmentid = $zg_departmentid;
					}else{
						$departmentid = $dtd_departmentid;
					}
					//新增指令
					$data_i=array(
							'plan_id'=>$res,
							'date'=>date('Y-m-d'),
							'status'=>$instruction_status['not_start'],
							'department_id'=>$departmentid
					);
					$Instruction=new \Common\Model\DdInstructionModel();
					$Instruction->add($data_i);
					$this->success('添加成功！');
				} else {
					$this->error('添加失败！');
				}
			}
		}else{
			// 集装箱船列表
			$ship_type = json_decode ( ship_type, true );
			$shiplist = $ship->getShipList ( $ship_type ['container'] );
			$this->assign ( 'shiplist', $shiplist );
	
			// 理货地点列表
			$location_type = json_decode ( location_type, true );
			$locationlist = $location->getLocationList ( $location_type ['port'] );
			$this->assign ( 'locationlist', $locationlist );
			$this->display();
		}
	}
}