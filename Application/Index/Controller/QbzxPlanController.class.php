<?php
/**
 * 起泊装箱-预报计划
 */
namespace Index\Controller;
use Index\Common\BaseController;

class QbzxPlanController extends BaseController
{
	//预报计划列表
	public function index()
	{
		$plan=new \Common\Model\QbzxPlanModel();
		$where="1";
		$count=$plan->where($where)->count();
		$per = 15;
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
		$this->assign('page',$show);
		
		//列表
		$begin=($p-1)*$per;
		$sql="select p.*,s.ship_name,l.location_name,c.customer_name as customer from __PREFIX__qbzx_plan p,__PREFIX__ship s,__PREFIX__location l,__PREFIX__customer c where p.id!='' and p.ship_id=s.id and p.location_id=l.id and p.entrust_company=c.id order by p.id desc limit $begin,$per";
		$list=M()->query($sql);
		$this->assign('list',$list);
		$this->display();
	}
	
	//新增预报计划
	public function add() 
	{
		$ship=new \Common\Model\ShipModel();
		$location=new \Common\Model\LocationModel();
		$customer=new \Common\Model\CustomerModel();
		$cargoagent=new \Common\Model\CargoAgentModel();
		if (! IS_POST) 
		{
			//理货地点列表
			$location_type=json_decode(location_type,true);
			$locationlist = $location->getLocationList($location_type['port']);
			$this->assign ( 'locationlist', $locationlist );
			//集装箱船列表
			$ship_type=json_decode(ship_type,true);
			$shiplist =$ship->getShipList($ship_type['container']);
			$this->assign ( 'shiplist', $shiplist );
			//客户列表-代理
			$customer_category=json_decode(customer_category,true);
			$customerlist=$customer->getCustomerList($customer_category['agent']);
			$this->assign ( 'customerlist', $customerlist );
			//货代
			$cargoAgentList=$cargoagent->getCargoAgentList();
			$this->assign ( 'cargoAgentList', $cargoAgentList );
			$this->display ();
		} else {
			layout ( false );
			if(I('post.entrustno')=='' or I('post.entrust_company')=='' or I('post.location_name')=='' or I('post.shipname')=='' or I('post.voyage')=='' or I('post.total_ticket')=='' or I('post.cargo_agent')=='')
			{
				$this->error('委托编号、委托单位、理货地点、船舶名称、航次、总票数、货代不能为空！');
			}
			//检验理货地址名称是否正确
			$location_name = I ( 'post.location_name' );
			$data_l = filterString($location_name);
			if($data_l == false){
				$this->error('不能含有特殊字符');
				exit();
			}
			$res_l=$location->where("location_name='$location_name'")->field('id')->find();
			if($res_l['id']=='')
			{
				$this->error('理货地点不存在！');
			}else {
				$location_id = $res_l['id'];
			}
			
			//检查委托单位是否正确
			$customer_name = I('post.entrust_company');
			$data_c = filterString($customer_name);
			if($data_c == false){
				$this->error('不能含有特殊字符');
				exit();
			}
			$res_c = $customer->where("customer_name='$customer_name'")->field('id')->find();
			if($res_c['id']=='')
			{
				$this->error('委托单位不存在');
			}else{
				$entrust_company = $res_c['id'];
			}
			
			//检验船舶名称是否正确
			$shipname = I ( 'post.shipname' );
			$data_s = filterString($shipname);
			if($data_s == false)
			{
				$this->error('不能含有特殊字符');
				exit();
			}
			$res_s=$ship->where("ship_name='$shipname'")->field('id')->find();
			if($res_s['id']=='')
			{
				$this->error('船舶名称不存在！');
			}else {
				$ship_id = $res_s['id'];
			}
			
			//检验货代名称是否正确
			$cargo_agent = I ( 'post.cargo_agent' );
			$data_ca = filterString($cargo_agent);
			if($data_ca == false)
			{
				$this->error('不能含有特殊字符');
				exit();
			}
			$res_ca=$cargoagent->where("name='$cargo_agent'")->field('id')->find();
			if($res_ca['id']=='')
			{
				$this->error('货代名称不存在！');
			}else {
				$cargo_agent_id = $res_ca['id'];
			}
			// 将提交过来的数据强制类型转换
			$total_ctn = intval(I('post.total_ctn'));
			if($total_ctn>2147483647){
				$this->error("总箱数错误！");
				// echo $total_ctn;
				exit;
			}
			$data=array(
					'entrustno'=>trim(I ( 'post.entrustno' ),"'"),
					'entrust_company'=>$entrust_company,
					'location_id'=>$location_id,
					'ship_id'=>$ship_id,
					'voyage'=>trim(I('post.voyage'),"'"),
					// 'total_ctn'=>trim(I ('post.total_ctn')),
					'total_ctn'=>$total_ctn,
					'total_ticket'=>trim(I ( 'post.total_ticket' ),"'"),
					'total_package'=>trim(I ( 'post.total_package' ),"'"),
					'total_weight'=>trim(I ( 'post.total_weight' ),"'"),
					'cargo_agent_id'=>$cargo_agent_id,
					'packing_require'=>trim(I ('post.packing_require')),
					'entrust_time'=>date ('Y-m-d'),
					'last_operator'=>$_SESSION ['uid'],
					'last_operationtime'=>date ('Y-m-d H:i:s'),
					
			);
			$plan=new \Common\Model\QbzxPlanModel();
			if(!$plan->create($data))
			{
				//对data数据进行验证
				$this->error($plan->getError());
			}else{
				//验证通过 可以对数据进行操作
				$plan_id=$plan->add($data);
				if ($plan_id)
				{
					$this->success ( '新增起泊装箱预报计划成功', U ('index') );
				} else {
					$this->error ('操作失败！');
				}
			}
		}
	}
	
	//编辑预报计划
	public function edit($plan_id)
	{
		$this->assign('plan_id',$plan_id);
		//根据预报计划ID获取预报计划详情
		$customer=new \Common\Model\CustomerModel();
		$plan=new \Common\Model\QbzxPlanModel();
		$msg=$plan->getPlanMsg($plan_id);
		$this->assign('msg',$msg);
		$shipModel=new \Common\Model\ShipModel();
		$location=new \Common\Model\LocationModel();
		//理货地点列表
		$location_type=json_decode(location_type,true);
		$locationlist = $location->getLocationList($location_type['type']);
		$this->assign ( 'locationlist', $locationlist );
		//集装箱船列表
		$shiplist =$shipModel->getShipList(1);
		$this->assign ( 'shiplist', $shiplist );
		//客户列表-代理
		$customer_category=json_decode(customer_category,true);
		$customerlist=$customer->getCustomerList($customer_category['agent']);
		$this->assign ( 'customerlist', $customerlist );
		//货代
		$cargoAgent=new \Common\Model\CargoAgentModel();
		$cargoAgentList=$cargoAgent->getCargoAgentList();
		$this->assign ( 'cargoAgentList', $cargoAgentList );
		
		//预报计划-配箱
		$planContainer=new \Common\Model\QbzxPlanCtnModel();
		$containerList=$planContainer->getContainerList($plan_id);
		$this->assign('containerlist',$containerList);
		
		//预报计划-配货
		$planCargo=new \Common\Model\QbzxPlanCargoModel();
		$cargoList=$planCargo->getCargoList($plan_id);
		$this->assign('cargolist',$cargoList);
		
		if(I('post.'))
		{
			layout ( false );
			if(I('post.entrustno')=='' or I('post.entrust_company')=='' or I('post.location_name')=='' or I('post.shipname')=='' or I('post.voyage')=='' or I('post.total_ticket')=='' or I('post.cargo_agent')=='')
			{
				$this->error('委托编号、委托单位、理货地点、船舶名称、航次、总票数、货代不能为空！');
			}
			//检验理货地址代码是否正确
			$location_name = I ( 'post.location_name' );
			$data_l = filterString($location_name);
			if($data_l == false){
				$this->error('不能含有特殊字符');
				exit();
			}
			$res_l=$location->where("location_name='$location_name'")->field('id')->find();
			if($res_l['id']=='')
			{
				$this->error('理货地点不存在！');
			}else {
				$location_id = $res_l['id'];
			}
			
			//检查委托单位是否正确
			$customer_name = I('post.entrust_company');
			$data_c = filterString($customer_name);
			if($data_c == false){
				$this->error('不能含有特殊字符');
				exit();
			}
			$res_c = $customer->where("customer_name='$customer_name'")->field('id')->find();
			if($res_c['id']=='')
			{
				$this->error('委托单位不存在');
			}else{
				$entrust_company = $res_c['id'];
			}

			//检验船舶名称是否正确
			$shipname = I ( 'post.shipname' );
			$data_s = filterString($shipname);
			if($data_s == false){
				$this->error('不能含有特殊字符');
				exit();
			}
			$res_s=$shipModel->where("ship_name='$shipname'")->field('id')->find();
			if($res_s['id']=='')
			{
				$this->error('船舶名称不存在！');
			}else {
				$ship_id = $res_s['id'];
			}

			//检验货代名称是否正确
			$cargo_agent = I ( 'post.cargo_agent' );
			$data_ca = filterString($cargo_agent);
			if($data_ca == false){
				$this->error('不能含有特殊字符');
				exit();
			}
			$res_ca=$cargoAgent->where("name='$cargo_agent'")->field('id')->find();
			if($res_ca['id']=='')
			{
				$this->error('货代名称不存在！');
			}else {
				$cargo_agent_id = $res_ca['id'];
			}

			// 将提交过来的数据强制类型转换
			$total_ctn = intval(I('post.total_ctn'));
			if($total_ctn>2147483647){
				$this->error("总箱数错误！");
				exit;
			}
			$data=array(
					'entrustno'=>trim(I ( 'post.entrustno' ),"'"),
					'entrust_company'=>$entrust_company,
					'location_id'=>$location_id,
					'ship_id'=>$ship_id,
					'voyage'=>trim(I('post.voyage'),"'"),
					'total_ctn'=>trim(I ('post.total_ctn'),"'"),
					'total_ticket'=>trim(I ( 'post.total_ticket' ),"'"),
					'total_package'=>trim(I ( 'post.total_package' ),"'"),
					'total_weight'=>trim(I ( 'post.total_weight' ),"'"),
					'cargo_agent_id'=>$cargo_agent_id,
					'packing_require'=>trim(I ('post.packing_require'),"'"),
					'entrust_time'=>date ('Y-m-d'),
					'last_operator'=>$_SESSION ['uid'],
					'last_operationtime'=>date ('Y-m-d H:i:s'),
					
			);
			if(!$plan->create($data))
			{
				//对data数据进行验证
				$this->error($plan->getError());
			}else{
				//验证通过 可以对数据进行验证
				$res = $plan->where ( "id=$plan_id" )->save ($data);
				if ($res !== false)
				{
					$this->success ('修改起泊装箱预报计划成功');
				} else {
					$this->error ( '修改失败');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//删除预报
	public function del($plan_id)
	{
		layout(false);
		//判断预报下是否存在指令，存在不允许删除
		$instruction=new \Common\Model\QbzxInstructionModel();
		$instruction_num=$instruction->where("plan_id=$plan_id")->count();
		if($instruction_num>0)
		{
			//存在指令，不允许删除
			$this->error('该预报已有指令，不允许删除！');
		}else {
			//不存在指令，允许删除
			//删除预报
			$plan=new \Common\Model\QbzxPlanModel();
			$res_p=$plan->where("id=$plan_id")->delete();
			$container=new \Common\Model\QbzxPlanCtnModel();
			$res_ctn=$container->where("plan_id=$plan_id")->delete();
			$cargo=new \Common\Model\QbzxPlanCargoModel();
			$res_c=$cargo->where("plan_id=$plan_id")->delete();
			if($res_p!==false and $res_ctn!==false and $res_c!==false)
			{
				$this->success('删除预报计划成功！');
			}else {
				$this->error('删除预报计划失败！');
			}
		}
	}
}
?>