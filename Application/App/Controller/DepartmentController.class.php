<?php
/**
 * APP接口
 * 部门管理接口
 */
namespace App\Controller;
use App\Common\BaseController;

class DepartmentController extends BaseController
{
	/**
	 * 获取一级部门列表
	 * @return 一级部门列表
	 */
	public function getTopDepartmentList()
	{
		$department=new \Common\Model\DepartmentModel();
		$list=$department->getTopDepartmentList();
		if($list!==false)
		{
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$list
			);
		}else {
			//数据库操作错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list'=>''
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 根据上级部门获取子部门列表
	 * @param int $pid:上级部门ID
	 * @return array 子部门列表
	 */
	public function getChildDepartmentList()
	{
		if(I('post.pid'))
		{
			$pid=I('post.pid');
			$department=new \Common\Model\DepartmentModel();
			$childlist=$department->getChildDepartmentList($pid);
			if($childlist!==false)
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'childlist'=>$childlist
				);
			}else {
				//数据库操作错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'childlist'=>''
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取部门信息
	 * @param int $id:部门ID
	 * @return array 一条部门详情记录，包含上级部门名称
	 */
	public function getDepartmentMsg()
	{
		if(I('post.id'))
		{
			$id=I('post.id');
			$department=new \Common\Model\DepartmentModel();
			$department_msg=$department->getDepartmentMsg($id);
			if($department_msg!==false)
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'content'=>$department_msg
				);
			}else {
				//数据库操作错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
					'content'=>''
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>