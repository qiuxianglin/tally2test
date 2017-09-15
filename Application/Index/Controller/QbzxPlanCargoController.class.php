<?php
/**
 * 起泊装箱-预报计划配货
 */
namespace Index\Controller;
use Index\Common\BaseController;

class QbzxPlanCargoController extends BaseController
{
	//新增配货
	public function add($plan_id)
	{
		layout(false);
		$this->assign('plan_id',$plan_id);
		//获取驳船列表
		$shipModel=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$shipModel->getShipList($ship_type['barge']);
		$this->assign('shiplist',$shiplist);
		// 港口信息
		$port = new \Common\Model\PortModel ();
		$portlist = $port->getPortList ();
		$this->assign ( 'portlist', $portlist );
		//获取场地列表
		$location_type=json_decode(location_type,true);
		$location=new \Common\Model\LocationModel();
		$locationlist=$location->getLocationList($location_type['port']);
		$this->assign('locationlist',$locationlist);
		if(I('post.'))
		{
			// var_dump(I('post.'));exit;
			if(I('post.billno') and I('post.cargoname') and I('post.package') and I('post.mark') and I('post.port_name'))
			{
				//驳船ID
				$ship_arr=array_unique(I('post.ship_id'));
				foreach ($ship_arr as $k=>$v)
				{
					if(!empty($v))
					{
						$data_s = filterString($v);
						if($data_s == false){
							$this->error('不能含有特殊字符');
							exit();
						}
						$res_s=$shipModel->where("ship_name='$v' and ship_type=9")->field('id')->find();
						if($res_s['id']!='')
						{
							$ship_id.=$res_s['id'].',';
						}else {
							$str=$v.'船舶不存在';
							$this->error($str);
							exit();
						}
					}
				}
				if($ship_id)
				{
					$ship_id=substr($ship_id,0,-1);
					$res = filterString($ship_id);
				}
				//来源场地ID
				$location_arr=array_unique(I('post.location_name'));
				foreach ($location_arr as $k=>$v)
				{
					if(!empty($v))
					{
						$data_l = filterString($v);
						if($data_l == false){
							$this->error('不能含有特殊字符');
							exit();
						}
						$res_l=$location->where("location_name='$v'")->field('id')->find();
						if($res_l['id']!='')
						{
							$location_id.=$res_l['id'].',';
						}else {
							$str=$v.'来源场地不存在';
							$this->error($str);
							exit();
						}
					}
				}
				if($location_id)
				{
					$location_id=substr($location_id,0,-1);
				}

				//提单号唯一
				$planCargo=new \Common\Model\QbzxPlanCargoModel();
				$billno= I ('post.billno');
				$data_b = filterString($billno);
				if($data_b == false){
					$this->error('不能含有特殊字符');
					exit();
				}
				$res_b=$planCargo->where("billno='$billno'")->find();
				if($res_b['id']!='')
				{
					$this->error('该提单号已存在，不能重复！');
				}

				// 检验目的港名称是否正确
				$port = new \Common\Model\PortModel();
				$port_name = I ( 'post.port_name' );
				$data_l = filterString ( $port_name );
				if ($data_l == false) {
					$this->error ( '目的港不能含有特殊字符' );
					exit ();
				}
				$res_l = $port->where ( "name='$port_name'" )->field ( 'id' )->find ();
				if ($res_l ['id'] == '') {
					$this->error ( '目的港不存在！' );
				} else {
					$port_id = $res_l ['id']; 
				}
				$data=array(
						'plan_id'=>$plan_id,
						'billno'=>$billno,
						'cargo_name'=>I ('post.cargoname'),
						'number'=>I ('post.number'),
						'pack'=>trim(I ('post.package'),"'"),
						'mark'=>trim(I ('post.mark'),"'"),
						'total_weight'=>I ('post.total_weight'),
						'dangerlevel'=>trim(I ('post.dangerlevel'),"'"),
						'ship_id'=>$ship_id,
						'location_id'=>$location_id,
						'last_operator'=>$_SESSION['uid'],
						'last_operationtime'=>date('Y-m-d H:i:s'),
						'port_id'   => $port_id 
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
				$this->error('提单号，货名，件数，包装，标志，目的港不能为空');	
			}
		}else {
			$this->display();
		}
	}
	
	//编辑配货
	public function edit($id)
	{
		layout(false);
		$this->assign('id',$id);
		//获取驳船列表
		$shipModel=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$shipModel->getShipList($ship_type['barge']);
		$this->assign('shiplist',$shiplist);
		//获取场地列表
		$location_type=json_decode(location_type,true);
		$location=new \Common\Model\LocationModel();
		$locationlist=$location->getLocationList($location_type['port']);
		$this->assign('locationlist',$locationlist);
		// 港口信息列表
		$port = new \Common\Model\PortModel ();
		$portlist = $port->getPortList ();
		$this->assign ( 'portlist', $portlist );
		//获取配箱详情
		$planCargo=new \Common\Model\QbzxPlanCargoModel();
		$msg=$planCargo->getCargoMsg($id);
		$this->assign('msg',$msg);
		if(I('post.'))
		{
			if(I('post.billno') and I('post.cargoname') and I('post.number') and I('post.package') and I('post.mark'))
			{
				//驳船ID
				$ship_arr=array_unique(I('post.ship_id'));
				foreach ($ship_arr as $k=>$v)
				{
					if(!empty($v))
					{
						$data_s = filterString($v);
						if($data_s == false){
							$this->error('不能含有特殊字符');
							exit();
						}
						$res_s=$shipModel->where("ship_name='$v' and ship_type=9")->field('id')->find();
						if($res_s['id']!='')
						{
							$ship_id.=$res_s['id'].',';
						}else {
							$str=$v.'船舶不存在';
							$this->error($str);
							exit();
						}
					}
				}
				if($ship_id)
				{
					$ship_id=substr($ship_id,0,-1);
				}
				//来源场地ID
				$location_arr=array_unique(I('post.location_name'));
				foreach ($location_arr as $k=>$v)
				{
					if(!empty($v))
					{
						$data_l = filterString($v);
						if($data_l == false){
							$this->error('不能含有特殊字符');
							exit();
						}
						$res_l=$location->where("location_name='$v'")->field('id')->find();
						if($res_l['id']!='')
						{
							$location_id.=$res_l['id'].',';
						}else {
							$str=$v.'来源场地不存在';
							$this->error($str);
							exit();
						}
					}
				}
				if($location_id)
				{
					$location_id=substr($location_id,0,-1);
				}
				
				//提单号唯一
				$planCargo=new \Common\Model\QbzxPlanCargoModel();
				$billno=I ('post.billno');
				$data_b = filterString($billno);
				if($data_b == false){
					$this->error('不能含有特殊字符');
					exit();
				}
				$res_b=$planCargo->where("billno='$billno' and id!=$id")->find();
				if($res_b['id']!='')
				{
					$this->error('该提单号已存在，不能重复！');
				}

				// 检验目的港名称是否正确
				$port = new \Common\Model\PortModel();
				$port_name = I ( 'post.port_name' );
				$data_l = filterString ( $port_name );
				if ($data_l == false) {
					$this->error ( '目的港不能含有特殊字符' );
					exit ();
				}
				$res_l = $port->where ( "name='$port_name'" )->field ( 'id' )->find ();
				if ($res_l ['id'] == '') {
					$this->error ( '目的港不存在！' );
				} else {
					$port_id = $res_l ['id'];
				}
				$data=array(
						'billno'=>$billno,
						'cargo_name'=>I ('post.cargoname'),
						'number'=>I ('post.number'),
						'pack'=>I ('post.package'),
						'mark'=>I ('post.mark'),
						'total_weight'=>I ('post.total_weight'),
						'dangerlevel'=>I ('post.dangerlevel'),
						'ship_id'=>$ship_id,
						'location_id'=>$location_id,
						'last_operator'=>$_SESSION['uid'],
						'last_operationtime'=>date('Y-m-d H:i:s'),
						'port_id'  => $port_id
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
				$this->error('提单号，货名，件数，包装，标志不能为空');
			}
		}else {
			$this->display();
		}
	}
	
	//删除配货
	public function del($id,$plan_id)
	{
		layout(false);
		//限制条件
		//配货所属的预报计划添加的指令已经派工的情况下不允许删除
		$instruction = new \Common\Model\QbzxInstructionModel();
		$res_i=$instruction->where("plan_id=$plan_id and status!='0'")->field('id')->find();
		if($res_i['id']!='')
		{
			$this->error('配货所属的预报计划已经开始装箱作业，禁止删除!');
		}else {
			$planCargo=new \Common\Model\QbzxPlanCargoModel();
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
?>