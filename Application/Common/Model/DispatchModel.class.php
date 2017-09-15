<?php
/**
 * 公用业务类
 * 派工管理类
 */
namespace Common\Model;
use Think\Model;

class DispatchModel extends Model
{
	public $ERROR_CODE_COMMON =array();     // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();  // 公共返回码中文描述
	public $ERROR_CODE_SHIFT =array();       // 工班管理返回码
	public $ERROR_CODE_SHIFT_ZH =array();    // 工班管理返回码中文描述
	public $ERROR_CODE_INSTRUCTION =array();       // 指令管理返回码
	public $ERROR_CODE_INSTRUCTION_ZH =array();    // 指令管理返回码中文描述
	
	//初始化
	protected function _initialize()
	{
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_SHIFT = json_decode(error_code_shift,true);
		$this->ERROR_CODE_SHIFT_ZH = json_decode(error_code_shift_zh,true);
		$this->ERROR_CODE_INSTRUCTION = json_decode(error_code_instruction,true);
		$this->ERROR_CODE_INSTRUCTION_ZH = json_decode(error_code_instruction_zh,true);
	}
	
	//验证规则
	protected $_validate = array(
				
	);
	
	/**
	 * 判断用户是否有权限进行派工
	 * @param int $uid:用户ID
	 * @param int $instruction_id:指令ID
	 * @param string $business:业务类型 qbzx：起泊装箱，dd：门到门拆箱，cfs：CFS拆箱
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param gand_id:工班ID
	 * @return @param department_id:部门ID
	 * @return @param workerlist:签入同一工班的人员列表
	 */
	public function isPermissionsForDispatching($uid,$instruction_id,$business='qbzx')
	{
		//①判断用户是否合法
		$user=new \Common\Model\UserModel();
		$res_u1=$user->is_valid($uid);
		if($res_u1['code']!=0)
		{
			//用户不合法，返回码采用的和判断用户是否合法的一致
			$res=$res_u1;
		}else {
			//②用户合法，判断用户是否签到
			$res_u2=$user->is_sign($uid);
			if($res_u2['code']!=0)
			{
				//用户未签到，返回码采用的和判断用户是否签到一致
				$res=$res_u2;
				return $res;
			}
			//③用户合法且已签到，判断用户是否为理货长身份
			$res_u3=$user->is_chiefTally($uid);
			if($res_u3['code']!=0)
			{
				//用户不是理货长身份，返回码采用的和判断用户是否为理货长身份一致
				$res=$res_u3;
			}else {
				//④判断指令所属部门的最新班次的理货长是否是其它理货长
				switch ($business)
				{
					case 'qbzx':
						//根据指令ID取出所属部门ID
						$instruction=new \Common\Model\QbzxInstructionModel();
						$res_d=$instruction->getInstructionMsg($instruction_id);
						if($res_d)
						{
							$department_id=$res_d['department_id'];
						}
						break;
					case 'dd':
						//根据指令ID取出所属部门ID
						$instruction=new \Common\Model\DdInstructionModel();
						$res_d=$instruction->getInstructionMsg($instruction_id);
						if($res_d)
						{
							$department_id=$res_d['department_id'];
						}
						break;
					case 'cfs' :
						// 根据指令ID取出所属部门ID
						$instruction = new \Common\Model\CfsInstructionModel ();
						$res_d = $instruction->getInstructionMsg ( $instruction_id );
						if ($res_d) {
							$department_id = $res_d ['department_id'];
						}
						break;
					default:break;
				}
				if($department_id!='')
				{
					//判断部门的最新班次的理货长是否是其它理货长
					$shift=new \Common\Model\ShiftModel();
					$res_g=$shift->where("department_id='$department_id'")->order('shift_id desc')->find();
					if($res_g)
					{
						if($res_g['shift_master']=='')
						{
							// 该工班尚无理货长
							$res=array(
									'code'=>$this->ERROR_CODE_SHIFT['SHIFT_NEED_CHIEFTALLY'],
									'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['SHIFT_NEED_CHIEFTALLY']]
							);
							return $res;
						}
						if($res_g['shift_master']!='' and $res_g['shift_master']!=$uid)
						{
							// 该用户不是当前工班理货长
							$res=array(
									'code'=>$this->ERROR_CODE_SHIFT['NOT_ONDUTY_CHIEFTALLY'],
									'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['NOT_ONDUTY_CHIEFTALLY']]
							);
						}else {
							//⑤判断部门的最新班次是否已交班
							if($res_g['mark']=='1')
							{
								// 该工班已交班
								$res=array(
										'code'=>$this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED'],
										'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED']]
								);
							}else {
								//该工班当班理货长为用户自己
								//⑥判断用户签到的班次和部门的最新班次是否相同
								$msg=$user->where("uid=$uid")->field('shift_id')->find();
								if($msg['shift_id']!=$res_g['shift_id'])
								{
									$res=array(
											'code'=>$this->ERROR_CODE_SHIFT['NOT_LAST_SHIFT'],
											'msg'=>'用户所在班次和部门最新工班不同，无法派工！'
									);
								}else {
									//获取签入同一工班的人员列表，进行派工
									$workerlist=$user->getWorkerList($res_g['shift_id']);
									$res=array(
											'code'=>0,
											'msg'=>'有权限派工！',
											'shift_id'=>$res_g['shift_id'],
											'department_id'=>$department_id,
											'workerlist'=>$workerlist
									);
								}
							}
						}
					}else {
						// 工班不存在
						$res=array(
								'code'=>$this->ERROR_CODE_SHIFT['SHIFT_NOT_EXIST'],
								'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['SHIFT_NOT_EXIST']]
						);
					}
				}else {
					// 指令不存在
					$res=array(
							'code'=>$this->ERROR_CODE_INSTRUCTION['INSTRUCTION_NOT_EXIST'],
							'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['INSTRUCTION_NOT_EXIST']]
					);
				}
			}
		}
		return $res;
	}
	
	/**
	 * 新增派工
	 * @param int $chiefTally:当班理货长用户ID
	 * @param string $shift_id:工班ID
	 * @param int $instruction_id:指令ID
	 * @param string $business:业务类型
	 * @param array $workerlist:派工人员ID列表
	 * @return boolean
	 */
	public function addTask($chiefTally,$shift_id,$instruction_id,$business,$workerlist)
	{
		$data=array(
				'chieftally'=>$chiefTally,
				'shift_id'=>$shift_id,
				'instruction_id'=>$instruction_id,
				'business'=>$business,
				'dispatch_time'=>date('Y-m-d H:i:s'),
		);
		$dispatch_id=$this->add($data);
		if($dispatch_id>0)
		{
			$DispatchDetail=new \Common\Model\DispatchDetailModel();
			//新增派工详情
			foreach ($workerlist as $k=>$v)
			{
				$data2[]=array(
						'dispatch_id'=>$dispatch_id,
						'clerk_id'=>$v,
						'dispatch_time'=>date('Y-m-d H:i:s'),
				);
			}
			$res=$DispatchDetail->addAll($data2);
			if($res!==false)
			{
				//修改工班的当班理货长为用户
				$data_g=array(
						'shift_master'=>$chiefTally
				);
				$shift=new \Common\Model\ShiftModel();
				$shift->where("shift_id='$shift_id'")->save($data_g);
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
	
	/**
	 * 修改派工
	 * @param int $dispatch_id:派工ID
	 * @param array $workerlist:派工人员列表
	 * @return boolean
	 */
	public function editTask($dispatch_id,$workerlist)
	{
		$DispatchDetail=new \Common\Model\DispatchDetailModel();
		//删除原有派工记录
		$res=$DispatchDetail->where("dispatch_id='$dispatch_id'")->delete();
		if($res!==false)
		{
			//保存新的派工记录
			foreach ($workerlist as $k=>$v)
			{
				$data2[]=array(
						'dispatch_id'=>$dispatch_id,
						'clerk_id'=>$v,
						'dispatch_time'=>date('Y-m-d H:i:s'),
				);
			}
			$res2=$DispatchDetail->addAll($data2);
			if($res2)
			{
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
	
	/**
	 * 取消派工
	 * @param int $dispatch_id:派工ID
	 * @return boolean
	 */
	public function cancel($dispatch_id)
	{
		//删除派工指令
		$res=$this->where("id='$dispatch_id'")->delete();
		if($res!==false)
		{
			//删除派工记录
			$DispatchDetail=new \Common\Model\DispatchDetailModel();
			$res2=$DispatchDetail->where("dispatch_id='$dispatch_id'")->delete();
		    if($res2!==false)
			{
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
	
	/**
	 * 获取派工详情
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 */
	public function getDetail($instruction_id,$business)
	{
		$msg=$this->where("instruction_id='$instruction_id' and business='$business' and mark!='1'")->order('id desc')->find();
		if($msg)
		{
			//理货长信息
			$chieftally=$msg['chieftally'];
			$user=new \Common\Model\UserModel();
			$res_m=$user->where("uid='$chieftally'")->find();
			$msg['chieftally_name']=$res_m['user_name'];
			$dispatch_id=$msg['id'];
			$sql="select d.*,u.uid,u.staffno,u.user_name from __PREFIX__dispatch_detail d,__PREFIX__user u where d.dispatch_id='$dispatch_id' and d.clerk_id=u.uid";
			$list=M()->query($sql);
			$msg['detail']=$list;
			return $msg;
		}else {
			return false;
		}
	}
}