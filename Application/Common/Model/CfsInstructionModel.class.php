<?php
/**
 * CFS装箱业务类
 * 指令管理类
 */
namespace Common\Model;
use Think\Model;

class CfsInstructionModel extends Model
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
			array('ship_id','require','船不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('ship_id','is_positive_int','船不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('voyage','require','航次不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('voyage','1,17','航次长度不能超过17个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过17个字符
			array('operation_type','require','装箱方式不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('operation_type',array('0','1'),'请选择装箱方式',self::EXISTS_VALIDATE,'in'),//存在即验证 0人工 1机械
			array('location_id','require','作业场地不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('location_id','is_positive_int','作业场地不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('department_id','require','所属部门不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('department_id','is_positive_int','所属部门不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('status','require','指令状态不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('status',array('0','1','2'),'请选择指令状态',self::EXISTS_VALIDATE,'in'),//存在即验证 0未派工 1已派工2 已完成
			array('date','require','指令下达日期不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('date','is_date','指令下达日期必须为日期格式',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为日期格式
	);
	/**
	 * 获取指令列表
	 * @return array|boolean
	 */
	public function getInstructionList()
	{
	    $list = $this->select();
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
		$sql = "select i.*,s.ship_name,l.location_name from __PREFIX__ship s,__PREFIX__cfs_instruction i,__PREFIX__location l where i.id ='$id' and  i.location_id=l.id and i.ship_id=s.id";
		$res = M()->query($sql);
		if($res!==false)
		{
			$department_id = $res[0]['department_id'];
			$department = new \Common\Model\DepartmentModel();
			$departmentmsg = $department->getDepartmentMsg($department_id);
			$parent_department_name = $departmentmsg['parent_department_name'];
			$departmentname = $departmentmsg['department_name'];
			//装箱方式文字描述
			if($res[0]['operation_type'] == '1')
			{
				$operation_type_zh = '机械';
			}else{
				$operation_type_zh = '人工';
			}
			//指令状态文字描述
			$instruction_status_d=json_decode(instruction_status_d,true);
			$status_zh=$instruction_status_d[$res[0]['status']];
			$msg=$res[0];
			$msg['status_zh'] = $status_zh;
			$msg['operation_type_zh']= $operation_type_zh;
			$msg['department'] = $parent_department_name.'-'.$departmentname;
			//获取委托单位中文名
			$customer = new \Common\Model\CustomerModel();
			$customer_id = $res[0]['entrust_company'];
			$res_c = $customer->where("id='$customer_id'")->field('id,customer_name')->find();
			if($res_c['id']=='')
			{
				$msg['entrust_company_zh'] = '';
			}else{
				$msg['entrust_company_zh'] = $res_c['customer_name'];
			}
			return $msg;
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
	public function getInstructionListByWork($uid, $status) 
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
						$sql = "select i.*,s.ship_name,l.location_name,d.department_name from __PREFIX__cfs_instruction i,__PREFIX__ship s,__PREFIX__location l,__PREFIX__department d  where i.department_id='$department_id' and i.ship_id=s.id and i.location_id=l.id and i.department_id=d.id and i.status='$status' order by id desc limit 0,50";
						$list = M ()->query ( $sql );
						if ($list !== false) 
						{
							// 获取指令的派工ID
							$nun = count ( $list );
							for($i = 0; $i < $nun; $i ++) 
							{
								$instruction_id = $list [$i] ['id'];
								$dispatch =new \Common\Model\DispatchModel();
								$res_o = $dispatch->where ( "instruction_id=$instruction_id and business='cfs' and mark!='1'" )->field ( 'id' )->find ();
								$list [$i] ['dispatch_id'] = $res_o ['id'];
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
					//该用户未签到
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

