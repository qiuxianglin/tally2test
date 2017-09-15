<?php
/**
 * 费率管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class RateController extends AuthController
{
    public function index()
    {
    	//获取费率本列表
    	$Rate=new \Common\Model\RateModel();
    	$where="1";
    	if(I('get.code'))
    	{
    		$code=I('get.code');
    		$where.=" and code='$code'";
    	}
    	if(I('get.name'))
    	{
    		$name=I('get.name');
    		$where.=" and name like'%$name%'";
    	}
    	if(I('get.flag'))
    	{
    		$flag=I('get.flag');
    		$where.=" and flag='$flag'";
    	}
    	$count=$Rate->where($where)->count();
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
    		
    	$ratelist = $Rate->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('ratelist',$ratelist);
    	$this->assign('page',$show);
    	$this->display();
    }
    
    //新增费率本
    public function add()
    {
    	if(I('post.'))
    	{
    		layout(false);
    		$data['code']=$code=strtoupper(trim(I('post.code')));
    		//检验计费代码是否唯一
    		$Rate=new \Common\Model\RateModel;
    		$res_c=$Rate->where("code='$code'")->field('id')->find();
    		if($res_c['id']!='')
    		{
    			$this->error('计费代码不能重复，请确保唯一！');
    		}else {
    			$data = array(
    					'code'=>$code,
    					'name'=>trim(I('post.name')),
    					'discount'=>trim(I('post.discount')),
    					'tax_rate'=>trim(I('post.tax_rate')),
    					'flag'=>trim(I('post.flag')),
    					'first_amount'=>trim(I('post.first_amount')),
    					'first_rate'=>trim(I('post.first_rate')),
    					'second_amount'=>trim(I('post.second_amount')),
    					'second_rate'=>trim(I('post.second_rate')),
    					'third_amount'=>trim(I('post.third_amount')),
    					'third_rate'=>trim(I('post.third_rate')),
    					'fourth_amount'=>trim(I('post.fourth_amount')),
    					'fourth_rate'=>trim(I('post.fourth_rate')),
    					'fifth_amount'=>trim(I('post.fifth_amount')),
    					'fifth_rate'=>trim(I('post.fifth_rate')),
    			);
    			if(!$Rate->create($data))
    			{
    				//对data数据进行验证
    				$this->error($Rate->getError());
    			}else{
    				//验证通过 可以对数据进行操作
    				$res=$Rate->add($data);
    				if($res!==false)
    				{
    					$this->success('新增费率本成功！',U('index'));
    				}else {
    					$this->error('新增费率本失败！');
    				}
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑费率本
    public function edit($id)
    {
    	//获取费率本信息
    	$Rate=new \Common\Model\RateModel();
    	$msg=$Rate->getRateMsg($id);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		layout(false);
    		$data['code']=$code=strtoupper(trim(I('post.code')));
    		//检验计费代码是否唯一
    		$res_c=$Rate->where("code='$code' and id!=$id")->field('id')->find();
    		if($res_c['id']!='')
    		{
    			$this->error('计费代码不能重复，请确保唯一！');
    		}else {
    			$data = array(
    					'code'=>$code,
    					'name'=>trim(I('post.name')),
    					'discount'=>trim(I('post.discount')),
    					'tax_rate'=>trim(I('post.tax_rate')),
    					'flag'=>trim(I('post.flag')),
    					'first_amount'=>trim(I('post.first_amount')),
    					'first_rate'=>trim(I('post.first_rate')),
    					'second_amount'=>trim(I('post.second_amount')),
    					'second_rate'=>trim(I('post.second_rate')),
    					'third_amount'=>trim(I('post.third_amount')),
    					'third_rate'=>trim(I('post.third_rate')),
    					'fourth_amount'=>trim(I('post.fourth_amount')),
    					'fourth_rate'=>trim(I('post.fourth_rate')),
    					'fifth_amount'=>trim(I('post.fifth_amount')),
    					'fifth_rate'=>trim(I('post.fifth_rate')),
    			);
    			if(!$Rate->create($data))
    			{
    				//对data数据进行验证
    				$this->error($Rate->getError());
    			}else{
    				//验证通过 可以对数据进行操作
    				$res=$Rate->where("id='$id'")->save($data);
    				if($res!==false)
    				{
    					$this->success('编辑费率本成功！',U('index'));
    				}else {
    					$this->error('编辑费率本失败！');
    				}
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
}