<?php
/**
 * 门到门拆箱-预报计划管理
 */
namespace Customer\Controller;
use Think\Controller;

class DdPlanController extends CommonController
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
}