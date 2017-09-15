<?php
/**
 * 基础类
 * 部门管理类
 */
namespace Common\Model;
use Think\Model;

class DepartmentModel extends Model
{
	public $ERROR_CODE_COMMON =array();     // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();  // 公共返回码中文描述
	public $ERROR_CODE_DEPARTMENT =array();       // 部门管理返回码
	public $ERROR_CODE_DEPARTMENT_ZH =array();    // 部门管理返回码中文描述
	
	//初始化
	protected function _initialize()
	{
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_DEPARTMENT = json_decode(error_code_department,true);
		$this->ERROR_CODE_DEPARTMENT_ZH = json_decode(error_code_department_zh,true);
	}
	
	//验证规则
	protected $_validate = array(
			array('department_code','require','部门代码不能为空',self::EXISTS_VALIDATE),  //存在即验证，不能为空
			array('department_code','preg_match_chinese','部门代码不能使用中文',self::EXISTS_VALIDATE,'function'), //存在即验证，不准使用中文
			array('department_code','1,11','部门代码不超过11个字符',self::EXISTS_VALIDATE,'length'),//存在即验证，长度不能超过10个字符
			array('department_name','require','部门名称不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('department_name','1,20','部门名称不能超过20个字符',self::EXISTS_VALIDATE,'length'),//存在即验证，长度不能超过20个字符
			array('pid','is_natural_num','上级部门ID为自然数',self::VALUE_VALIDATE,'function'),//值不为空即验证，上级部门ID为自然数
	);
	
	/**
	 * 获取部门列表
	 * @return array
	 */
	public function getDepartmentList()
	{
		$cat=$this->select();
		$departmentList =$this->rule($cat);
		return $departmentList;
	}
	
	/**
	 * 获取一级部门列表
	 * @return array
	 */
	public function getTopDepartmentList()
	{
		$departmentList=$this->where("pid=0")->select();
		return $departmentList;
	}
	
	/**
	 * 获取部门信息
	 * @param int $id 部门ID
	 * @return array 一条部门详情记录，包含上级部门名称
	 */
	public function getDepartmentMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if(!empty($msg['pid']))
		{
			$pid=$msg['pid'];
			$parentDepartment=$this->where("id='$pid'")->field('department_code,department_name')->find();
			$msg['parent_department_name']=$parentDepartment['department_name'];
			$msg['parent_department_code']=$parentDepartment['department_code'];
		}else {
			$msg['parent_department_name']='';
			$msg['parent_department_code']='';
		}
		return $msg;
	}
	
	/**
	 * 根据上级部门ID获取子部门列表
	 * @param int $pid 上级部门ID
	 * @return array 子部门列表
	 */
	public function getChildDepartmentList($pid)
	{
		$childDepartmentList=$this->where("pid=$pid")->select();
		return $childDepartmentList;
	}
	
	/**
	 * 判断部门是否存在
	 * @param int $id:部门ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function is_exist($id)
	{
		$res=$this->where("id='$id'")->field('id')->find();
		if($res['id']=='')
		{
			$res=array(
					'code'=>$this->ERROR_CODE_DEPARTMENT['DEPARTMENT_NOT_EXIST'],
					'msg'=>$this->ERROR_CODE_DEPARTMENT_ZH[$this->ERROR_CODE_DEPARTMENT['DEPARTMENT_NOT_EXIST']]
			);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'存在该部门组!',
			);
		}
		return $res;
	}
	
	/**
	 * 判断二级部门是否存在
	 * @param int $id:部门ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function is_exist_subdepartment($id)
	{
		$res=$this->where("id='$id' and pid!=''")->field('id')->find();
		if($res['id']=='')
		{
			$res=array(
					'code'=>$this->ERROR_CODE_DEPARTMENT['DEPARTMENT_NOT_EXIST'],
					'msg'=>$this->ERROR_CODE_DEPARTMENT_ZH[$this->ERROR_CODE_DEPARTMENT['DEPARTMENT_NOT_EXIST']]
			);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'存在该部门组!',
			);
		}
		return $res;
	}
	
	static public function rule($cate , $lefthtml = '— ' , $pid=0 , $lvl=0, $leftpin=0 )
	{
		$arr=array();
		foreach ($cate as $v)
		{
			if($v['pid']==$pid)
			{
				$v['lvl']=$lvl + 1;
				$v['leftpin']=$leftpin + 0;//左边距
				$v['lefthtml']=str_repeat($lefthtml,$lvl);
				$arr[]=$v;
				$arr= array_merge($arr,self::rule($cate,$lefthtml,$v['id'],$lvl+1 , $leftpin+20));
			}
		}
		return $arr;
	}
}
?>
