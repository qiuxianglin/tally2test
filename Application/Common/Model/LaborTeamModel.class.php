<?php
/**
 * 基础类
 * 货代信息维护类
 */
namespace Common\Model;
use Think\Model;

class LaborTeamModel extends Model
{
	protected $tableName = 'laborteam';
	
	/**
	 * 获取劳务队列表
	 * @return array
	 */
	public function getLaborTeamList()
	{
		$LaborTeamList = $this->field('id,labor_name')->select();
		return $LaborTeamList;
	}
	
	/**
	 * 获取劳务队详细信息
	 * @param int $id:劳务队ID
	 * @return array
	 */
	public function getLaborteamMsg()
	{
		$msg=$this->where("id='$id'")->find();
		return $msg;
	}
}