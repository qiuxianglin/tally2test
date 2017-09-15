<?php
/**
 * 作业地点维护
 * 2016-11-21
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class LocationController extends AuthController
{
	public function index()
	{
		//获取所有作业地点列表
		$location=new \Common\Model\LocationModel();
		$where="1";
		if(I('get.location_code'))
		{
			$code=I('get.location_code');
			$code = str_replace("'", "", $code);
			$where.=" and location_code='$code'";
		}
		if(I('get.location_name'))
		{
			$name=I('get.location_name');
			$name = str_replace("'", "", $name);
			$where.=" and location_name like'%$name%'";
		}
		if(I('get.location_type'))
		{
			$type=I('get.location_type');
			$where.=" and location_type='$type'";
		}
		$count=$location->where($where)->count();
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
		 
		$list = $location->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('locationlist',$list);
		$this->assign('page',$show);
		$this->display();
	}
	
	//新增作业地点
	public function add()
	{
		$location=new \Common\Model\LocationModel();
		//获取一级作业地点列表
		$locationList=$location->getLocationList2();
		$this->assign('locationList',$locationList);
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.location_code'),"'"));
			//判断作业地点代码唯一性
			$res_c=$location->where("location_code='$code'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X作业地点代码不能重复，确保唯一！');
			}
			$data = array(
					'location_code'=>$code,
					'location_name'=>trim(I('post.location_name'),"'"),
			        'address'=>trim(I('post.address')),
			        'linkman'=>trim(I('post.linkman')),
			        'telephone'=>trim((I('post.telephone'))),
					'location_type'=>trim(I('post.location_type')),
					'pid'=>trim(I('post.pid')),
					'comment'=>trim(I('post.comment'))
			);
			if (!$location->create($data)){
				// 对data数据进行验证
				$this->error($location->getError());
			}else{
				// 验证通过 可以进行其他数据操作
				$res=$location->add($data);
				if ($res!==false)
				{
					$this->success('新增作业地点成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//修改作业地点
	public function edit($id)
	{
		$location=new \Common\Model\LocationModel();
		//获取作业地点信息
		$msg=$location->getLocationMsg($id);
		$this->assign('msg',$msg);
		//获取一级作业地点列表
		$locationList=$location->getLocationList2();
		$this->assign('locationList',$locationList);
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.location_code'),"'"));
			$res_c=$location->where("location_code='$code' and id!='$id'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X作业地点代码已被使用，不能重复，确保唯一！');
			}
			$data = array(
					'location_code'=>$code,
					'location_name'=>trim(I('post.location_name'),"'"),
			        'address'=>trim(I('post.address')),
			        'linkman'=>trim(I('post.linkman')),
			        'telephone'=>trim((I('post.telephone'))),
					'location_type'=>trim(I('post.location_type')),
					'pid'=>trim(I('post.pid')),
					'comment'=>trim(I('post.comment'))
			);
			if (!$location->create($data)){
				// 对data数据进行验证
				$this->error($location->getError());
			}else{
				// 验证通过 可以进行其他数据操作
				$res=$location->where("id=$id")->save($data);
				if ($res!==false)
				{
					$this->success('编辑作业地点成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
}
