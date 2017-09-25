<?php
/**
 * 查询统计
 * 门到门拆箱查询
 */
namespace Customer\Controller;
use Think\Controller;

class DdSearchController extends CommonController
{
	//引入页
	public function index(){
		$this->display();
	}
	
	//预报列表
	public function plan()
	{
		//集装箱船列表
		$Ship=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$Ship->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		$Plan=new \Common\Model\DdPlanModel();
		$id = $_SESSION['id'];
		$where="cu.id=$id";
		if(I('get.orderid'))
		{
			$orderid=I('get.orderid');
			$orderid = str_replace("'", " ", $orderid);
			$where.=" and p.orderid='$orderid'";
		}
		if(I('get.vslname'))
		{
			$vslname=I('get.vslname');
			$vslname = str_replace("'", " ", $vslname);
			$where.=" and p.vslname='$vslname'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", " ", $voyage);
			$where.=" and p.voyage='$voyage'";
		}
		$count=$Plan->alias('p')
				->join('tally_dd_plan_cargo c ON c.plan_id=p.id')
				->join('tally_customer cu ON cu.customer_code=c.paycode')
				->where($where)->count();
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

		$list = $Plan->alias('p')
				->join('tally_dd_plan_cargo c ON c.plan_id=p.id')
				->join('tally_customer cu ON cu.customer_code=c.paycode')
				->where($where)->page($p.','.$per)->order('p.id desc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	//查看预报详情
	public function edit($id)
	{
		$Plan=new \Common\Model\DdPlanModel();
		$msg=$Plan->getPlanMsg($id);
		$this->assign('msg',$msg);
		$this->display();
	}
	
	//实时作业查询
	public function real_time()
	{
		// 集装箱船列表
		$Ship=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$Ship->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		// 作业场地
		$Location=new \Common\Model\LocationModel();
		$locationlist=$Location->getLocationList();
		$this->assign('locationlist',$locationlist);

		// 箱状态
		$ctn_status=json_decode(ctn_status,true);
		// 已完成
		$ctn_status_finished=$ctn_status['finished'];
		$id = $_SESSION['id'];
		$customer_code = $_SESSION['customer_code'];
		$CargoModel = new \Common\Model\DdPlanCargoModel();
		$plan_ids = $CargoModel->field('plan_id')->where("paycode='$customer_code'")->select();
		if($plan_ids){
			foreach($plan_ids as $v){
				$arr[] = $v['plan_id'];
			}
			$ids = '('.implode(',',$arr).')';
			$where="c.status in (1,2) and c.plan_id in $ids and o.operation_examine in (1,3)";
			if(I('get.vslname'))
			{
				$vslname=I('get.vslname');
				$vslname = str_replace("'", "", $vslname);
				$where.=" and p.vslname='$vslname'";
			}
			if(I('get.voyage'))
			{
				$voyage=I('get.voyage');
				$voyage = str_replace("'", "", $voyage);
				$where.=" and p.voyage='$voyage'";
			}
			if(I('get.unpackagingplace'))
			{
				$unpackagingplace=I('get.unpackagingplace');
				$where.=" and p.unpackagingplace='$unpackagingplace'";
			}
			$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
			$sql="select p.vslname,p.voyage,p.unpackagingplace,c.* from __PREFIX__dd_plan p,__PREFIX__dd_plan_container  c,__PREFIX__dd_operation o where $where and p.id=c.plan_id and o.ctn_id = c.id";
			$count=count(M()->query($sql));
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
			$sql="select p.vslname,p.voyage,p.unpackagingplace,c.* from __PREFIX__dd_plan p,__PREFIX__dd_plan_container c,__PREFIX__dd_plan_cargo ca,__PREFIX__dd_operation o where $where and p.id=c.plan_id and p.id=ca.plan_id  and o.ctn_id = c.id order by c.id desc limit $begin_num,$per";
			$list=M()->query($sql);
			//遍历结果，取出其它数据
			$num=count($list);
			$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
			for ($i=0;$i<$num;$i++)
			{
				$ctn_id=$list[$i]['id'];
				$DdOperation=new \Common\Model\DdOperationModel();
				$res_o=$DdOperation->where("ctn_id=$ctn_id")->find();
				if($res_o['id']!='')
				{
					$operation_id=$res_o['id'];
					$list[$i]['begin_time']=$res_o['begin_time'];
					//关数
					$levelnum=$DdOperationLevel->sumLevelNum($operation_id);
					$list[$i]['levelnum']=$levelnum;
					//货物件数
					$cargonum=$DdOperationLevel->sumCargoNum($operation_id);
					$list[$i]['cargonum']=$cargonum;
					//残损件数
					$damage_num=$DdOperationLevel->sumDamageNum($operation_id);
					$list[$i]['damage_num']=$damage_num;
					//最新操作时间
					$res_new=$DdOperationLevel->where("operation_id=$operation_id")->field('createtime')->order("id desc")->find();
					if($res_new['createtime']!='')
					{
						$list[$i]['newtime']=$res_new['createtime'];
					}else {
						$list[$i]['newtime']=$res_o['begin_time'];
					}
					$time1 = strtotime ( $list[$i]['newtime'] );
					$time2 = time ();
					if (($time2 - $time1) / 300 > 1) 
					{
						$list [$i] ['red'] = 1;
					}
				}
			}
			$this->assign('list',$list);
		}
		$this->display();
	}
	
	//实时作业详情
	public function realtimeDetail($ctn_id)
	{
		//根据箱ID获取箱详情
		$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
		$msg=$DdPlanContainer->getContainerMsg($ctn_id);
		$this->assign('msg',$msg);
		if($msg!==false)
		{
			$plan_id=$msg['plan_id'];
			$DdPlan=new \Common\Model\DdPlanModel();
			$planMsg=$DdPlan->getPlanMsg($plan_id);
			$this->assign('planMsg',$planMsg);
		}
		//根据箱ID获取作业详情
		$DdOperation=new \Common\Model\DdOperationModel();
		$operationMsg=$DdOperation->getOperationMsgByCtn($ctn_id);
		$this->assign('operationMsg',$operationMsg);
		if($operationMsg['id']!='')
		{
			$operation_id=$operationMsg['id'];
			//根据作业ID获取关列表
			$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
			$levellist=$DdOperationLevel->getLevelList($operation_id);
			$this->assign('levellist',$levellist);
		}
		$this->display();
	}
	
	//完成作业查询
	public function complete()
	{
		//集装箱船列表
		$Ship=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$Ship->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		//作业场地
		$Location=new \Common\Model\LocationModel();
		$locationlist=$Location->getLocationList();
		$this->assign('locationlist',$locationlist);
		//获取箱型列表
		$Container=new \Common\Model\ContainerModel();
		$containerlist=$Container->getContainerList();
		$this->assign('containerlist',$containerlist);
		$id = $_SESSION['id'];
		$customer_code = $_SESSION['customer_code'];
		$CargoModel = new \Common\Model\DdPlanCargoModel();
		$plan_ids = $CargoModel->field('plan_id')->where("paycode='$customer_code'")->select();
		if($plan_ids){
			foreach($plan_ids as $v){
				$arr[] = $v['plan_id'];
			}
			$ids = '('.implode(',',$arr).')';
			$where = "c.plan_id in $ids";
			if(I('get.ship_name'))
			{
				$ship_name=I('get.ship_name');
				$ship_name = str_replace("'", "", $ship_name);
				$where.=" and p.ship_name='$ship_name'";
			}
			if(I('get.vargo'))
			{
				$vargo=I('get.vargo');
				$vargo = str_replace("'", "", $vargo);
				$where.=" and p.vargo='$vargo'";
			}
			if(I('get.location_id'))
			{
				$location_id=I('get.location_id');
				$where.=" and p.location_id=$location_id";
			}
			if(I('get.ctn_no'))
			{
				$ctn_no=I('get.ctn_no');
				$ctn_no = str_replace("'", "", $ctn_no);
				$where.=" and p.ctn_no='$ctn_no'";
			}
			if(I('get.flflag'))
			{
				$flflag=I('get.flflag');
				$where.=" and p.flflag='$flflag'";
			}
			if(I('get.ctn_type_code'))
			{
				$ctn_type_code=I('get.ctn_type_code');
				$ctn_type_code = str_replace("'", "", $ctn_type_code);
				$where.=" and p.ctn_type_code='$ctn_type_code'";
			}
			if (I('get.begin_time') && I ('get.end_time')) 
			{
				$begin_time = I ( 'begin_time' );
				$end_time = I ( 'end_time' );
				$end_time=strtotime("$end_time +1 day");
				$end_time=date('Y-m-d',$end_time);
				$where .= " and p.createtime between '$begin_time' and '$end_time' ";
			}
			if( I ('post.bl_no') )
			{
				$bl_no=I('post.bl_no');
				$bl_no = str_replace("'", "", $bl_no);
				$where.=" and p.content like '".'%"bl_no":"'. $bl_no .'%\'';
			}
			$DdProve=new \Common\Model\DdProveModel();
			$count = $DdProve->field("p.*")->alias('p')
			->join("tally_dd_plan_container c on c.id=p.ctn_id")
			->where($where)->count();
			$per = 15;
			if($_GET['p'])
			{
				$p=$_GET['p'];
			}else {
				$p=1;
			}
			// 分页显示输出
			$Page=new \Common\Model\PageModel();
			$show= $Page->show($count, $per);
			$this->assign('page',$show);
			$begin_num=($p-1)*$per;
			$list = $DdProve->field("p.*")->alias('p')
			->join("tally_dd_plan_container c on c.id=p.ctn_id")
			->where($where)->page($p.','.$per)->order('p.id desc')->select();
		
			$this->assign('list',$list);
		}
		$this->display();
	}
	
	//完成作业详情
	public function completeDetail($ctn_id)
	{
		//根据箱ID获取单证详情
		$DdProve=new \Common\Model\DdProveModel();
		$msg=$DdProve->getProveMsgByCtn($ctn_id);
		$this->assign('msg',$msg);
		//根据箱ID获取作业详情
		$DdOperation=new \Common\Model\DdOperationModel();
		$operationMsg=$DdOperation->getOperationMsgByCtn($ctn_id);
		$this->assign('operationMsg',$operationMsg);
		if($operationMsg['id']!='')
		{
			$operation_id=$operationMsg['id'];
			//根据作业ID获取关列表
			$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
			$levellist=$DdOperationLevel->getLevelList($operation_id);
			$this->assign('levellist',$levellist);
		}
		//该客户可查看照片的权限
		$Customer = M('customer');
		$id = $_SESSION['id'];
		$cus = $Customer->field('authority')->where('id='.$id)->find();
		$authority = json_decode($cus['authority'],true);
		$photoauth = $authority['photo'];
		$this->assign('photoauth',$photoauth);
		//客户是否有权限
		$a = in_array('1',$photoauth) && in_array('2',$photoauth);
		$b = in_array('1',$photoauth) && !in_array('2',$photoauth);
		$c = !in_array('1',$photoauth) && in_array('2',$photoauth);
		$this->assign('a',$a);
		$this->assign('b',$b);
		$this->assign('c',$c);
		//修改记录-待做
		$this->display();
	}
	
	//分箱单证
	public function documentByCtn()
	{
		//获取船舶列表
		$ship_type=json_decode(ship_type,true);
		$Ship=new \Common\Model\ShipModel();
		$shiplist=$Ship->getShipList($ship_type['container']);
		$this->assign('shiplist',$shiplist);
		//获取作业地点列表
		$Location=new \Common\Model\LocationModel();
		$locationlist=$Location->getLocationList();
		$this->assign('locationlist',$locationlist);
	
		$id = $_SESSION['id'];
		$customer_code = $_SESSION['customer_code'];
		$CargoModel = new \Common\Model\DdPlanCargoModel();
		$plan_ids = $CargoModel->field('plan_id')->where("paycode='$customer_code'")->select();
		if($plan_ids){
			foreach($plan_ids as $v){
				$arr[] = $v['plan_id'];
			}
			$ids = '('.implode(',',$arr).')';
			$where="c.plan_id in $ids";
			if(I('get.ship_name'))
			{
				$ship_name=I('get.ship_name');
				$ship_name = str_replace("'", "", $ship_name);
				$where.=" and p.ship_name='$ship_name'";
			}
			if(I('get.vargo'))
			{
				$vargo=I('get.vargo');
				$vargo = str_replace("'", "", $vargo);
				$where.=" and p.vargo='$vargo'";
			}
			if(I('get.location_id'))
			{
				$location_id=I('get.location_id');
				$where.=" and p.location_id='$location_id'";
			}
			if(I('get.ctn_no'))
			{
				$ctn_no=I('get.ctn_no');
				$ctn_no = str_replace("'", "", $ctn_no);
				$where.=" and p.ctn_no='$ctn_no'";
			}
			if(I('get.flflag'))
			{
				$flflag=I('get.flflag');
				$where.=" and p.flflag='$flflag'";
			}
			if (I ( 'begin_time' ) && I ( 'end_time' ))
			{
				$begin_time = I ( 'get.begin_time' );
				$end_time = I ( 'get.end_time' );
				$end_time=strtotime("$end_time +1 day");
				$end_time=date('Y-m-d',$end_time);
				$where .= " and p.createtime between '$begin_time' and '$end_time' ";
			}
			$DdProve=new \Common\Model\DdProveModel();
			//单证总数
			$count = $DdProve->alias('p')->field('p.*')
				->join('tally_dd_plan_container c ON p.ctn_id=c.id')
				->where ( $where )->count();
			$per = 15;
			if ($_GET ['p']) {
				$p = $_GET ['p'];
			} else {
				$p = 1;
			}
			// 分页显示输出
			$Page=new \Common\Model\PageModel();
			$show = $Page->show ($count, $per); 
			$this->assign ( 'page', $show );
	
			//列表
			$list = $DdProve->alias('p')->field('p.*')
				->join('tally_dd_plan_container c ON p.ctn_id=c.id')
				->where($where)->page($p.','.$per)->order('p.id desc')->select();
			$this->assign('list',$list);
		}
		$this->display();
	}
	
	//分箱单证详情
	public function documentByCtnMsg()
	{
		if(!empty($_GET['id'])){
			$ctn_id = $_GET['id'];
			$DdProve=new \Common\Model\DdProveModel();
			$msg=$DdProve->where('ctn_id='.$ctn_id)->find();
			$this->assign('msg',$msg);
			//根据箱ID获取关操作记录
			if($msg['ctn_id']!='')
			{
				$ctn_id=$msg['ctn_id'];
				//获取作业ID
				$DdOperation=new \Common\Model\DdOperationModel();
				$res_o=$DdOperation->getOperationMsgByCtn($ctn_id);
				if($res_o['id']!='')
				{
					$begin_time=$res_o['begin_time'];
					$this->assign('begin_time',$begin_time);
					$operation_id=$res_o['id'];
					$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
					$levellist=$DdOperationLevel->getLevelList($operation_id);
					$this->assign('list',$levellist);
				}
			}
			$this->display();
		}
	}
}
?>