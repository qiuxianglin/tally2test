<?php
/**
 * 船舶信息维护
 * 2016-11-21
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class ShipController extends AuthController
{
	public function index()
	{
		//获取船舶列表
	    $ship = new \Common\Model\ShipModel();
		$where="1";
		if(I('get.code'))
		{
			$code=I('get.code');
			$code = str_replace("'", "", $code);
			$where.=" and ship_code='$code'";
		}
		if(I('get.name'))
		{
			$name=I('get.name');
			$name = str_replace("'", "", $name);
			$where.=" and ship_name like'%$name%'";
		}
		if(I('get.ship_type'))
		{
			$ship_type=I('get.ship_type');
			$where.=" and ship_type='$ship_type'";
		}
		$count=$ship->where($where)->count();
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
		 
		$list = $ship->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('shipList',$list);
		$this->assign('page',$show);
		$this->display();
	}
	
	//新增船舶
	public function add()
	{
		//获取船代列表
		$shipagent = new \Common\Model\ShipAgentModel();
		$shipAgentList=$shipagent->getShipAgentList();
		$this->assign('shipAgentList',$shipAgentList);
		$ship = new \Common\Model\ShipModel();
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.ship_code'),"'"));
			//判断船舶代码唯一性
			$res_c=$ship->where("ship_code='$code'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X船舶代码不能重复，确保唯一！');
			}
			$data = array(
					'ship_code'=>$code,
					'ship_name'=>trim(I('post.ship_name'),"'"),
					'ship_english_name'=>trim(I('post.ship_english_name'),"'"),
					'ship_type'=>I('post.ship_type'),
					'ship_route'=>I('post.ship_route'),
					'warehouse_number'=>trim(I('post.warehouse_number')),
					'imo'=>trim(I('post.imo')),
					'regular_ship'=>I('post.regular_ship'),
					'nationality'=>trim(I('post.nationality')),
					'linkman'=>trim(I('post.linkman')),
					'telephone'=>trim(I('post.telephone')),
					'agent_id'=>I('post.agent_id')
			);
			if(!$ship->create($data))
			{
				// 对data数据进行验证
				$this->error($ship->getError());
			}else{
				// 验证通过 可以进行其他数据操作
				$res=$ship->add($data);
				if ($res!==false)
				{
					$this->success('新增船舶成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//编辑船舶
	public function edit($id)
	{
		//获取船代列表
		$shipagent = new \Common\Model\ShipAgentModel();
		$shipAgentList=$shipagent->getShipAgentList();
		$this->assign('shipAgentList',$shipAgentList);
		$ship = new \Common\Model\ShipModel();
		//获取船舶信息
		$msg=$ship->getShipMsg($id);
		$this->assign('msg',$msg);
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.ship_code'),"'"));
			//判断船舶代码唯一性
			$res_c=$ship->where("ship_code='$code' and id!='$id'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X船舶代码已被使用，不能重复，确保唯一！');
			}
			$data = array(
					'ship_code'=>$code,
					'ship_name'=>trim(I('post.ship_name'),"'"),
					'ship_english_name'=>trim(I('post.ship_english_name'),"'"),
					'ship_type'=>I('post.ship_type'),
					'ship_route'=>I('post.ship_route'),
					'warehouse_number'=>trim(I('post.warehouse_number')),
					'imo'=>trim(I('post.imo')),
					'regular_ship'=>I('post.regular_ship'),
					'nationality'=>trim(I('post.nationality')),
					'linkman'=>trim(I('post.linkman')),
					'telephone'=>trim(I('post.telephone')),
					'agent_id'=>I('post.agent_id')
			);
			if(!$ship->create($data))
			{
				// 对data数据进行验证
				$this->error($ship->getError());
			}else{
				// 验证通过 可以进行其他数据操作
				$res=$ship->where("id='$id'")->save($data);
				if ($res)
				{
					$this->success('编辑船舶成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}	
		}else {
			$this->display();
		}
	}
}