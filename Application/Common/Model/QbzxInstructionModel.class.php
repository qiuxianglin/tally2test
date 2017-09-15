<?php
/**
 * 起泊装箱业务类
 * 指令管理类
 */
namespace Common\Model;
use Think\Model;

class QbzxInstructionModel extends Model
{	
	public $ERROR_CODE_COMMON =array();     // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();  // 公共返回码中文描述
	public $ERROR_CODE_SHIFT =array();       // 工班管理返回码
	public $ERROR_CODE_SHIFT_ZH =array();    // 工班管理返回码中文描述
	public $ERROR_CODE_USER =array();       // 用户管理返回码
	public $ERROR_CODE_USER_ZH =array();    // 用户管理返回码中文描述
	
	//初始化
	protected function _initialize()
	{
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_SHIFT = json_decode(error_code_shift,true);
		$this->ERROR_CODE_SHIFT_ZH = json_decode(error_code_shift_zh,true);
		$this->ERROR_CODE_USER = json_decode(error_code_user,true);
		$this->ERROR_CODE_USER_ZH = json_decode(error_code_user_zh,true);
	}
	
	//验证规则
	protected $_validate = array(
		array('plan_id','require','预报计划不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('plan_id','is_positive_int','预报计划不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
		array('department_id','require','部门不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('department_id','is_positive_int','部门不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
		array('location_id','require','作业地点不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('location_id','is_positive_int','作业地点不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
		array('loadingtype',array('0','1'),'请选择装箱方式',self::VALUE_VALIDATE,'in'),//值不为空即验证 0人工 1机械
		array('ordertime','require','指令下达日期不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('ordertime','is_date','指令下达日期必须为日期格式',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为日期格式
		array('status','require','指令状态不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('status',array(0,2),'指令必须在0-2范围内',self::EXISTS_VALIDATE,'between'),//存在即验证 0.未派工1.已派工2。已完成
	);
	/**
	 * 获取预报计划下的指令列表
	 * @param int $plan_id:预报计划ID
	 * @return array|boolean
	 */
	public function getInstructionList($plan_id)
	{
		$sql="select i.*,l.location_name from __PREFIX__qbzx_instruction i,__PREFIX__location l where i.plan_id=$plan_id and i.location_id=l.id";
		$list=M()->query($sql);
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取指令详情
	 * @param int $id:指令ID
	 * @return array|boolean
	 */
	public function getInstructionMsg($id)
	{
		$sql="select i.*,l.location_name,s.ship_name,c.customer_name,p.*,i.id iid from __PREFIX__qbzx_instruction i,__PREFIX__location l,__PREFIX__qbzx_plan p,__PREFIX__ship s,__PREFIX__customer c where i.id='$id' and i.location_id=l.id and s.id=p.ship_id and p.id=i.plan_id and c.id=p.entrust_company";
		$res=M()->query($sql);
		if($res!==false)
		{
			//已配箱数
			$sql = "select count(c.id) as ctn_num from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_instruction_ctn c where i.id='$id' and c.instruction_id=i.id and c.status!=3";
			$res_i = M ()->query ( $sql );
			if($res_i [0] ['ctn_num'] != '')
			{
				$res [0] ['has_container_num'] = $res_i [0] ['ctn_num'];
			}else{
				$res [0] ['has_container_num'] = '0';
			}
			
			// 指令状态
			$instruction_status_d = json_decode ( instruction_status_d, true );
			$status_zh = $instruction_status_d [$res[0]['status']];
			$res [0] ['status_zh'] = $status_zh;
			if($res[0]['loadingtype'] == '0')
			{
				$res[0]['loadingtype_zh'] == '人工';
			}else{
				$res[0]['loadingtype_zh'] == '机械';
			}
			return $res[0];
		}else {
			return false;
		}
	}
	
	
	/**
	 * 获取理货长所在部门的指令列表
	 * @param int $uid:用户ID
	 * @param string $status:指令状态 0.未派工 1.已派工 2.已完成
	 * @return array
	 * @return code:返回码
	 * @return msg:返回码说明
	 * @return list:指令列表
	 */
	public function getInstructionListByWork($uid)
	{
		// ①判断用户身份是否合法
		$user = new \Common\Model\UserModel ();
		$res_u = $user->is_valid ( $uid );
		if ($res_u ['code'] != 0)
		{
			// 用户不合法，返回码采用的和判断用户是否合法一致
			$res = $res_u;
		} else {
			// ②用户合法，判断用户是否为理货长身份
			$res_u2 = $user->is_chiefTally ( $uid );
			if ($res_u2 ['code'] != 0)
			{
				// 用户不是理货长，不具备查看理货长功能
				// 返回码采用的和判断用户是否为理货长一致
				$res = $res_u2;
			} else {
				// 用户是理货长
				// 查询用户签到班组的指令
				$msg_u = $user->where ( "uid=$uid" )->field ( 'shift_id' )->find ();
				if ($msg_u ['shift_id'])
				{
					// 工班ID
					$shift_id = $msg_u ['shift_id'];
					// 根据工班ID查询所属部门ID
					$shift = new \Common\Model\ShiftModel();
					$msg_w = $shift->where ( "shift_id='$shift_id'" )->field ( 'department_id' )->find ();
					if ($msg_w)
					{
						// 工班所属部门ID
						$department_id = $msg_w ['department_id'];
						// 根据部门ID和状态查询指令列表
						$sql = "select i.*,s.id as ship_id,s.ship_name,p.voyage,p.total_ctn,l.location_name,d.department_name from __PREFIX__qbzx_instruction i,__PREFIX__ship s,__PREFIX__qbzx_plan p,__PREFIX__location l,__PREFIX__department d  where i.department_id='$department_id' and i.status in (0,1) and p.ship_id=s.id and i.plan_id=p.id and i.location_id=l.id and i.department_id=d.id order by i.id desc limit 0,50";
						$list = M ()->query ( $sql );
						if ($list !== false)
						{
							// 获取指令的派工ID
							$nun = count ( $list );
							for($i = 0; $i < $nun; $i ++) 
							{
								// 指令状态
								$instruction_status_d = json_decode ( instruction_status_d, true );
								$status_zh = $instruction_status_d [$list [$i] ['status']];
								$list [$i] ['status_zh'] = $status_zh;
								
								$instruction_id = $list [$i] ['id'];
								//已配箱数
								$sql = "select count(c.id) as ctn_num from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_instruction_ctn c where i.id='$instruction_id' and c.instruction_id=i.id and c.status!=3";
								$res_i = M ()->query ( $sql );
								if($res_i [0] ['ctn_num'] != '')
								{
									$list [$i] ['has_container_num'] = $res_i [0] ['ctn_num'];
								}else{
									$list [$i] ['has_container_num'] = '0';
								}
								$dispatch = new \Common\Model\DispatchModel ();
								$res_o = $dispatch->where ( "instruction_id=$instruction_id and business='qbzx' and mark!='1'" )->field ( 'id' )->order ( 'id desc' )->find ();
								$list [$i] ['repair_id'] = $res_o ['id'];
								if ($list [$i] ['loadingtype'] == '0') 
								{
									$list [$i] ['loadingtype_zh'] = '人工';
								} else {
									$list [$i] ['loadingtype_zh'] = '机械';
								}
							}
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
									'msg' => '成功',
									'list' => $list
							);
						} else {
						$res = array (
							'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					} else {
						// 工班不存在
						$res=array(
								'code'=>$this->ERROR_CODE_SHIFT['SHIFT_NOT_EXIST'],
								'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['SHIFT_NOT_EXIST']]
						);
					}
				} else {
					$res = array (
							'code' => $this->ERROR_CODE_USER['USER_NOT_SIGNIN'],
							'msg' => $this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_SIGNIN']]
					);
				}
			}
		}
		return $res;
	}
}
?>