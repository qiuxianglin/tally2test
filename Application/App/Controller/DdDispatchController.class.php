<?php
/**
 * APP接口
 * 派工管理
 */
namespace App\Controller;
use App\Common\BaseController;

class DdDispatchController extends BaseController
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
			$Dispatch=new \Common\Model\DispatchModel();
			$res=$Dispatch->isPermissionsForDispatching($uid, $instruction_id,'dd');
		}else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 新增派工
	 * @param int $chiefTally:当班理货长用户ID
	 * @param string $shift_id:工班ID
	 * @param int $instruction_id:指令ID
	 * @param array $workerlist:派工人员ID列表
	 * @param string $is_must:指令是否必须实际作业 Y是 N否
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function addTask()
	{
		if(I('get.chiefTally') and I('get.shift_id') and I('get.instruction_id') and I('get.workerlist'))
		{
			$chiefTally=I('get.chiefTally');
			$shift_id=I('get.shift_id');
			$instruction_id=I('get.instruction_id');
			$workerlist=I('get.workerlist');
			$is_must=I('get.is_must');
			//判断指令是否已派工
			$DdInstruction=new \Common\Model\DdInstructionModel();
			$res_s=$DdInstruction->where("id=$instruction_id")->field('status')->find();
			if($res_s['status']!='0')
			{
				// 该指令已派工，无法新增派工
				$res=array(
						'code'=>$this->ERROR_CODE_INSTRUCTION['ALREADY_DISPATCH'],
						'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['ALREADY_DISPATCH']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			$Dispatch=new \Common\Model\DispatchModel();
			//判断用户是否有权限进行派工
			$res_u=$Dispatch->isPermissionsForDispatching($chiefTally, $instruction_id,'dd');
			if($res_u['code']!=0)
			{
				//用户没有权限
				$res=$res_u;
			}else {
				//用户有权限
				$res_a=$Dispatch->addTask($chiefTally, $shift_id, $instruction_id, 'dd', $workerlist);
				if($res_a!==false)
				{
					//将门到门拆箱的指令状态改为是否必做
					$data=array(
							'status'=>'1',
							'is_must'=>$is_must
					);
					if(!$DdInstruction->create($data))
					{
						// 验证不通过
						// 参数不正确，参数缺失
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
								'msg'=>$DdInstruction->getError()
						);
					}else {
						// 验证通过
						$res_i=$DdInstruction->where("id=$instruction_id")->save($data);
						if($res_i!==false)
						{
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'新增派工成功'
							);
						}else {
							// 数据库操作错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}else {
					// 数据库操作错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			// 参数不正确，参数缺失
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
	 * @param int $dispatch_id:派工ID
	 * @param int $instruction_id:指令ID
	 * @param array $workerlist:派工人员列表
	 * @param string $is_must:指令是否必须实际作业 Y是 N否
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function editTask()
	{
		if(I('get.dispatch_id') and I('get.workerlist') and I('get.uid') and I('get.instruction_id') and is_array(I('get.workerlist')))
		{
			$uid=I('get.uid');
			$dispatch_id=I('get.dispatch_id');
			$instruction_id=I('get.instruction_id');
			$workerlist=I('get.workerlist');
			$is_must=I('get.is_must');
			//判断指令是否已派工
			$DdInstruction=new \Common\Model\DdInstructionModel();
			$res_s=$DdInstruction->where("id=$instruction_id")->field('status,is_must')->find();
			if($res_s['status']=='0')
			{
				// 该指令尚未派工，请先派工
				$res=array(
						'code'=>$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH'],
						'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			$Dispatch=new \Common\Model\DispatchModel();
			//判断用户是否有权限进行派工
			$res_u=$Dispatch->isPermissionsForDispatching($uid, $instruction_id,'dd');
			if($res_u['code']!=0)
			{
				//用户没有权限
				$res=$res_u;
			}else {
				//判断预报下的配箱是否正在作业
				//获取原先派工的人员id列表
				$dispatchdetail=new \Common\Model\DispatchDetailModel();
				$uidlist = $dispatchdetail->getclerkidlist($dispatch_id);
				//判断原先派工列表跟修改后派工列表的差集
				$result1 = array_diff($uidlist,$workerlist);
				
				//根据指令ID获取预报计划ID在获取所有工作中的箱子的操作人ID
				$sql = "select c.operator_id from tally_dd_plan_container c,tally_dd_instruction i where i.id='$instruction_id' and c.status='1'";
				$ulist = M()->query($sql);
				
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
							'msg'=>$username.'配箱已开始工作不能修改派工！'
					);
					echo json_encode ($res,JSON_UNESCAPED_UNICODE);
					exit();
				}
				$res_e=$Dispatch->editTask($dispatch_id, $workerlist);
				if($res_e!==false)
				{
					//将门到门拆箱的指令状态改为是否必做
					$data=array(
							'is_must'=>$is_must
					);
					if(!$DdInstruction->create($data))
					{
						// 验证不通过
						// 参数不正确，参数缺失
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
								'msg'=>$DdInstruction->getError()
						);
					}else {
						// 验证通过
						$res_i=$DdInstruction->where("id=$instruction_id")->save($data);
						if($res_i!==false)
						{
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'修改派工成功'
							);
						}else {
							// 数据库操作错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}else {
					// 数据库操作错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			// 参数不正确，参数缺失
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
			$Dispatch=new \Common\Model\DispatchModel();
			$res_d=$Dispatch->where("id='$dispatch_id'")->find();
			if($res_d!==false)
			{
				if($res_d['mark']=='1')
				{
					// 该工班已交班，不准取消派工
					$res=array(
							'code'=>$this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED'],
							'msg'=>'该工班已交班，不准取消派工！'
					);
				}else {
					//判断指令是否已派工
					$DdInstruction=new \Common\Model\DdInstructionModel();
					$res_s=$DdInstruction->where("id=$instruction_id")->field('status,is_must')->find();
					if($res_s['status']=='0')
					{
						// 该指令尚未派工，请先派工
						$res=array(
								'code'=>$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH'],
								'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH']]
						);
						echo json_encode ($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
					//判断用户是否有权限进行派工
					$res_u=$Dispatch->isPermissionsForDispatching($uid, $instruction_id,'dd');
					if($res_u['code']!=0)
					{
						//用户没有权限
						$res=$res_u;
					}else {
						//查询指令下的配箱
						$sql="select c.* from __PREFIX__dd_plan_container c,__PREFIX__dd_instruction i where i.plan_id=c.plan_id and i.id=$instruction_id";
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
								//判断配箱是否正在工作中
								foreach ($res_ctn as $r)
								{
									if($r['status'] == '1')
									{
										// 该指令存在正在作业的箱子，无法取消派工
										$res=array(
												'code'=>$this->ERROR_CODE_SHIFT['ANY_CONTAINER_IN_OPERATION'],
												'msg'=>$this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['ANY_CONTAINER_IN_OPERATION']]
										);
										echo json_encode ($res,JSON_UNESCAPED_UNICODE);
										exit();
									}
								}
								//指令下不存在作业中的配箱，可以取消
								$res_cancel=$Dispatch->cancel($dispatch_id);
								if($res_cancel!==false)
								{
									//修改指令状态为未派工
									$data=array(
											'status'=>'0',
											'is_must'=>'N'
									);
									if(!$DdInstruction->create($data))
									{
										// 验证不通过
										$res=array(
												'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
												'msg'=>$DdInstruction->getError()
										);
									}else {
										// 验证通过
										$res_i=$DdInstruction->where("id=$instruction_id")->save($data);
										if($res_i!==false)
										{
											$res=array(
													'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
													'msg'=>'取消派工成功！'
											);
										}else {
											// 数据库操作错误
											$res=array(
													'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
													'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
											);
										}
									}
								}else {
									// 数据库操作错误
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}else {
								//指令下不存在配箱，可以直接取消
								$res_cancel=$Dispatch->cancel($dispatch_id);
								if($res_cancel!==false)
								{
									//修改指令状态为未派工
									$data=array(
											'status'=>'0',
											'is_must'=>'N'
									);
									if(!$DdInstruction->create($data))
									{
										// 验证不通过
										$res=array(
												'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
												'msg'=>$DdInstruction->getError()
										);
									}else {
										// 验证通过
										$res_i=$DdInstruction->where("id=$instruction_id")->save($data);
										if($res_i!==false)
										{
											$res=array(
													'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
													'msg'=>'取消派工成功！'
											);
										}else {
											// 数据库操作错误
											$res=array(
													'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
													'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
											);
										}
									}
								}else {
									// 数据库操作错误
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}
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
				// 数据库操作错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else {
			// 参数不正确，参数缺失
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
			$DdInstruction=new \Common\Model\DdInstructionModel();
			$res_s=$DdInstruction->where("id='$instruction_id'")->field('status,is_must')->find();
			if($res_s['status']=='0')
			{
				// 该指令尚未派工，请先派工
				$res=array(
						'code'=>$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH'],
						'msg'=>$this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NEED_DISPATCH']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			//获取签入同一工班的人员列表
			$User=new \Common\Model\UserModel();
			$workerlist=$User->getWorkerList($shift_id);
			//获取派工详情
			$Dispatch=new \Common\Model\DispatchModel();
			$dispatch_detail=$Dispatch->getDetail($instruction_id, 'dd');
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
					'is_must'=>$res_s['is_must'],
					'list'=>$workerlist
			);
		}else {
			// 参数不正确，参数缺失
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
		if(I('post.shift_id'))
		{
			$shift_id=I('post.shift_id');
			//获取签入同一工班的人员列表
			$User=new \Common\Model\UserModel();
			$workerlist=$User->getWorkerList($shift_id);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$workerlist
			);
		}else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>