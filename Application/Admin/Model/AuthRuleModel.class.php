<?php
namespace Admin\Model;
use Think\Model;

class AuthRuleModel extends Model
{
	protected $trueTableName = 'tally_auth_rule';
	/* 
	 * 获取权限列表
	 *  */
	public function getRuleList()
	{
		$rule=$this->order('sort desc,id asc')->select();
		$rule_arr =$this->rule($rule);
		return $rule_arr;
	}
	
	/* 
	 * 根据id获取认证规则信息
	 * 成功返回规则记录，失败返回0
	 *  */
	public function getRuleMsg($id)
	{
		if(!empty($id))
		{
			$where=array(
				'id'=>$id
			);
			$res=$this->where($where)->find();
			if($res)
			{
				return $res;
			}else {
				return 0;
			}
		}else {
			return 0;
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