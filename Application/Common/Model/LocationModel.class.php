<?php
/**
 * 基础类
 * 作业地点维护类
 */
namespace Common\Model;
use Think\Model;

class LocationModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('location_code','require','作业地点代码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('location_code','preg_match_chinese','作业地点代码不能使用中文！',self::EXISTS_VALIDATE,'function'),  //存在即验证，不准使用中文
			array('location_code','1,10','作业地点代码不超过10个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过10个字符
			array('location_name','require','作业地点名称不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('location_name','1,30','作业地点名称不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过30个字符
			array('address','1,60','详细地址不超过60个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，长度不超过60个字符
			array('linkman','1,20','联系人不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，长度不超过20个字符
			array('telephone','1,100','联系电话不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，长度不超过100个字符
			array('location_type',array(1,3),'作业地点类型不正确！',self::VALUE_VALIDATE,'between'),  //值不为空的时候验证 ，范围在1-3之间的数字
			array('pid','is_natural_num','请选择正确的父级作业地点！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须为自然数
			array('comment','1,200','备注不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过200个字符
	);
	
	/**
	 * 获取作业地点列表
	 * @param $type:作业地点类别 1港内 2港外常驻 3港外非常驻
	 * @return array
	 */
	public function getLocationList($type='')
	{
		if($type)
		{
			$where="location_type='$type'";
		}
		$cat=$this->where($where)->select();
		$locationList =$this->rule($cat);
		return $locationList;
	}
	
	/**
	 * 只获取一级作业地点
	 * @return array
	 */
	public function getLocationList2()
	{
		$locationList=$this->where("pid=0")->select();
		return $locationList;
	}
	
	/**
	 * 获取作业地点信息
	 * @param int $id:作业地点ID
	 * @return array 一条作业地点详情记录
	 */
	public function getLocationMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		return $msg;
	}
	
	/**
	 * 判断作业地点是否存在
	 * @param string $name:地点名称
	 * @return boolean
	 */
	public function is_exist($name)
	{
		$res=$this->where("location_name='$name'")->field('id')->find();
		if($res['id']!='')
		{
			return true;
		}else {
			return false;
		}
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
