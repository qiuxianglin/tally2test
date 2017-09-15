<?php
/**
 * 船期维护
 */
namespace Index\Controller;
use Index\Common\BaseController;

class ShipScheduleController extends BaseController
{
	public function index()
	{
		//获取船期列表
		$ShipSchedule=new \Common\Model\ShipScheduleModel();
		$where="1";
		if(I('get.code'))
		{
			$code=I('get.code');
			$where.=" and containerTypeCode='$code'";
		}
		$count=$ShipSchedule->where($where)->count();
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
			
		$list = $ShipSchedule->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	
	//新增船期
	public function add()
	{
		//获取船列表
		$Ship=new \Common\Model\ShipModel();
		$shiplist=$Ship->getShipList();
		$this->assign('shiplist',$shiplist);
		//获取港口列表
		$Port=new \Common\Model\PortModel();
		$portlist=$Port->getPortList();
		$this->assign('portlist',$portlist);
		if(I('post.'))
		{
			layout(false);
			if(I('post.shipname') and I('post.voyage'))
			{
				//检验输入的船舶代码是否正确
				$shipname=I('post.shipname');
				$res_s=$Ship->where("ship_name='$shipname'")->field('id')->find();
				if($res_s['id']=='')
				{
					$this->error('输入的船舶名称不存在！');
					exit();
				}else {
					$data['ship_id']=$res_s['id'];
				}
				if(I('post.loading_port'))
				{
					//检验起运港名称是否正确
					$loading_port=I('post.loading_port');
					$res_p=$Port->where("name='$loading_port'")->field('id')->find();
					if($res_p['id'])
					{
						$data['loading_port']=$res_p['id'];
					}
				}
				if(I('post.destination_port'))
				{
					//检验目的港名称是否正确
					$destination_port=I('post.destination_port');
					$res_p2=$Port->where("name='$destination_port'")->field('id')->find();
					if($res_p2['id'])
					{
						$data['destination_port']=$res_p2['id'];
					}
				}
				$data['voyage']=I('post.voyage');
				$data['sailing_date']=I('post.sailing_date');
				$data['arrival_date']=I('post.arrival_date');
				$data['uid']=$_SESSION['uid'];
				$data['createtime']=date('Y-m-d H:i:s');
				$ShipSchedule=new \Common\Model\ShipScheduleModel();
				$res=$ShipSchedule->add($data);
				if($res!==false)
				{
					$this->success('新增船期成功！',U('index'));
				}else {
					$this->error('操作失败！');
				}
			}else {
				$this->error('船舶名称、航次不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//编辑船期
	public function edit($id)
	{
		//获取船列表
		$Ship=new \Common\Model\ShipModel();
		$shiplist=$Ship->getShipList();
		$this->assign('shiplist',$shiplist);
		//获取港口列表
		$Port=new \Common\Model\PortModel();
		$portlist=$Port->getPortList();
		$this->assign('portlist',$portlist);
		//获取船期信息
		$ShipSchedule=new \Common\Model\ShipScheduleModel();
		$msg=$ShipSchedule->getMsg($id);
		$this->assign('msg',$msg);
		if(I('post.'))
		{
			layout(false);
			if(I('post.shipname') and I('post.voyage'))
			{
				//检验输入的船舶代码是否正确
				$shipname=I('post.shipname');
				$res_s=$Ship->where("ship_name='$shipname'")->field('id')->find();
				if($res_s['id']=='')
				{
					$this->error('输入的船舶名称不存在！');
					exit();
				}else {
					$data['ship_id']=$res_s['shipinfoid'];
				}
				if(I('post.loading_port'))
				{
					//检验起运港名称是否正确
					$loading_port=I('post.loading_port');
					$res_p=$Port->where("name='$loading_port'")->field('id')->find();
					if($res_p['id'])
					{
						$data['loading_port']=$res_p['id'];
					}
				}
				if(I('post.destination_port'))
				{
					//检验目的港名称是否正确
					$destination_port=I('post.destination_port');
					$res_p2=$Port->where("name='$destination_port'")->field('id')->find();
					if($res_p2['id'])
					{
						$data['destination_port']=$res_p2['id'];
					}
				}
				$data['voyage']=I('post.voyage');
				$data['sailing_date']=I('post.sailing_date');
				$data['arrival_date']=I('post.arrival_date');
				$data['uid']=$_SESSION['uid'];
				$data['createtime']=date('Y-m-d H:i:s');
				$res=$ShipSchedule->where("id=$id")->save($data);
				if($res!==false)
				{
					$this->success('编辑船期成功！',U('index'));
				}else {
					$this->error('操作失败！');
				}
			}else {
				$this->error('船舶名称、航次不能为空！');
			}
		}else {
			$this->display();
		}
	}
}