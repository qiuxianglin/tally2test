<?php
/**
 * 起泊装箱业务类
 * 作业管理类
 */
namespace Common\Model;
use Think\Model;

class QbzxOperationModel extends Model
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
		array('ctn_id','require','箱不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('ctn_id','is_positive_int','箱ID必须为正整数',self::EXISTS_VALIDATE,'function'),  //值不为空即验证，必须为正整数
		array('empty_weight','number','空箱重量必须为数字',self::VALUE_VALIDATE), //值不为空即验证，必须为数字
		array('halfclosedoor_picture','1,255','半关门照长度不能超过255个字符',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
		array('closedoor_picture','1,255','全关门照长度不能超过255个字符',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
		array('tmp_sealno','1,20','临时铅封号长度不能超过20个字符',self::VALUE_VALIDATE,'length'),  //值不为空验证，长度不能超过20个字符
		array('sealno','1,20','铅封号长度不能超过20个字符',self::VALUE_VALIDATE,'length'),  //值不为空验证，长度不能超过20个字符		
		array('seal_picture','1,255','铅封照长度不能超过255个字符',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
		array('cargo_weight','number','货物重量必须为数字',self::VALUE_VALIDATE), //值不为空即验证，必须为数字
		array('operation_id','is_positive_int','作业不存在',self::VALUE_VALIDATE,'function'), //值不为空的时候验证 ，必须为正整数
		array('begin_time','require','开始时间不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('begin_time','is_datetime','开始时间必须为时间格式',self::EXISTS_VALIDATE,'function'),  //存在即验证，必须我时间格式
		array('is_stop','require','暂停作业不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('is_stop',array('Y','N'),'请选择是否暂停作业',self::EXISTS_VALIDATE,'in'), //存在即验证，是Y否N
		array('is_reservation','require','是否预约不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('is_reservation',array('Y','N'),'请选择是否预约',self::EXISTS_VALIDATE,'in'), //存在即验证，是Y否N
		array('step','require','步骤不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('step','number','步骤必须为数字',self::EXISTS_VALIDATE), //存在即验证,必须为数字
		array('step',array(1,9),'步骤必须在1-9范围内',self::EXISTS_VALIDATE,'between'), //存在即验证，必须在1-9范围内
	);
	/**
	 * 获取作业详情
	 * @param int $ctn_id:箱ID
	 * @return array|boolean
	 */
	public function getOperationMsg($ctn_id)
	{
		$msg=$this->where("ctn_id=$ctn_id")->find();
		if($msg!==false)
		{
			//根据作业ID获取空箱照片
			$empty = new \Common\Model\QbzxEmptyCtnImgModel();
			$empty_picture=$empty->where("operation_id='".$msg['id']."'")->select();
			$msg['seal_picture_a']= IMAGE_QBZX_SEAL.$msg['seal_picture'];//获取铅封图片完整地址
			foreach ($empty_picture as $key => $vo)
			{
				$empty_picture[$key]['empty_picture'] =  $empty_picture[$key]['empty_picture'];
				$empty_picture[$key]['empty_picture_a'] = IMAGE_QBZX_EMPTY . $empty_picture[$key]['empty_picture'];
			}
			$msg['empty_picture']= $empty_picture;
			$msg['halfclose_door_picture_a'] = IMAGE_QBZX_HALFCLOSEDOOR.$msg['halfclose_door_picture'];
			$msg['close_door_picture_a'] = IMAGE_QBZX_CLOSEDOOR.$msg['close_door_picture'];
			//根据作业ID获取补充照片
			$supplement = new \Common\Model\QbzxSupplementPictureModel();
			$supplement_picture=$supplement->where("operation_id='".$msg['id']."'")->select();
			$msg['supplement_picture']= $supplement_picture;
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 根据箱ID获取作业详情
	 * @param int $ctn_id:箱ID
	 * @return array|boolean
	 */
	public function getOperationMsgByCtn($ctn_id)
	{
		$msg=$this->where("ctn_id=$ctn_id")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
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
	public function isPermissions($uid,$instruction_id,$business='qbzx')
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
								//工办未交办，理货长可以进行图片打包打包
								$res=array(
										'code'=>0,
										'msg'=>'有权限打包下载！'
								);
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

	// 获取相应状态的箱列表-工作中、已完成
	public function get_num($instruction_id,$status,$uid)
	{
		// 获取相应状态的箱列表-工作中、已完成
		$sql3 = "select c.*,cm.ctn_master from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_instruction_ctn c,__PREFIX__container_master cm where i.id=c.instruction_id and cm.id=c.ctn_master and i.id in ($instruction_id)  and c.status='$status' and c.operator_id='$uid' order by c.id desc";
		$list = M ()->query ( $sql3 );
		if ($list !== false) {
			$cnum = count ( $list );
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'num'=>$cnum
				);
		}else{
			$res=array(
					'code'=>3,
					'msg'=>'失败'
				);
		}
		return $res;
	}
}
?>