<?php
/**
 * 起泊装箱业务类
 * 指令配箱管理类
 */
namespace Common\Model;
use Think\Model;

class QbzxInstructionCtnModel extends Model
{
	
	//验证规则
	protected $_validate = array(
			array('instruction_id','require','指令不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('instruction_id','is_positive_int','指令不存在',self::EXISTS_VALIDATE,'function'),//存在即验证，不能为空
			array('ctnno','require','箱号不能为空',self::EXISTS_VALIDATE),//存在即验证,不能为空
			array('ctnno','1,11','箱号长度不能超过11个字符',self::EXISTS_VALIDATE,'length'),//存在即验证，长度不能超过11个字符
			array('ctn_type_code','preg_match_chinese','箱型尺寸不能用中文',self::VALUE_VALIDATE,'function'),//值不为空即验证，不能使用中文
			array('ctn_type_code','1,10','箱型尺寸长度不能超过10个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 ，不能超过0个字符
			array('ctn_master','is_positive_int','箱主不存在',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为正整数
			array('status','number','箱状态必须为数字',self::VALUE_VALIDATE),//值不为空即验证，必须我数字
	);
	/**
	 * 获取指令下的已配箱数
	 * @param int $instruction_id:指令ID
	 * @return int|boolean
	 */
	public function hasContainerNum($instruction_id)
	{
		$num=$this->where("instruction_id=$instruction_id and status!='-1'")->count();
		if($num!==false)
		{
			return $num;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取指令下的配箱列表
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 */
	public function getContainerList($instruction_id)
	{
		$sql="select c.*,m.ctn_master as cmaster from __PREFIX__qbzx_instruction_ctn c,__PREFIX__container_master m where c.instruction_id=$instruction_id and c.ctn_master=m.id";
		$list=M()->query($sql);
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取配箱信息
	 * @param int $id:配箱ID
	 * @return array|boolean
	 */
	public function getContainerMsg($id)
	{
		$sql = "select c.*,cm.ctn_master,u.user_name from __PREFIX__user u,__PREFIX__qbzx_instruction_ctn c,__PREFIX__container_master cm where c.id='$id' and c.operator_id=u.uid and c.ctn_master=cm.id";
		$msg = M()->query($sql);
		if($msg!==false)
		{
			// 箱状态
			$ctn_status_d = json_decode ( ctn_status_d, true );
			$status_zh = $ctn_status_d [$msg [0] ['status']];
			$msg [0] ['status_zh'] = $status_zh;
			return $msg[0];
		}else {
			return false;
		}
	}
	
	
	/**
	 * 获取配箱信息
	 * @param int $id:配箱ID
	 * @return array|boolean
	 */
	public function getContainerMsga($id)
	{
		$sql = "select c.*,cm.ctn_master from __PREFIX__qbzx_instruction_ctn c,__PREFIX__container_master cm where c.id='$id' and c.ctn_master=cm.id";
		$msg = M()->query($sql);
		if($msg!==false)
		{
			// 箱状态
			$ctn_status_d = json_decode ( ctn_status_d, true );
			$status_zh = $ctn_status_d [$msg [0] ['status']];
			$msg [0] ['status_zh'] = $status_zh;
			return $msg[0];
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
		$operation = new \Common\Model\QbzxOperationModel();
		$res=$operation->where("ctn_id=$ctn_id")->field('empty_weight')->find();
		if($res['empty_weight'])
		{
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 判断配箱是否作业
	 * @param int $ctn_id:配箱ID
	 * @return boolean
	 */
	public function is_begina($ctn_id)
	{
		$operation = new \Common\Model\QbzxOperationModel();
		$res=$operation->where("ctn_id=$ctn_id")->find();
		if($res)
		{
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取指令下的配箱列表及配箱作业
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 */
	public function getCtnOperationList($instruction_id)
	{
		$sql="select c.*,m.ctn_master as cmaster from __PREFIX__qbzx_instruction_ctn c,__PREFIX__container_master m where c.instruction_id=$instruction_id and c.ctn_master=m.id";
		$list=M()->query($sql);
		if($list!==false)
		{
			foreach($list as $key => $vo)
			{
				$operation = new \Common\Model\QbzxOperationModel();
				$res = $operation->where("ctn_id = '".$vo['id']."'")->find();
				if($res !== false)
				{
					$list[$key]['operation_examine'] = $res['operation_examine'];
					$list[$key]['examine_remark']   = $res['examine_remark'];
				}else{
					$list[$key]['operation_examine']  = '';
					$list[$key]['examine_remark']     = ''; 
				}
			}
			return $list;
		}else {
			return false;
		}
	}
}
?>