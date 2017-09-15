<?php
/**
 * APP接口
 * 用户管理接口
 */
namespace App\Controller;
use App\Common\BaseController;

class UserController extends BaseController
{
	/**
	 * 用户登录
	 * @param string $staffno:工号
	 * @param string $pwd:密码
	 */
	public function login()
	{
		if(I('post.staffno') and I('post.pwd'))
		{
			$staffno = I('post.staffno');
			$pwd = I('post.pwd');
			$user = new \Common\Model\UserModel();
			$res_u = $user->login($staffno, $pwd);
			if($res_u['code'] == '0')
			{
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'登录成功',
						'uid'=>$res_u['uid'],
						'group_id'=>$res_u['group_id']
				);
			}else{
				$res = array(
						'code'=>$res_u['code'],
						'msg'=>$res_u['msg']
				);
			}
		}else{
			//参数不正确，参数缺失
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 判断用户是否为理货长
	 * 用户身份为理货长、部门经理、公司领导时判断具备理货长权限，理货员时不具备
	 * @param int $uid:用户ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:内容、说明
	 */
	public function is_chiefTally()
	{
		if(I('post.uid'))
		{
			$uid=I('post.uid');
			$user=new \Common\Model\UserModel();
			$res=$user->is_chiefTally($uid);
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取用户信息
	 * @param int $uid:用户ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:内容、说明
	 */
	public function getUserMsg()
	{
		if(I('post.uid'))
		{
			$uid=I('post.uid');
			$user=new \Common\Model\UserModel();
			$content=$user->getUserDetail($uid);
			if($content!==false)
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'content'=>$content
				);
			}else{
				//数据库操作错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else{
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
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
	public function changePwd()
	{
		if(I('post.uid')and I('post.oldpwd') and I('post.pwd1')and I('post.pwd2'))
		{
			$uid=I('post.uid');
			$oldpwd=I('post.oldpwd');
			$pwd1=I('post.pwd1');
			$pwd2=I('post.pwd2');
			$user=new \Common\Model\UserModel();
			$res=$user->changePwd($uid,$oldpwd,$pwd1,$pwd2);
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}

	/**
	 * 统计三个系统的已完成、未完成、未开始箱子个数
	 * @param uid 用户ID
	 * @param business 业务系统qbzx、dd、cfs
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回结果列表
	 */
	public function count_ctn()
	{
		if (I ( 'post.uid' ) and I('post.business'))
		{
			$uid = I ( 'post.uid' );
			$business = I('post.business');
			// 根据用户ID查询被分配的指令任务
			$sql1 = "select d.instruction_id from __PREFIX__dispatch d,__PREFIX__dispatch_detail dd where d.id = dd.dispatch_id and dd.clerk_id='$uid' and d.business='".$business."' and d.mark!='1'";
			$res_i = M ()->query ( $sql1 );
			if (count ( $res_i ) > 0) 
			{
				foreach ( $res_i as $instruction ) {
					$instruction_arr [] = $instruction ['instruction_id'];
				}
				$instruction_id = implode ( ',', array_unique ( $instruction_arr ) );
			} else {
				// 该理货员尚未被分配任务！
				$res = array (
						'code' => $this->ERROR_CODE_INSTRUCTION ['NOT_ALLOCATION_TASK'],
						'msg' => $this->ERROR_CODE_INSTRUCTION_ZH [$this->ERROR_CODE_INSTRUCTION ['NOT_ALLOCATION_TASK']] 
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			switch ($business) {
				case 'qbzx':
					// 获取相应状态的箱数量未开始
					$sql2 = "select c.*,cm.ctn_master from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_instruction_ctn c,__PREFIX__container_master cm where i.id in ($instruction_id) and i.id=c.instruction_id and c.status='0' and c.ctn_master=cm.id order by c.id desc";
					$list = M ()->query ( $sql2 );
					$no_begin_num = count ( $list );
					$qbzxoperation = new \Common\Model\QbzxOperationModel();
					//工作中
					$begin_num =  $qbzxoperation->get_num($instruction_id,'1',$uid);
					//已完成
					$finish_num =  $qbzxoperation->get_num($instruction_id,'2',$uid);
					break;
				case 'cfs':
					// 获取相应状态的箱列表-未开始
					$sql2 = "select c.* from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_ctn c where i.id in ($instruction_id) and i.id=c.instruction_id and c.status='0' order by c.id desc";
					$list = M ()->query ( $sql2 );
					$no_begin_num = count ( $list );
					$cfsoperation = new \Common\Model\CfsOperationModel();
					//工作中
					$begin_num =  $cfsoperation->get_num($instruction_id,'1',$uid);
					//已完成
					$finish_num =  $cfsoperation->get_num($instruction_id,'2',$uid);
					break;
				case 'dd':
					// 获取相应状态的箱列表-未开始
					$sql2 = "select c.*,i.is_must,unpackagingplace from __PREFIX__dd_instruction i,__PREFIX__dd_plan_container c,__PREFIX__dd_plan p where i.id in ($instruction_id) and i.plan_id=c.plan_id and p.id=c.plan_id and c.status='0' order by i.is_must desc,c.id desc";
					$list = M ()->query ( $sql2 );
					$no_begin_num = count ( $list );
					$ddoperation = new \Common\Model\DdOperationModel();
					//工作中
					$begin_num =  $ddoperation->get_num($instruction_id,'1',$uid);
					//已完成
					$finish_num =  $ddoperation->get_num($instruction_id,'2',$uid);
					break;
				default:
					$no_begin_num = 0;
					$begin_num['num']=0;
					$finish_num['num']=0;
					break;
			}
			$res = array(
					'code'  =>  '0',
					'msg'   =>  '成功!',
					'no_begin_num'   =>   $no_begin_num,
					'begin_num'      =>   $begin_num['num'],
					'finish_num'     =>   $finish_num['num']
				);
		} else {
			// 参数缺失，参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['PARAMETER_ERROR']],
					'list' => ''
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
}
?>