<?php
/**
 * CFS装箱-指令管理
 */

namespace Index\Controller;
use Index\Common\BaseController;

class CfsInstructionController extends BaseController
{
	//新增指令
	public function add()
	{
		$uid = $_SESSION['uid'];
		$signin = new \Common\Model\UserModel(); 
		$res_s = $signin->is_sign($uid);
		if($res_s !== false)
		{
			// 集装箱船列表
			$shipinfo = new \Common\Model\ShipModel ();
			$ship_type = json_decode ( ship_type, true );
			$shiplist = $shipinfo->getShipList ( $ship_type ['container'] );
			$this->assign ( 'shiplist', $shiplist );
			//作业场地列表
			$location=new \Common\Model\LocationModel();
			$locationlist=$location->getLocationList();
			$this->assign('locationlist',$locationlist);
			//客户列表-代理
			$customer = new \Common\Model\CustomerModel();
			$customer_category=json_decode(customer_category,true);
			$customerlist=$customer->getCustomerList($customer_category['agent']);
			$this->assign ( 'customerlist', $customerlist );
			if(I('post.'))
			{
				layout(false);// 临时关闭当前模板的布局功能
				if(I('post.location_name'))
				{
					$location_name=I('post.location_name');
				}else {
					$this->error('装箱场地不能为空！');
				}
				//检查作业地点是否含有特殊字符
				$data_l = filterString($location_name);
				if($data_l == false)
				{
					$this->error("不能含有特殊字符");
					exit();
				}
				$voyage = I('post.voyage');
				$data_v = filterString($voyage);
				if($data_v == false)
				{
					$this->error("不能含有特殊字符");
					exit();
				}

				//检查委托单位是否含有特殊字符
				$customer_name = I('post.entrust_company');
				$data_l = filterString($entrust_company);
				if($data_l == false)
				{
					$this->error("委托单位不能含有特殊字符");
					exit();
				}
				//检验委托单位名称是否正确
				$res_c = $customer->where("customer_name='$customer_name'")->field('id')->find();
				if($res_c['id']=='')
				{
					$this->error('委托单位不存在');
				}else{
					$entrust_company = $res_c['id'];
				}

				//检验理货地址名称是否正确
				$res_l=$location->where("location_name='$location_name'")->field('id')->find();
				if($res_l['id']=='')
				{
					$this->error('理货地点不存在！');
				}else {
					$location_id = $res_l['id'];
				}
				//检查船名是否正确
				$ship_name = I('post.shipname');
				$data_s = filterString($ship_name);
				if($data_s == false)
				{
					$this->error("不能含有特殊字符");
					exit();
				}
				$res_v =$shipinfo->where("ship_name='$ship_name'")->field('id')->find();
				if($res_v['id'] == '')
				{
					$this->error('不存在此船');
				}else{
					$ship_id = $res_v['id'];
				}
				
				$sql = "select s.department_id from __PREFIX__user u,__PREFIX__shift s where u.uid='$uid' and u.shift_id=s.shift_id";
				$msg = M()->query($sql);
				$department_id = $msg[0]['department_id'];
				if($department_id == '')
				{
					$this->error('您尚未签到，请先签到！');
				}
				$operation_type = I('post.operation_type');
				if($operation_type == '人工')
				{
					$operation_type = '0';
				}else{
					$opration_type = '1';
				}
				$data=array(
						'ship_id'=>$ship_id,
						'voyage'=>$voyage,
						'department_id'=>$department_id,
						'location_id'=>$location_id,
						'entrust_company' => $entrust_company,
						'operation_type'=>$operation_type,
						'date'=>date('Y-m-d'),
						'last_operator'=>$_SESSION['uid'],
						'last_operationtime'=>date('Y-m-d H:i:s')
				);
				$instruction=new \Common\Model\CfsInstructionModel();
				if(!$instruction->create($data))
				{
					//对data数据进行验证
					$this->error($instruction->getError());
				}else{
					//验证通过 可以对数据进行操作
					$res=$instruction->add($data);
					if ($res !== false)
					{
						$this->success('新增作业指令成功!',U('index'));
					} else {
						$this->error('操作失败！');
					}
				}
			}else {
				$this->display();
			}
		}else{
			$this->error("您未签到，不能开始工作");
		}
        
	}

	//指令列表
	public function index()
	{
		$instruction = new \Common\Model\CfsInstructionModel();
		$count = $instruction->where("1")->count();
		$per = 10;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page= new \Think\Page($count,$per);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->rollPage=10; // 分页栏每页显示的页数
		$Page -> setConfig('header','共%TOTAL_ROW%条');
		$Page -> setConfig('first','首页');
		$Page -> setConfig('last','共%TOTAL_PAGE%页');
		$Page -> setConfig('prev','上一页');
		$Page -> setConfig('next','下一页');
		$Page -> setConfig('link','indexpagenumb');//pagenumb 会替换成页码
		$Page -> setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 (<font color="red">'.$per.'</font> 条/页 共 %TOTAL_ROW% 条)');
		$show= $Page->show();// 分页显示输出
		$begin=($p-1)*$per;
		$sql = "select i.*,s.ship_name,l.location_name from __PREFIX__ship s,__PREFIX__cfs_instruction i,__PREFIX__location l where i.id !='' and  i.location_id=l.id and i.ship_id=s.id order by i.id desc limit $begin,$per";
		$list = M()->query($sql);
		$this->assign('list',$list);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}

	//编辑指令
	public function edit($id)
	{
		$this->assign('id',$id);
		//根据预报计划ID获取预报计划详情
		$instruction = new \Common\Model\CfsInstructionModel();
		$msg=$instruction->getInstructionMsg($id);
		$this->assign('msg',$msg);
		//作业场地列表
		$location=new \Common\Model\LocationModel();
		$locationlist=$location->getLocationList();
		$this->assign('locationlist',$locationlist);
		//客户列表-代理
		$customer = new \Common\Model\CustomerModel();
		$customer_category=json_decode(customer_category,true);
		$customerlist=$customer->getCustomerList($customer_category['agent']);
		$this->assign ( 'customerlist', $customerlist );
		// 集装箱船列表
		$shipinfo = new \Common\Model\ShipModel ();
		$ship_type = json_decode ( ship_type, true );
		$shiplist = $shipinfo->getShipList ( $ship_type ['container'] );
		$this->assign ( 'shiplist', $shiplist );
		$instruction=new \Common\Model\CfsInstructionModel();
		//获取指令详情
		$msg=$instruction->getInstructionMsg($id);
		$this->assign('msg',$msg);
		//已配箱数
		$instructionContainer=new \Common\Model\CfsInstructionCtnModel();
		//作业班组
		$department_id=$msg['department_id'];
		$department=new \Common\Model\DepartmentModel();
		$dMsg=$department->getDepartmentMsg($department_id);
		$this->assign('dMsg',$dMsg);
		//根据指令ID获取派工详情
		$dispatch=new \Common\Model\DispatchModel();
		$dispatch_detail=$dispatch->getDetail($id, 'cfs');
		$this->assign('dispatch_detail',$dispatch_detail);
		//获取指令下的配箱列表
		$containerlist=$instructionContainer->getContainerList($id);
		$this->assign('containerlist',$containerlist);
		//获取指令下的配货列表
		$instructionCargo=new \Common\Model\CfsInstructionCargoModel();
		$cargolist=$instructionCargo->getCargoList($id);
		$this->assign('cargolist',$cargolist);
		if(I('post.'))
		{
			layout(false);// 临时关闭当前模板的布局功能
			if(I('post.location_name'))
			{
				$location_name=I('post.location_name');
			}else {
				$this->error('装箱场地不能为空！');
			}
			$data_l = filterString($location_name);
			if($data_l == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			//检验理货地址名称是否正确
			$res_l=$location->where("location_name='$location_name'")->field('id')->find();
			if($res_l['id']=='')
			{
				$this->error('理货地点不存在！');
			}else {
				$locationno = $res_l['id'];
			}
			//检查航次是否含有特殊字符
			$voyage = I('post.voyage');
			$data_v = filterString($voyage);
			if($data_v == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}

			//检查委托单位是否含有特殊字符
			$customer_name = I('post.entrust_company');
			$data_l = filterString($entrust_company);
			if($data_l == false)
			{
				$this->error("委托单位不能含有特殊字符");
				exit();
			}
			//检验委托单位名称是否正确
			$res_c = $customer->where("customer_name='$customer_name'")->field('id')->find();
			if($res_c['id']=='')
			{
				$this->error('委托单位不存在');
			}else{
				$entrust_company = $res_c['id'];
			}

			//检查船名是否正确
			$vslname = I('post.shipname');
			$data_s = filterString($vslname);
			if($data_s == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			$ship = new \Common\Model\ShipModel();
			$res_v = $ship->where("ship_name='$vslname'")->field('id')->find();
			if($res_v['id'] == '')
			{
				$this->error('不存在此船');
			}else{
				$ship_id = $res_v['id'];
			}
			$operation_type = I('post.operation_type');
			$data=array(
					'ship_id'=>$ship_id,
					'voyage'=>$voyage,
					'location_id'=>$locationno,
					'entrust_company' => $entrust_company,
					'operation_type'=>$operation_type,
					'last_operator'=>$_SESSION['uid'],
					'last_operationtime'=>date('Y-m-d H:i:s')
			);

			$instruction=new \Common\Model\CfsInstructionModel();
			if(!$instruction->create($data))
			{
				//对data数据进行验证
				$this->error($instruction->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$instruction->where("id=$id")->save($data);
				if ($res !== false)
				{
					$this->success('修改作业指令成功!',U('index'));
				} else {
					$this->error('操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//删除指令
	public function del_instruct($id)
	{
		layout(false);
		$instruction = new \Common\Model\CfsInstructionModel ();
		$res_i=$instruction->where("id='$id'")->field('status')->find();
		$status = $res_i['status'];
		if ($status == '0')
		{
			$res = $instruction->where ( "id='$id'" )->delete ();
			$container = new \Common\Model\CfsInstructionCtnModel();
			$res_c = $container->where("instruction_id='$id'")->delete();
			if ($res !== false)
			{
				$this->success ( '删除指令成功！' );
			} else {
				$this->error ( '操作失败！' );
			}
		}else{
			$this->error("不能删除此指令，指令已作业");
		}
	}
}