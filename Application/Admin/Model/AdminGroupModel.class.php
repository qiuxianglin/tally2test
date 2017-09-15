<?php
namespace Admin\Model;
use Think\Model;

class AdminGroupModel extends Model
{
	protected $trueTableName = 'tally_admin_group';
	/* 
	 * 获取管理员组列表
	 * 不含超级管理员组
	 *  */
	public function getGroupList()
	{
		$grouplist=$this->where('id!=1')->select();
		return $grouplist;
	}
	
	/*
	 * 获取管理员组列表
	 * 包含超级管理员组
	 *  */
	public function getGroupList2()
	{
		$grouplist=$this->select();
		return $grouplist;
	}
	
	/* 
	 * 根据ID获取管理员组信息
	 *  */
	public function getGroupMsg($id)
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
}