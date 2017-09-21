<?php
/**
 * 基础类
 * 用户管理类
 */
namespace Common\Model;
use Think\Model;

class UserModel extends Model
{
	public $ERROR_CODE_COMMON =array();     // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();  // 公共返回码中文描述
	public $ERROR_CODE_USER =array();       // 用户相关返回码
	public $ERROR_CODE_USER_ZH =array();    // 用户相关返回码中文描述
	public $ERROR_CODE_SHIFT = array();
	public $ERROR_CODE_SHIFT_ZH = array();
	
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
		array('staffno','require','工号不能为空',self::EXISTS_VALIDATE),//存在即验证,不能为空
		array('staffno','preg_match_chinese','工号不能使用中文',self::EXISTS_VALIDATE,'function'),//存在即验证，不能使用中文
		array('staffno','1,10','工号长度不能超过10个字符',self::EXISTS_VALIDATE,'length'),//存在即验证，长度不能超过10个字符
		array('user_name','require','用户名不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
		array('user_name','1,20','用户名长度不能超过20个字符',self::EXISTS_VALIDATE,'length'),//值不为空即验证，长度不能超过20个字符
		array('user_pwd','require','用户密码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
		array('user_pwd','32','用户密码无效！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度为32个字符
		array('department_id','is_positive_int','请选择正确的部门组',self::VALUE_VALIDATE,'function')	,//值不为空验证,必须为自然数
		array('position','1,20','职务不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证，长度不能超过20个字符
		array('group_id','is_positive_int','请选择正确的用户组',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为正整数
		array('last_logintime','is_datetime','不是正确的时间格式',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为正确的时间格式
		array('user_status',array('Y','N'),'请选择是否冻结',self::VALUE_VALIDATE,'in'),//值不为空即验证，只能是Y是 N否
		array('shift_id','is_natural_num','请选择 正确的工班',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为自然数
		array('operator','1,30','操作人不能超过30个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证，长度不能超过30个字符
		array('operationtime','is_datetime','不是正确的时间格式',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为正确的时间格式
	);
	
	/**
	 * 获取所有用户列表
	 * @return array
	 */
	public function getUserList()
	{
		$list=$this->order('id desc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取用户信息
	 * @param int $id:用户ID
	 * @return array|boolean
	 */
	public function getUserMsg($id)
	{
		$res = $this->where ("uid=$id ")->find ();
		if ($res!==false) 
		{
			//部门
			$department = new \Common\Model\DepartmentModel();
			$res_d=$department->getDepartmentMsg($res['id']);
			$res['department_name']=$res_d['department_name'];
			//用户组
			$group = new \Common\Model\UserGroupModel();
			$res_g=$group->getUserGroupMsg($res['group_id']);
			$res['group_name']=$res_g['title'];
			return $res;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取用户详情
	 * @param int $id 用户ID
	 * @return array 一条用户详情记录，另包含用户组名称、部门名称、签到信息
	 */
	public function getUserDetail($id)
	{
		$sql="SELECT u.*,ug.title as groupname,d.department_name FROM __PREFIX__user u,__PREFIX__user_group ug,__PREFIX__department d where u.uid=$id and u.group_id=ug.id and u.department_id=d.id";
		$res=M()->query($sql);
		$msg=$res[0];
		if(!empty($msg['shift_id']))
		{
			$shift_id=$msg['shift_id'];
			//签到部门
			$shift = new \Common\Model\ShiftModel();
			$sign_in=$shift->where("shift_id='$shift_id'")->find();
			$department_id=$sign_in['department_id'];
			$dp=new \Common\Model\DepartmentModel;
			$department=$dp->getDepartmentMsg($department_id);
			$msg['shift']['department_id']=$department_id;
			$msg['shift']['department']=$department['parent_department_name'].'-'.$department['department_name'];
			$msg['shift']['parent_department_name']=$department['parent_department_name'];
			$msg['shift']['department_name']=$department['department_name'];
			//签到时间
			$msg['shift']['time']=$sign_in['begin_time'];
			//日期
			$msg['shift']['sign_in_date']=substr($shift_id, -9,-1);
			//班次
			$classes=substr($shift_id, -1);
			if($classes=='1')
			{
				$msg['shift']['classes']='白班';
			}else {
				$msg['shift']['classes']='夜班';
			}
			//当班理货长
			$master_id=$sign_in['shift_master'];
			if($master_id)
			{
				$master=$this->where("uid=$master_id")->field('staffno,user_name')->find();
				$msg['shift']['master']['staffno']=$master['staffno'];
				$msg['shift']['master']['username']=$master['user_name'];
			}else {
				$msg['shift']['master']['staffno']='';
				$msg['shift']['master']['username']='';
			}
		}else {
			$msg['shift']='';
		}
		return $msg;
	}
	
	/**
	 * 密码加密
	 * @param string $pwd:用户密码
	 * @return array
	 */
	public function encrypt($pwd)
	{
		$password = md5($pwd.'user'.substr($pwd,0,3));
		return $password;
	}
	
	
	/**
	 * 获取用户签到信息
	 * @param int $id 用户ID
	 * @return array
	 * @return @param department:签到部门
	 * @return @param sign_in_date:签到日期
	 * @return @param classes:班次
	 * @return @param master['staffno']:当班理货长工号
	 * @return @param master['username']:当班理货长姓名
	 * @return @param time:签到时间
	 */
	public function getSignInMsg($id)
	{
		$msg=$this->where("uid=$id")->field('shift_id')->find();
		if(!empty($msg['shift_id']))
		{
			$shift_id=$msg['shift_id'];
			//签到部门
			$shift = new \Common\Model\ShiftModel();
			$sign_in=$shift->where("shift_id='$shift_id'")->find();
			$department_id=$sign_in['department_id'];
			$dp=new \Common\Model\DepartmentModel;
			$department=$dp->getDepartmentMsg($department_id);
			$res['department']=$department['parent_department_name'].'-'.$department['department_name'];
			//签到时间
			$res['time']=$sign_in['date'].' '.$sign_in['begin_time'];
			//当班理货长
			$master_id=$sign_in['shift_master'];
			$master=$this->where("uid=$master_id")->field('staffno,user_name')->find();
			$res['master']['staffno']=$master['staffno'];
			$res['master']['username']=$master['username'];
			//日期
			$res['sign_in_date']=substr($shift_id, -9,-1);
			//班次
			$classes=substr($shift_id, -1);
			if($classes=='1')
			{
				$res['classes']='白班';
			}else {
				$res['classes']='夜班';
			}
		}else {
			$res='';
		}
		return $res;
	}
	
	/**
	 * 获取用户列表
	 * @param int $status 用户状态：默认空，查找全部;1 正常 0 冻结
	 * @return array
	 */
// 	public function getUserList($status='')
// 	{
// 		if($status!=='')
// 		{
// 			$where="u.userStatus='$status' and";
// 		}else {
// 			$where='';
// 		}
// 		$sql="SELECT u.*,ug.title as groupname,d.departmentName FROM userinfo u,usergroups ug,departmentinfo d WHERE $where u.group_id=ug.id and u.departmentInfoId=d.departmentInfoId";
// 		$userList=M()->query($sql);
// 		return $userList;
// 	}
	
	/**
	 * 用户登录
	 * @param string $staffno:工号
	 * @param string $pwd:密码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:内容、说明
	 */
	public function login($staffno,$pwd)
	{
		//检验原密码是否正确
		$msg=$this->where("staffno='$staffno'")->field('uid,user_pwd,group_id')->find();
		if($msg)
		{
			$pwd=$this->encrypt($pwd);
			if($pwd!=$msg['user_pwd'])
			{
				// 工号或密码错误
				$res=array(
						'code'=>$this->ERROR_CODE_USER['USER_LOGIN_ERROR'],
						'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_LOGIN_ERROR']]
				);
			}else {
				// 修改最后登录时间
				$data=array(
						'last_logintime'=>date('Y-m-d H:i:s')
				);
				$this->where("staffno='$staffno'")->save($data);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'登录成功',
						'uid'=>$msg['uid'],
						'group_id'=>$msg['group_id']
				);
			}
		}else {
			// 用户不存在
			$res=array(
					'code'=>$this->ERROR_CODE_USER['USER_NOT_EXIST'],
					'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_EXIST']]
			);
		}
		return $res;
	}
	
	/**
	 * 退出登录
	 */
	public function loginout()
	{
		
	}
	
	/**
	 * 用户修改密码
	 * @param int $uid:用户ID
	 * @param string $oldpwd:原密码
	 * @param string $pwd1:新密码
	 * @param string $pwd2:重复新密码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:内容、说明
	 */
	public function changePwd($uid,$oldpwd,$pwd1,$pwd2)
	{
		if($pwd1!=$pwd2)
		{
			// 两次密码不相同
			$res=array(
					'code'=>$this->ERROR_CODE_USER['USER_PASSWORD_NOT_MATCH'],
					'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_PASSWORD_NOT_MATCH']]
			);
		}else {
			//检验原密码是否正确
			$msg=$this->where("uid=$uid")->field('user_pwd')->find();
			if($msg!='')
			{
				$oldpwd=$this->encrypt($oldpwd);
				if($oldpwd!=$msg['user_pwd'])
				{
					// 原始密码不正确
					$res=array(
							'code'=>$this->ERROR_CODE_USER['USER_ORIGINALPASSWORD_ERROR'],
							'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_ORIGINALPASSWORD_ERROR']]
					);
				}else {
					$data=array(
							'user_pwd'=>$this->encrypt($pwd1)
					);
					$res1=$this->where("uid=$uid")->save($data);
					{
						if($res1!==false)
						{
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'修改密码成功！'
							);
						}else {
							// 数据库操作错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			}else {
				// 该用户不存在
				$res=array(
						'code'=>$this->ERROR_CODE_USER['USER_NOT_EXIST'],
						'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_EXIST']]
				);
			}
		}
		return $res;
	}
	
	/**
	 * 检验用户有效性
	 * @param int $uid:用户ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:内容、说明
	 */
	public function is_valid($uid)
	{
		$msg=$this->where("uid=$uid")->field('user_status')->find();
		if($msg!='')
		{
			if($msg['user_status']=='N')
			{
				// 用户被冻结
				$res=array(
						'code'=>$this->ERROR_CODE_USER['USER_FROZEN'],
						'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_FROZEN']]
				);
			}else {
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'该用户可以使用'
				);
			}
		}else {
			// 用户不存在
			$res=array(
					'code'=>$this->ERROR_CODE_USER['USER_NOT_EXIST'],
					'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_EXIST']]
			);
		}
		return $res;
	}
	
	/**
	 * 判断用户是否签到
	 * @param int $uid:用户ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:内容、说明
	 * @return @param shift_id:用户最新的签到工班组ID
	 */
	public function is_sign($uid)
	{
		$msg=$this->where("uid=$uid")->field('shift_id,department_id')->find();
		if ($msg['shift_id'])
		{
			$shift_id=$msg['shift_id'];
			// 用户签到工班所属部门
			$Shift=new \Common\Model\ShiftModel();
			$res_s=$Shift->where("shift_id='$shift_id'")->field('department_id')->find();
			$department_id=$res_s['department_id'];
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'已签到！',
					'shift_id'=>$shift_id,
					'department_id'=>$department_id
			);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_USER['USER_NOT_SIGNIN'],
					'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_SIGNIN']]
			);
		}
		return $res;
	}
	
	/**
	 * 判断用户是否为理货长
	 * 用户身份为理货长、部门经理、公司领导时判断具备理货长权限，理货员时不具备
	 * @param int $uid:用户ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:内容、说明
	 */
	public function is_chiefTally($uid)
	{
		$msg=$this->where("uid=$uid")->field('group_id')->find();
		if ($msg['group_id']==12 or $msg['group_id']==13 or $msg['group_id']==17)
		{
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'该用户是理货长或部门长或公司领导！'
			);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY'],
					'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY']]
			);
		}
		return $res;
	}
	
	/**
	 * 获取同一工班人员列表
	 * @param string $shift_id:班次ID
	 * @return array
	 */
	public function getWorkerList($shift_id)
	{
		$list=$this->where("shift_id='$shift_id'")->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 判断用户是否有权限进行审核操作
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
	public function isPermissionsforexamine($uid,$instruction_id,$business='qbzx')
	{
		//①判断用户是否合法
		$res_u1=$this->is_valid($uid);
		if($res_u1['code']!=0)
		{
			//用户不合法，返回码采用的和判断用户是否合法的一致
			$res=$res_u1;
		}else {
			//②用户合法，判断用户是否签到
			$res_u2=$this->is_sign($uid);
			if($res_u2['code']!=0)
			{
				//用户未签到，返回码采用的和判断用户是否签到一致
				$res=$res_u2;
				return $res;
			}
			//③用户合法且已签到，判断用户是否为理货长身份
			$res_u3=$this->is_chiefTally($uid);
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
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'该用户是当班理货长！'
							);
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
	
	//获取修改表内容
	public function getamend ($business,$operation_id)
	{
		$amendlist = D('amend')->field("a.*,u.user_name")->alias("a")->join("left join tally_user u on u.uid=a.uid")->where("business='$business' and operation_id='$operation_id'")->select();
		return $amendlist;
	}
}
?>