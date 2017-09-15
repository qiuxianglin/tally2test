<?php
/**
 * APP接口
 * 派工管理
 */
namespace App\Controller;
use App\Common\BaseController;
header ( "Access-Control-Allow-Origin: *" );

class QbzxDispatchController extends BaseController
{
	/**
	 * 判断用户是否有权限进行派工
	 * @param int $uid:用户ID
	 * @param int $instruction_id:指令ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param gand_id:工班ID
	 * @return @param department_id:部门ID
	 * @return @param workerlist:签入同一工班的人员列表
	 */
	public function isPermissionsForDispatching()
	{
		if(I('post.uid') and I('post.instruction_id'))
		{
			$uid=I('post.uid');
			$instruction_id=I('post.instruction_id');
			$dispatch=new \Common\Model\DispatchModel();
			$res=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'qbzx');
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 新增派工
	 * @param int $chieftally:当班理货长用户ID
	 * @param string $shift_id:工班ID
	 * @param int $instruction_id:指令ID
	 * @param array $workerlist:派工人员ID列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function addTask()
	{
		if(I('get.chieftally') and I('get.shift_id') and I('get.instruction_id') and I('get.workerlist'))
		{
			$chieftally=I('get.chieftally');
			$shift_id=I('get.shift_id');
			$instruction_id=I('get.instruction_id');
			$workerlist=I('get.workerlist');
			//判断指令是否已派工
			$instruction=new \Common\Model\QbzxInstructionModel();
			$res_s=$instruction->where("id=$instruction_id")->field('status')->find();
			if($res_s['status']!='0')
			{
				$res=array(
						'code'=>$this->ERROR_CODE_INSTRUCTION['ALREADY_DISPATCH'],
						'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['ALREADY_DISPATCH']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			$dispatch=new \Common\Model\DispatchModel();
			//判断用户是否有权限进行派工
			$res_u=$dispatch->isPermissionsForDispatching($chieftally, $instruction_id,'qbzx');
			if($res_u['code']!=0)
			{
				//用户没有权限
				$res=$res_u;
			}else {
				//用户有权限
				$res_a=$dispatch->addTask($chieftally, $shift_id, $instruction_id, 'qbzx', $workerlist);
				if($res_a!==false)
				{
					//CFS装箱的指令状态改为是否必做
					$data=array(
							'status'=>'1',
					);
					$res_i=$instruction->where("id=$instruction_id")->save($data);
					if($res_i!==false)
					{
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'新增派工成功'
						);
					}else {
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}else {
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 修改派工
	 * @param int $uid:当班理货长用户ID
	 * @param int $repair_id:派工ID
	 * @param int $instruction_id:指令ID
	 * @param array $workerlist:派工人员列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function editTask()
	{
		if(I('get.dispatch_id') and I('get.workerlist') and I('get.uid') and I('get.instruction_id') and I('get.workerlist'))
		{
			$uid=I('get.uid');
			$dispatch_id=I('get.dispatch_id');
			$instruction_id=I('get.instruction_id');
			$workerlist=I('get.workerlist');
			//判断指令是否已派工;未派工提示未派工，已派工获取派工的作业人员ID
			$instruction=new \Common\Model\QbzxInstructionModel();
			$res_s=$instruction->where("id='$instruction_id'")->field('status')->find();
			if($res_s['status']=='0')
			{
				$res=array(
						'code'=>$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH'],
						'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			$dispatch=new \Common\Model\DispatchModel();
			//判断用户是否有权限进行派工
			$res_u=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'qbzx');
			if($res_u['code']!=0)
			{
				//用户没有权限
				$res=$res_u;
			}else {
				//获取原先派工的人员id列表
				$dispatchdetail=new \Common\Model\DispatchDetailModel();
				$uidlist = $dispatchdetail->getclerkidlist($dispatch_id);
				//判断原先派工列表跟修改后派工列表的差集
				$result1 = array_diff($uidlist,$workerlist);
// 				echo "修改前的ID：";
// 				var_dump($uidlist);
// 				echo "修改后的ID：";
// 				var_dump($workerlist);
// 				echo "修改后的多出来的ID：";
// 				var_dump($result1);
				
				//根据指令ID获取所有工作中的箱子的操作人ID
				$QbzxInstructionCtn = new \Common\Model\QbzxInstructionCtnModel();
				$ulist = $QbzxInstructionCtn->where("instruction_id='$instruction_id' and status='1'")->field('operator_id')->select();
				foreach($ulist as $vo){
					$msg1[] =$vo['operator_id'];
				}
// 				echo "修改前该指令作业中的ID：";
// 				var_dump($msg1);
				
				$result2=array_intersect($result1,$msg1);
// 				echo "重复的数据：";
				$user = new \Common\Model\UserModel();
				$username="";
				foreach($result2 as $vo){
					 $user_name = $user->where("uid='$vo'")->field('user_name')->find();	
					 $username .= $user_name['user_name'].",";			
				}
				$username = substr($username,0,-1);
				if(count($result2)>0){
					$res=array(
							'code'=>12,
							'msg'=>$username.'配箱已开始工作不能修改派工！'
					);
					echo json_encode ($res,JSON_UNESCAPED_UNICODE);
					exit();
				}
				$res_e=$dispatch->editTask($dispatch_id, $workerlist);
				if($res_e!==false)
				{
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'修改派工成功'
						);
				}else {
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 取消派工
	 * @param int $uid:当班理货长用户ID
	 * @param int $repair_id:派工ID
	 * @param int $instruction_id:指令ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function cancel()
	{
		if(I('get.repair_id')  and I('get.uid') and I('get.instruction_id'))
		{
			$repair_id=I('get.repair_id');
			$uid=I('get.uid');
			$instruction_id=I('get.instruction_id');
			//判断工班是否已交班，已交班不允许取消派工
			$dispatch=new \Common\Model\DispatchModel();
			$res_d=$dispatch->where("id=$repair_id")->find();
			if($res_d!==false)
			{
				if($res_d['mark']=='1')
				{
					//已交班不允许取消派工
					$res=array(
							'code'=>$this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED'],
							'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED']]
					);
				}else {
					//判断指令是否已派工
					$instruction=new \Common\Model\QbzxInstructionModel();
					$res_s=$instruction->where("id='$instruction_id'")->field('status')->find();
					if($res_s['status']=='0')
					{
						$res=array(
								'code'=>$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH'],
								'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH']]
						);
						echo json_encode ($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
					//判断用户是否有权限进行派工
					$res_u=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'qbzx');
					if($res_u['code']!=0)
					{
						//用户没有权限
						$res=$res_u;
					}else {
						//查询指令下的配箱是否有在作业中
						$instruction_ctn = new \Common\Model\QbzxInstructionCtnModel();
						$res_c = $instruction_ctn->where("instruction_id='$instruction_id' and status in(1)")->count();
						if($res_c > 0)
						{
							//存在工作中的箱子
							$res=array(
									'code'=>$this->ERROR_CODE_SHIFT['ANY_CONTAINER_IN_OPERATION'],
									'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['ANY_CONTAINER_IN_OPERATION']]
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit();
						}else{
							$res_cancel=$dispatch->cancel($repair_id);
							if($res_cancel!==false)
							{
								//修改指令状态为未派工
								$data=array(
										'status'=>'0',
								);
								$instruction->where("id='$instruction_id'")->save($data);
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'取消派工成功！'
								);
							}else {
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}
					}
				}
			}else {
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取派工详情
	 * @param int $instruction_id:指令ID
	 * @param int $work_id:签到工班ID
	 * @return array|boolean
	 */
	public function getDetail()
	{
		if(I('post.shift_id') and I('post.instruction_id'))
		{
			$shift_id=I('post.shift_id');
			$instruction_id=I('post.instruction_id');
			//判断指令是否已派工
			$instruction=new \Common\Model\QbzxInstructionModel();
			$res_s=$instruction->where("id='$instruction_id'")->field('status')->find();
			if($res_s['status']=='0')
			{
				$res=array(
						'code'=>$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH'],
						'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			//获取签入同一工班的人员列表
			$user=new \Common\Model\UserModel();
			$workerlist=$user->getWorkerList($shift_id);
			//获取派工详情
			$dispatch=new \Common\Model\DispatchModel();
			$dispatch_detail=$dispatch->getDetail($instruction_id, 'qbzx');
			$workarr=$dispatch_detail['detail'];
			//已派工人员ID列表
			foreach($workarr as $w)
			{
				$is_dispatch[]=$w['uid'];
			}
			//签入同一工班的人员是否已派工标志
			$num=count($workerlist);
			for ($i=0;$i<$num;$i++)
			{
				if(in_array($workerlist[$i]['uid'], $is_dispatch))
				{
					$workerlist[$i]['is_check']='Y';
				}else {
					$workerlist[$i]['is_check']='N';
				}
			}
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'repair_id'=>$dispatch_detail['id'],
					'list'=>$workerlist
			);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取签入同一工班的人员列表
	 * @param string $work_id:工班ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回同一工班的人员列表
	 */
	public function getWorkList()
	{
		if(I('post.shift_id'))
		{
			$shift_id=I('post.shift_id');
			//获取签入同一工班的人员列表   
			$user=new \Common\Model\UserModel();
			$workerlist=$user->getWorkerList($shift_id);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$workerlist
			);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	
}
?>

