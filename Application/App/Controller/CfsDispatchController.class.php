<?php
/**
 * APP接口
 * 派工管理
 */
namespace App\Controller;
use App\Common\BaseController;
header ( "Access-Control-Allow-Origin: *" );

class CfsDispatchController extends BaseController
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
			$res=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'cfs');
		}else {
			//参数不正确 参数缺失 3
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
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
			$cfsInstruction=new \Common\Model\CfsInstructionModel();
			$res_s=$cfsInstruction->where("id='$instruction_id'")->field('status')->find();
			if($res_s['status']!='0')
			{
				//该指令尚未派工，请先派工！ 503
				$res=array(
						'code'=>$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH'],
						'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			$dispatch=new \Common\Model\DispatchModel();
			//判断用户是否有权限进行派工
			$res_u=$dispatch->isPermissionsForDispatching($chieftally, $instruction_id,'cfs');
			if($res_u['code']!=0)
			{
				//用户没有权限
				$res=$res_u;
			}else {
				//用户有权限
				$res_a=$dispatch->addTask($chieftally, $shift_id, $instruction_id, 'cfs', $workerlist);
				if($res_a!==false)
				{
					//CFS装箱的指令状态改为是否必做
					$data=array(
							'status'=>'1',
					);
					$res_i=$cfsInstruction->where("id=$instruction_id")->save($data);
					if($res_i!==false)
					{
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'新增派工成功'
						);
					}else {
						//数据库错误 2
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}else {
					//数据库错误 2
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			//参数不正确 参数缺失 3
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 修改派工
	 * @param int $uid:当班理货长用户ID
	 * @param int $dispatch_id:派工ID
	 * @param int $instruction_id:指令ID
	 * @param array $workerlist:派工人员列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function editTask()
	{
		if(I('get.dispatch_id') and I('get.uid') and I('get.instruction_id') and I('get.workerlist'))
		{
			$uid=I('get.uid');
			$dispatch_id=I('get.dispatch_id');
			$instruction_id=I('get.instruction_id');
			$workerlist=I('get.workerlist');
			//判断指令是否已派工
			$cfsInstruction=new \Common\Model\CfsInstructionModel();
			$res_s=$cfsInstruction->where("id='$instruction_id'")->field('status')->find();
			if($res_s['status']=='0')
			{
				//该指令尚未派工，请先派工！ 503
				$res=array(
						'code'=>$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH'],
						'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			$dispatch=new \Common\Model\DispatchModel();
			//判断用户是否有权限进行派工
			$res_u=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'cfs');
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
				//根据指令ID获取所有工作中的箱子的操作人ID
				$CfsInstructionCtn = new \Common\Model\CfsInstructionCtnModel();
				$ulist = $CfsInstructionCtn->where("instruction_id='$instruction_id' and status='1'")->field('operator_id')->select();
				//修改前的操作人列表
				foreach($ulist as $vo){
					$msg1[] =$vo['operator_id'];
				}
				
				$result2=array_intersect($result1,$msg1);
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
							'msg'=>$username.'的配箱已开始工作不能修改派工！'
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
	 * @param int $dispatch_id:派工ID
	 * @param int $instruction_id:指令ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function cancel()
	{
		if(I('get.dispatch_id')  and I('get.uid') and I('get.instruction_id'))
		{
			$dispatch_id=I('get.dispatch_id');
			$uid=I('get.uid');
			$instruction_id=I('get.instruction_id');
			//判断工班是否已交班，已交班不允许取消派工
			$dispatch=new \Common\Model\DispatchModel();
			$res_d=$dispatch->where("id=$dispatch_id")->find();
			if($res_d!==false)
			{
				if($res_d['mark']=='1')
				{
					//已交班不允许取消派工 205
					$res=array(
							'code'=>$this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED'],
							'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED']]
					);
				}else {
					//判断指令是否已派工
					$cfsInstruction=new \Common\Model\CfsInstructionModel();
					$res_s=$cfsInstruction->where("id='$instruction_id'")->field('status')->find();
					if($res_s['status']=='0')
					{
						//该指令尚未派工，请先派工！ 503
						$res=array(
								'code'=>$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH'],
								'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH']]
						);
						echo json_encode ($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
					// 判断用户是否有权限进行派工
					$res_u=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'cfs');
					if($res_u['code']!=0)
					{
						//用户没有权限
						$res=$res_u;
					}else {
						// 查询指令下的配箱
						$sql="select c.id from __PREFIX__cfs_instruction_ctn c,__PREFIX__cfs_instruction i where i.id=c.instruction_id and i.id='$instruction_id'";
						$res_ctn=M()->query($sql);
						if($res_ctn!==false)
						{
							foreach ($res_ctn as $c)
							{
								$ctn_arr[]=$c['id'];
							}
							$ctn_allid=implode($ctn_arr, ',');
							if($ctn_allid!='')
							{
								//判断指令下是否存在已作业的箱子，存在不允许取消派工
								$instruction_ctn = new \Common\Model\CfsInstructionCtnModel();
								$res_o = $instruction_ctn->where("instruction_id='$instruction_id' and status in (1)")->count();
								if($res_o  > 0){
									//该指令存在正在作业的箱子，无法取消派工！ 209
									$res=array(
											'code'=>$this->ERROR_CODE_SHIFT['ANY_CONTAINER_IN_OPERATION'],
											'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['ANY_CONTAINER_IN_OPERATION']]
									);
									echo json_encode ($res,JSON_UNESCAPED_UNICODE);
									exit();
								}else {
									//指令下不存在作业中的配箱，可以取消
									$res_cancel=$dispatch->cancel($dispatch_id);
									if($res_cancel!==false)
									{
										//修改指令状态为未派工
										$data=array(
												'status'=>'0',
										);
										$cfsInstruction->where("id='$instruction_id'")->save($data);
										//修改想状态
										$data1 = array(
												'status'   =>   '0',
												'operator_id'   =>   null
											);
										$instruction_ctn->where("id='$instruction_id'")->save($data1);
										$res=array(
												'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
												'msg'=>'取消派工成功！'
										);
									}else {
										//数据库连接错误 2
										$res=array(
												'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
												'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
										);
									}
								}
							}else {
								//指令下不存在配箱，可以直接取消
								$res_cancel=$dispatch->cancel($dispatch_id);
								if($res_cancel!==false)
								{
									//修改指令状态为未派工
									$data=array(
											'status'=>'0',
											'is_must'=>null
									);
									$cfsInstruction->where("id='$instruction_id'")->save($data);
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
											'msg'=>'取消派工成功！'
									);
								}else {
									//数据库连接错误 2
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}
						}else {
							//数据库连接错误 2
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			}else {
				//数据库连接错误 2
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else {
			//参数缺失 参数不正确 3
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
	 * @param int $shift_id:签到工班ID
	 * @return array|boolean
	 */
	public function getDetail()
	{
		if(I('post.shift_id') and I('post.instruction_id'))
		{
			$shift_id=I('post.shift_id');
			$instruction_id=I('post.instruction_id');
			//判断指令是否已派工
			$cfsInstruction=new \Common\Model\CfsInstructionModel();
			$res_s=$cfsInstruction->where("id='$instruction_id'")->field('status')->find();
			if($res_s['status']=='0')
			{
				//该指令尚未派工，请先派工！ 503
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
			$dispatch_detail=$dispatch->getDetail($instruction_id, 'cfs');
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
					'dispatch_id'=>$dispatch_detail['id'],
					'list'=>$workerlist
			);
		}else {
			//参数不正确，参数缺失 3
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取签入同一工班的人员列表
	 * @param string $shift_id:工班ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回同一工班的人员列表
	 */
	public function getWorkList()
	{
		if(I('post.dispatch_id'))
		{
			$dispatch_id=I('post.dispatch_id');
			//获取签入同一工班的人员列表
			$user=new \Common\Model\UserModel();
			$workerlist=$user->getWorkerList($dispatch_id);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$workerlist
			);
		}else {
			//参数不正确，参数缺失 3
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>
