<?php
/**
 * 费率明细维护
 * 2016-11-21
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class RateDetailController extends AuthController
{
    public function index()
    {
    	//获取箱型列表
    	$container=new \Common\Model\ContainerModel();
    	$clist = $container->distinct(true)->field('ctn_size')->order('id desc')->select();
    	$this->assign('clist',$clist);
    	$clist2 = $container->distinct(true)->field('ctn_type')->order('id desc')->select();
    	$this->assign('clist2',$clist2);
    	//获取费率明细列表
    	$RateDetail=new \Common\Model\RateDetailModel();
    	$where="1";
    	if(I('get.container_size'))
    	{
    		$container_size=I('get.container_size');
    		$where.=" and container_size='$container_size'";
    	}
    	if(I('get.container_type'))
    	{
    		$container_type=I('get.container_type');
    		$where.=" and container_type='$container_type'";
    	}
    	$count=$RateDetail->where($where)->count();
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
    		
    	$list = $RateDetail->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('list',$list);
    	$this->assign('page',$show);
    	$this->display();
    }
    
    //新增费率明细
    public function add()
    {
    	//获取箱型列表
		$container=new \Common\Model\ContainerModel();
		$clist = $container->distinct(true)->field('ctn_size')->order('id desc')->select();
		$this->assign('clist',$clist);
		$clist2 = $container->distinct(true)->field('ctn_type')->order('id desc')->select();
		$this->assign('clist2',$clist2);
    	if(I('post.'))
    	{
    		layout(false);
    		$RateDetail=new \Common\Model\RateDetailModel();
    		$data=array(
    				'container_size'=>I('post.container_size'),
    				'container_type'=>I('post.container_type'),
    				'full_rate'=>trim(I('post.full_rate')),
    				'mixed_rate'=>trim(I('post.mixed_rate')),
    		);
    		if(!$RateDetail->create($data))
    		{
    			//对data数据进行验证
    			$this->error($RateDetail->getError());
    		}else{
    			//验证通过 可以对数据进行操作
    			$res=$RateDetail->add($data);
    			if($res!==false)
    			{
    				$this->success('新增费率明细成功！',U('index'));
    			}else {
    				$this->error('新增费率明细失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑费率明细
    public function edit($id)
    {
    	//获取箱型列表
    	$container=new \Common\Model\ContainerModel();
    	$clist = $container->distinct(true)->field('ctn_size')->order('id desc')->select();
    	$this->assign('clist',$clist);
    	$clist2 = $container->distinct(true)->field('ctn_type')->order('id desc')->select();
    	$this->assign('clist2',$clist2);
    	//获取费率明细信息
    	$RateDetail=new \Common\Model\RateDetailModel();
    	$msg=$RateDetail->getRateDetail($id);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		layout(false);
    		$data=array(
    				'container_size'=>I('post.container_size'),
    				'container_type'=>I('post.container_type'),
    				'full_rate'=>trim(I('post.full_rate')),
    				'mixed_rate'=>trim(I('post.mixed_rate')),
    		);
    		if(!$RateDetail->create($data))
    		{
    			//对data数据进行验证
    			$this->error($RateDetail->getError());
    		}else{
    			//验证通过 可以对数据进行操作
    			$res=$RateDetail->where("id='$id'")->save($data);
    			if($res!==false)
    			{
    				$this->success('编辑费率明细成功！',U('index'));
    			}else {
    				$this->error('编辑费率明细失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
}