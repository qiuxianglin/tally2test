<?php
/**
 * 门到门拆箱业务类
 * 预报计划-配箱管理
 */
namespace Common\Model;
use Think\Model;

class DdPlanContainerModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('status',array('-1','0','1','2'),'配箱状态错误',self::EXISTS_VALIDATE,'in'),//存在即验证，只能是 0未开始 1工作中 2已完成 -1箱残损
			array('operator_id','is_positive_int','该理货员不存在',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为正整数
	);
	
	/**
	 * 获取预报计划下的配箱列表
	 * @param int $plan_id:预报计划ID
	 * @return array|boolean
	 */
	public function getContainerList($plan_id)
	{
		$list=$this->where("plan_id='$plan_id'")->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				if($list[$i]['flflag']=='F')
				{
					$list[$i]['flflag_zh']='整箱';
				}elseif($list[$i]['flflag']=='L') {
					$list[$i]['flflag_zh']='拼箱';
				}else {
					$list[$i]['flflag_zh']='整箱';
				}
				//获取想审核状态
				$operation = new \Common\Model\DdOperationModel();
				$res = $operation->where("ctn_id = '".$list[$i]['id']."'")->find();
				if($res !== false)
				{
					$list[$i]['operation_examine'] = $res['operation_examine'];
					$list[$i]['examine_remark']   = $res['examine_remark'];
				}else{
					$list[$i]['operation_examine']  = '';
					$list[$i]['examine_remark']     = '';
				}
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取配箱信息
	 * @param int $id:箱ID
	 * @return array|boolean
	 */
	public function getContainerMsg($id)
	{
		$sql="select c.*,i.is_must,i.id instruction_id from __PREFIX__dd_plan_container c,__PREFIX__dd_instruction i where c.id=$id and c.plan_id=i.plan_id";
		$res=M()->query($sql);
		if($res!==false)
		{
			return $res[0];
		}else {
			return false;
		}
	}
	
	/**
	 * 判断配箱是否已经作业
	 * @param int $ctn_id:配箱ID
	 * @return boolean
	 */
	public function is_begin($ctn_id)
	{
		$Operation=new \Common\Model\DdOperationModel();
		$res=$Operation->where("ctn_id=$ctn_id")->find();
		if($res['step'] == '0')
		{
			return false;
		}else {
			return true;
		}
	}
}