<?php
/**
 * 部门管理
 * 2016-11-22
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class DepartmentController extends AuthController
{
	public function index()
	{
		//获取部门列表
		$department = new \Common\Model\DepartmentModel();
		$where="1";
		if(I('get.code'))
		{
			$code=I('get.code');
			$code = str_replace("'", "", $code);
			$where.=" and department_code='$code'";
		}
		if(I('get.name'))
		{
			$name=I('get.name');
			$name = str_replace("'", "", $name);
			$where.=" and department_name like'%$name%'";
		}
		$count=$department->where($where)->count();
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
			
		$list = $department->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('dList',$list);
		$this->assign('page',$show);
		$this->display();
	}
	
	//新增部门
	public function add()
	{
		$department = new \Common\Model\DepartmentModel();
		//获取一级部门列表
		$deptList=$department->getTopDepartmentList();
		$this->assign('deptList',$deptList);
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.department_code'),"'"));
			//判断部门代码唯一性
			$res_c=$department->where("department_code='$code'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X部门代码不能重复，确保唯一！');
			}
			$data = array(
					'department_code'=>$code,
					'department_name'=>trim(I('post.department_name'),"'"),
					'pid'=>trim(I('post.pid'))
			);
			if(!$department->create($data))
			{
				//对data数据进行验证
				$this->error($department->getError());
			}else{
				//验证通过 可以对数据进行处理
				$res=$department->add($data);
				if ($res!==false)
				{
					$this->success('新增部门成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//编辑部门
	public function edit($id)
	{
		$department = new \Common\Model\DepartmentModel();
		//获取一级部门列表
		$deptList=$department->getTopDepartmentList();
		$this->assign('deptList',$deptList);
		//获取部门信息
		$msg=$department->getDepartmentMsg($id);
		$this->assign('msg',$msg);
		if(I('post.'))
		{
			layout(false);
			$code=strtoupper(trim(I('post.department_code'),"'"));
			//判断部门代码唯一性
			$res_c=$department->where("department_code='$code' and id!='$id'")->field('id')->find();
			if($res_c['id']!='')
			{
				$this->error('X部门代码已被使用，不能重复，确保唯一！');
			}
			$data = array(
					'department_code'=>$code,
					'department_name'=>trim(I('post.department_name'),"'"),
					'pid'=>trim(I('post.pid'))
			);
			if(!$department->create($data))
			{
				//对data数据进行验证
				$this->error($department->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$department->where("id='$id'")->save($data);
				if ($res!==false)
				{
					$this->success('编辑部门成功！',U('index'));
				}else {
					$this->error('X操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
}
