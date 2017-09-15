<?php
/**
 * 箱型信息维护
 * 2016-11-21
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class ContainerController extends AuthController
{
	public function index()
	{
		//获取箱型列表
		$container=new \Common\Model\ContainerModel();
		$where="1";
		if(I('get.code'))
		{
			$code=I('get.code');
			$code = str_replace("'", "", $code);
			$where.=" and ctn_type_code='$code'";
		}
		if(I('get.type'))
		{
			$type=I('get.type');
			$type = str_replace("'", "", $type);
			$where.=" and ctn_type='$type'";
		}
		if(I('get.size'))
		{
			$size=I('get.size');
			$size = str_replace("'", "", $size);
			$where.=" and ctn_size='$size'";
		}
		$count=$container->where($where)->count();
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
			
		$list = $container->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('cList',$list);
		$this->assign('page',$show);
		$this->display();
	}
	
	//新增箱型
	public function add()
	{
		$container=new \Common\Model\ContainerModel();
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.ctn_type_code'),"'"));
			//判断箱型代码唯一性
			$res_c=$container->where("ctn_type_code='$code'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X箱型代码不能重复，确保唯一！');
			}
			$data = array(
					'ctn_type_code'=>$code,
					'ctn_type'=>strtoupper(trim(I('post.ctn_type'))),
					'ctn_size'=>trim(I('post.ctn_size'))
			);
			if(!$container->create($data))
			{
				//对data数据进行验证
				$this->error($container->getError());
			}else{
				//验证通过 可以对数据进行处理
				$res=$container->add($data);
				if ($res)
				{
					$this->success('新增箱型成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//编辑箱型
	public function edit($id)
	{
		$container = new \Common\Model\ContainerModel();
		//获取作业地点信息
		$msg=$container->getContainerMsg($id);
		$this->assign('msg',$msg);
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.ctn_type_code'),"'"));
			//判断箱型代码唯一性
			$res_c=$container->where("ctn_type_code='$code' and id!='$id'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X箱型代码已被使用，不能重复，确保唯一！');
			}
			$data = array(
					'ctn_type_code'=>$code,
					'ctn_type'=>strtoupper(trim(I('post.ctn_type'))),
					'ctn_size'=>trim(I('post.ctn_size'))
			);
			if(!$container->create($data))
			{
				//对data数据进行验证
				$this->error($container->getError());
			}else{
				//验证通过 可以对数据进行处理
				$res=$container->where("id='$id'")->save($data);
				if ($res)
				{
					$this->success('编辑箱型成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
}