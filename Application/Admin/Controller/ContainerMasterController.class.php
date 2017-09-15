<?php
/**
 * 箱主信息维护
 * 2016-11-21
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class ContainerMasterController extends AuthController
{
	public function index()
	{
		//获取箱主列表
		$container_master=new \Common\Model\ContainerMasterModel();
		$where="1";
		if(I('get.code'))
		{
			$code=I('get.code');
			$code = str_replace("'", "", $code);
			$where.=" and ctn_master_code='$code'";
		}
		if(I('get.name'))
		{
			$name=I('get.name');
			$name = str_replace("'", "", $name);
			$where.=" and ctn_master like '%$name%'";
		}
		$count=$container_master->where($where)->count();
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
			
		$list = $container_master->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('cList',$list);
		$this->assign('page',$show);
		$this->display();
	}
	
	//新增箱主
	public function add()
	{
		$container_master = new \Common\Model\ContainerMasterModel();
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.containerMasterCode'),"'"));
			//判断箱主代码唯一性
			$res_c=$container_master->where("ctn_master_code='$code'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X箱主代码不能重复，确保唯一！');
			}
			$data = array(
					'ctn_master_code'=>$code,
					'ctn_master'=>trim(I('post.containerMaster'))
			);
			if(!$container_master->create($data))
			{
				//对data数据进行验证
				$this->error($container_master->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$container_master->add($data);
				if ($res)
				{
					$this->success('新增箱主成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//编辑箱主
	public function edit($id)
	{
		$container_master = new \Common\Model\ContainerMasterModel();
		//获取作业地点信息
		$msg=$container_master->getContainerMasterMsg($id);
		$this->assign('msg',$msg);
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.containerMasterCode'),"'"));
			//判断箱主代码唯一性
			$res_c=$container_master->where("ctn_master_code='$code' and id!='$id'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X箱主代码已被使用，不能重复，确保唯一！');
			}
			$data = array(
					'ctn_master_code'=>$code,
					'ctn_master'=>trim(I('post.containerMaster'))
			);
			if(!$container_master->create($data))
			{
				//对data数据进行验证
				$this->error($container_master->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$container_master->where("id='$id'")->save($data);
				if ($res)
				{
					$this->success('编辑箱主成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
}