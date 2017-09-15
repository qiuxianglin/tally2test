<?php
/**
 * CFS装箱业务类
 * 指令配箱管理类
 */
namespace Common\Model;
use Think\Model;

class CfsInstructionCtnModel extends Model
{
	//验证规则
	protected $_validate = array(
		array('ctnno','require','箱号不能为空',self::EXISTS_VALIDATE),//存在即验证  不能为空
		array('ctnno','1,11','箱号长度不能超过11个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过11个字符
		array('ctn_size','1,10','箱型尺寸长度不能超过10个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过10个字符	
		array('ctn_master','is_natural_num','箱主不存在',self::VALUE_VALIDATE,'function'),//值不为空即验证 必须为自然数
		array('lcl','require','拼箱状态不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('lcl',array('F','L'),'请选择是否拼箱',self::EXISTS_VALIDATE,'in'),//存在即验证 F整箱 L拼箱
		array('pre_number','is_natural_num','预配件数必须为自然数',self::VALUE_VALIDATE,'function'),//值不为空即验证 必须为自然数
		array('status','require','配箱状态不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('status',array('-1','0','1','2'),'配箱状态不存在',self::EXISTS_VALIDATE,'in'),//存在即验证 0未开始 1工作中 2 已完成 -1箱残损
	    array('operator_id','is_natural_num','不存在此操作人',self::VALUE_VALIDATE,'function'),//值不为空即验证 必须为自然数
		array('instruction_id','require','指令不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('instruction_id','is_positive_int','指令不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
	);
	/**
	 * 获取指令下的配箱列表
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 */
	public function getContainerList($instruction_id)
	{
		$sql="select c.*,m.ctn_master as cmaster from __PREFIX__cfs_instruction_ctn c,__PREFIX__container_master m where c.instruction_id='$instruction_id' and c.ctn_master=m.id order by c.ctnno desc";
		$list=M()->query($sql);
		if($list!==false)
		{
			foreach($list as $key => $vo)
			{
				$operation = new \Common\Model\CfsOperationModel();
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
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取配箱信息（未作业）
	 * @param int $id:配箱ID
	 * @return array|boolean
	 */
	public function getContainerMsga($id)
	{
		$sql="select c.*,m.ctn_master as cmaster from __PREFIX__cfs_instruction_ctn c,__PREFIX__container_master m where c.id='$id' and c.ctn_master=m.id";
		$msg=M()->query($sql);
		if($msg!==false)
		{
		    $lcl = $msg[0]['lcl'];
			if($lcl == 'N')
			{
				$msg[0]['lclo'] = '整箱';
			}else{
				$msg[0]['lclo'] = '拼箱';
			}
			return $msg[0];
		}else {
			return false;
		}
	}
	
	/**
	 * 获取配箱信息（已作业）
	 * @param int $id:配箱ID
	 * @return array|boolean
	 */
	public function getContainerMsg($ctn_id)
	{
		$sql="select c.*,m.ctn_master as cmaster,o.cargo_weight,o.sealno from __PREFIX__cfs_instruction_ctn c,__PREFIX__container_master m,__PREFIX__cfs_operation o where c.id='$ctn_id' and c.ctn_master=m.id and o.ctn_id=c.id";
		$msg=M()->query($sql);
		if($msg!==false)
		{
			$lcl = $msg[0]['lcl'];
			if($lcl == 'N')
			{
				$msg[0]['lclo'] = '整箱';
			}else{
				$msg[0]['lclo'] = '拼箱';
			}
			return $msg;
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
		$operation = new \Common\Model\CfsOperationModel();
		$res=$operation->where("ctn_id=$ctn_id")->field('empty_weight')->find();
		if($res['empty_weight'])
		{
			return true;
		}else {
			return false;
		}
	}
}

?>