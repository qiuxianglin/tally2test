<?php
/**
 * 门到门拆箱业务类
 * 指令管理
 */
namespace Common\Model;
use Think\Model;

class DdInstructionModel extends Model
{
	public $ERROR_CODE_COMMON =array();     // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();  // 公共返回码中文描述
	public $ERROR_CODE_USER =array();       // 用户管理返回码
	public $ERROR_CODE_USER_ZH =array();    // 用户管理返回码中文描述
	public $ERROR_CODE_SHIFT =array();       // 工班管理返回码
	public $ERROR_CODE_SHIFT_ZH =array();    // 工班管理返回码中文描述	
	
	//初始化
	protected function _initialize()
	{
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_USER = json_decode(error_code_user,true);
		$this->ERROR_CODE_USER_ZH = json_decode(error_code_user_zh,true);
		$this->ERROR_CODE_SHIFT = json_decode(error_code_shift,true);
		$this->ERROR_CODE_SHIFT_ZH = json_decode(error_code_shift_zh,true);
	}
	
	//验证规则
	protected $_validate = array(
			array('plan_id','require','所属预报计划不能为空',self::EXISTS_VALIDATE), //存在即验证，不能为空
			array('plan_id','is_positive_int','预报计划不存在',self::EXISTS_VALIDATE,'function'), //存在即验证，必须为正整数
			array('department_id','require','指令所属部门不能为空',self::EXISTS_VALIDATE), //存在即验证，不能为空
			array('department_id','is_positive_int','部门不存在',self::EXISTS_VALIDATE,'function'), //存在即验证，必须为正整数
			array('date','require','指令下达日期不能为空',self::EXISTS_VALIDATE), //存在即验证，不能为空
			array('date','is_date','指令下达日期不是日期格式',self::EXISTS_VALIDATE,'function'), //存在即验证，必须为日期格式
			array('status','require','指令状态不能为空',self::EXISTS_VALIDATE), //存在即验证，不能为空
			array('status',array(0,2),'指令必须在0-2范围内',self::EXISTS_VALIDATE,'between'), //存在即验证，0未派工 1已派工 2已完成
			array('is_must','require','请选择是否为重点客户',self::EXISTS_VALIDATE), //存在即验证，不能为空		
			array('is_must',array('Y','N'),'请选择是否为重点客户',self::EXISTS_VALIDATE,'in'),//存在即验证，只能是 Y是 N否	
	);
	
	/**
	 * 获取指令详情
	 * @param int $id:指令ID
	 * @return array
	 */
	public function getInstructionMsg($id)
	{
		$sql="select p.*,i.* from __PREFIX__dd_plan p,__PREFIX__dd_instruction i where p.id=i.plan_id and i.id=$id";
		$result=M()->query($sql);
		$msg=$result[0];
		if($msg!==false)
		{
			//查询指令对应部门组信息
			$department=new \Common\Model\DepartmentModel();
			$dmsg=$department->getDepartmentMsg($msg['department_id']);
			$msg['parent_department_name']=$dmsg['parent_department_name'];
			$msg['parent_department_code']=$dmsg['parent_department_code'];
			$msg['department_code']=$dmsg['department_code'];
			$msg['department_name']=$dmsg['department_name'];
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
	public function getInstructionListByWork($uid,$status)
	{
		//①判断用户身份是否合法
		$User=new \Common\Model\UserModel();
		$res_u=$User->is_valid($uid);
		if($res_u['code']!=0)
		{
			//用户不合法，返回码采用的和判断用户是否合法一致
			$res=$res_u;
		}else {
			//②用户合法，判断用户是否为理货长身份
			$res_u2=$User->is_chiefTally($uid);
			if($res_u2['code']!=0)
			{
				//用户不是理货长，不具备查看理货长功能
				//返回码采用的和判断用户是否为理货长一致
				$res=$res_u2;
			}else {
				//用户是理货长
				//查询用户签到班组的指令
				$msg_u=$User->where("uid=$uid")->field('shift_id')->find();
				if($msg_u['shift_id'])
				{
					//工班ID
					$shift_id=$msg_u['shift_id'];
					//根据工班ID查询所属部门ID
					$shift = new \Common\Model\ShiftModel();
					$msg_w=$shift->where("shift_id='$shift_id'")->field('department_id')->find();
					if($msg_w)
					{
						//工班所属部门ID
						$department_id=$msg_w['department_id'];
						//根据部门ID和状态查询指令列表
						$sql="select p.*,i.* from __PREFIX__dd_plan p,__PREFIX__dd_instruction i where p.id=i.plan_id and i.department_id=$department_id  and i.status='$status' order by i.id desc limit 0,50";
						$list=M()->query($sql);
						if($list!==false)
						{
							//获取指令的派工ID
							$nun=count($list);
							for ($i=0;$i<$nun;$i++)
							{
								//指令状态文字描述
								switch ($list[$i]['status'])
								{
									case '0':
										$list[$i]['status_zh']='未派工';
										break;
									case '1':
										$list[$i]['status_zh']='已派工';
										break;
									case '2':
										$list[$i]['status_zh']='已完成';
										break;
									default:
										$list[$i]['status_zh']='未派工';
										break;
								}
								//拆箱方式文字描述
								switch ($list[$i]['operating_type'])
								{
									case '0':
										$list[$i]['operating_type_zh']='人工';
										break;
									case '1':
										$list[$i]['operating_type_zh']='机械';
										break;
								}
								
								$instruction_id=$list[$i]['id'];
								$dispatch = new \Common\Model\DispatchModel();
								$res_o=$dispatch->where("instruction_id=$instruction_id and business='dd' and mark!='1'")->field('id')->find();
								$list[$i]['dispatch_id']=$res_o['id'];
							}
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'成功',
									'list'=>$list
							);
						}else {
							// 数据库操作错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}else {
						// 该工班不存在！
						$res=array(
								'code'=>$this->ERROR_CODE_SHIFT['SHIFT_NOT_EXIST'],
								'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['SHIFT_NOT_EXIST']]
						);
					}
				}else {
					// 该用户未签到
					$res=array(
							'code'=>$this->ERROR_CODE_USER['USER_NOT_SIGNIN'],
							'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_SIGNIN']]
					);
				}
			}
		}
		return $res;
	}
}