<?php
/**
 * APP接口
 * 拆箱作业接口
 */
namespace App\Controller;
use App\Common\BaseController;

class DdOperationController extends  BaseController
{
	/**
	 * 获取指令下的配箱列表
	 * @param uid 用户ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回结果列表
	 */
	public function getContainerList()
	{

		if (I ( 'post.uid' ) and I('post.status')) 
		{
			$uid = I ( 'post.uid' );
			$status = I('post.status');
			// 根据用户ID查询被分配的指令任务
			$sql1="select r.instruction_id from __PREFIX__dispatch r,__PREFIX__dispatch_detail d where r.id = d.dispatch_id and d.clerk_id='$uid' and r.business='dd' and r.mark!='1'";		
			$res_i = M ()->query ( $sql1 );

			if(count($res_i)>0)
			{
				foreach ($res_i as $instruction)
				{
					$instruction_arr[]=$instruction['instruction_id'];
				}
				$instruction_id=implode(',', array_unique($instruction_arr));
			}else {
				// 该理货员尚未被分配任务
				$res = array (
						'code' => $this->ERROR_CODE_INSTRUCTION['NOT_ALLOCATION_TASK'],
						'msg' => $this->ERROR_CODE_INSTRUCTION_ZH[$this->ERROR_CODE_INSTRUCTION['NOT_ALLOCATION_TASK']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			//箱状态
			$ctn_status=json_decode(ctn_status,true);
			$ctn_status_nostart=$ctn_status['nostart'];
			$ctn_status_workin=$ctn_status['workin'];
			$ctn_status_finished=$ctn_status['finished'];
			// 获取相应状态的箱列表-未开始
			if($status == '1')
			{
				$sql2 = "select c.*,i.is_must,unpackagingplace from __PREFIX__dd_instruction i,__PREFIX__dd_plan_container c,__PREFIX__dd_plan p where i.id in ($instruction_id) and i.plan_id=c.plan_id and p.id=c.plan_id and c.status='$ctn_status_nostart' order by i.is_must desc,c.id desc";
				$list = M ()->query ( $sql2 );
				if ($list !== false)
				{
					$cnum=count($list);
					for($i=0;$i<$cnum;$i++)
					{
						if($list[$i]['step']=='0')
						{
								$list[$i]['is_begin']='Y';
						} else{
								$list[$i]['is_begin']='N';
						}
					}
				}
			}

			if($status == '2' or $status == '3')
			{
				$status = $status-1;
				// 获取相应状态的箱列表-工作中、已完成
				$sql3 = "select c.*,i.is_must,unpackagingplace from __PREFIX__dd_instruction i,__PREFIX__dd_plan_container c,__PREFIX__dd_plan p where i.plan_id=c.plan_id and i.id in ($instruction_id)  and c.status = '$status' and c.operator_id=$uid and p.id=c.plan_id order by i.is_must desc,c.id desc";
				$list = M ()->query ( $sql3 );

				if ($list !== false)
				{
					$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
					$cnum=count($list);
					for($i=0;$i<$cnum;$i++)
					{
						$ctn_id=$list[$i]['id'];
						$res_b=$DdPlanContainer->is_begin($ctn_id);
						if($res_b===true)
						{
							$list[$i]['is_begin']='Y';
						}else {
							$list[$i]['is_begin']='N';
						}
						$operation = new \Common\Model\DdOperationModel();
						$res_o = $operation->where ( "ctn_id='$ctn_id'" )->find ();
						$list [$i] ['tmp_sealno'] = $res_o ['tmp_sealno'];
						$list [$i] ['is_stop'] = $res_o ['is_stop'];
					}
				}
			}
			
			
			
			$num=count($list);
			$ctn_status_d=json_decode(ctn_status_d,true);
			for($i=0;$i<$num;$i++)
			{
				$list[$i]['status_zh']=$ctn_status_d[$list[$i]['status']];
			}
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
					'msg' => '成功',
					'list' => $list
			);
		} else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
					'list' => ''
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 理货员接单
	 * @param uid 用户ID
	 * @param ctn_id 箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function ordertaking()
	{
		if(I('post.uid') and I('post.ctn_id'))
		{
			// 箱状态
			$ctn_status=json_decode(ctn_status,true);
			$ctn_status_nostart=$ctn_status['nostart'];
			$uid=I('post.uid');
			$ctn_id=I('post.ctn_id');
			$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
			//获取符合条件的信息
			$res_c=$DdPlanContainer->where("id=$ctn_id")->field('operator_id,sealno')->find();
			if($res_c['operator_id']!='')
			{
				// 该配箱已被其他理货员操作，不得再次操作
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']],
				);
			}else {
				$where="id=$ctn_id and status = '$ctn_status_nostart' and operator_id is null";
				$data=array(
						'operator_id'=>$uid, 
						'status'=>$ctn_status['workin'] //将箱状态改为工作中
				);
				if(!$DdPlanContainer->create($data))
				{
					// 验证不通过
					// 参数不正确，参数缺失
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>$DdPlanContainer->getError()
					);
				}else {
					// 验证通过
					$res_m=$DdPlanContainer->where($where)->save($data);
					if($res_m!==false)
					{
						//判断该箱是否存在作业记录，存在的情况下修改作业的操作人
						$DdOperation=new \Common\Model\DdOperationModel();
						$res_o=$DdOperation->where("ctn_id=$ctn_id")->field('id')->find();
						if($res_o['id']!='')
						{
							//存在记录，修改操作人
							$data_o=array(
									'operator_id'=>$uid
							);
							if(!$DdOperation->create($data_o))
							{
								// 验证不通过
								// 参数不正确，参数缺失
								$res=array(
									'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
									'msg'=>$DdOperation->getError()
								);
							}else {
								// 验证通过
								$DdOperation->where("ctn_id=$ctn_id")->save($data_o);
							}
							$sql = "select ca.blno  from tally_dd_plan_container c,tally_dd_plan p,tally_dd_plan_cargo ca where c.plan_id = p.id and c.id=$ctn_id and ca.plan_id=p.id";
							$result = M()->query($sql);
							//计算关数量
							$operationlevel = new \Common\Model\DdOperationLevelModel();
							$operationlevelnum = $operationlevel->sumLevelNum($res_o['id']);
							$res=array(
									'operation_id' => $res_o['id'],
									'blno'    =>  $result,
									'seal_no' =>  $res_c['sealno'],
									'step'    => $res_o['step'],
									'level_num'   => $operationlevelnum,
									'code'	  =>  $this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'	  =>  '成功',
							);
						} else {
							// 查找提单号
							$sql = "select ca.blno  from tally_dd_plan_container c,tally_dd_plan p,tally_dd_plan_cargo ca where c.plan_id = p.id and c.id=$ctn_id and ca.plan_id=p.id";
							$arr = M()->query($sql);
// 							if(!empty($arr[0]['blno']) ){
								// 若没有作业id 修改处----------------------
								$data = array(
										'ctn_id' => $ctn_id,
										'begin_time'=>date('Y-m-d H:i:s'),
										'operator_id'=>$uid
								);
								if($DdOperation->create($data)){
									$oid = $DdOperation->add();
									$res=array(
											// 作业id 提单号
											'operation_id' => $oid,
											'blno' => $arr,
											'seal_no' => $res_c['sealno'],
											'step'    => 0,
											'level_num'   => 0,
											'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
											'msg'=>'成功',
									);
								} else {
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
											'msg'=>$DdOperation->getError()
									);
								}
								// --------------------------------end
// 							} else {
// 								$res=array(
// 										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
// 										'msg'=>'该预报没有配货',
// 								);
// 							}
							
						}
					}else{
						// 数据库操作错误
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						);
					}
				}
			}
		}else{
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取箱详情
	 * @param int $ctn_id:箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param content:成功时返回箱详情
	 */
	public function getContainerDetail()
	{
		if (I ( 'post.ctn_id' )) 
		{
			$ctn_id = I ( 'post.ctn_id' );
			$DdPlanContainer = new \Common\Model\DdPlanContainerModel();
			$detail = $DdPlanContainer->getContainerMsg($ctn_id);
			if ($detail !== false) 
			{
				//根据预报计划ID获取预报详情
				$plan_id=$detail['plan_id'];
				$DdPlan=new \Common\Model\DdPlanModel();
				$plan_msg=$DdPlan->getPlanMsg($plan_id);
				$detail['blno']=$plan_msg['blno'];
				//根据箱ID获取作业ID、工作步骤
				$DdOperation=new \Common\Model\DdOperationModel();
				$res_o=$DdOperation->where("ctn_id=$ctn_id")->field('id,step')->find();
				$detail['operation_id']=$res_o['id'];
				$detail['step']=$res_o['step'];
				//判断箱下面是否有关存在
				$sql="select count(l.id) from __PREFIX__dd_operation o,__PREFIX__dd_operation_level l where o.ctn_id='$ctn_id' and o.id=l.operation_id";
				$res1=M()->query($sql);
				$level_num=$res1[0]['count(l.id)'];
				if($level_num>0)
				{
					$detail['has_level']='Y';
				}else {
					$detail['has_level']='N';
				}
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg' => '成功',
						'content' => $detail 
				);
			} else {
				// 数据库操作错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
				);
			}
		} else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
					'content'=>''
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 拆箱作业信息核对
	 * @param int $operation_id 作业id
	 * @param int $uid:操作员用户ID
	 * @param int $ctn_id:箱ID
	 * @param string $door_picture:箱门照片
	 * @param string $seal_picture:铅封照片
	 * @param string $true_sealno:实际铅封号
	 * @param string $damage_remark:箱残损备注
	 * @param array $damage_img:箱残损图片
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function OperationCheck()
	{
		if (I ( 'post.ctn_id' ) and I ( 'post.uid' ) and I('post.operation_id'))
		{
			$operation_id = I('post.operation_id');
			$ctn_id = I ( 'post.ctn_id' );
			$uid = I ( 'post.uid' );
			//检查箱的操作记录是否存在，存在不允许新增
			$DdOperation=new \Common\Model\DdOperationModel();
			/*$res_ctn=$DdOperation->where("ctn_id=$ctn_id")->field("id")->find();
			if($res_ctn['id']!='')
			{
				// 该箱已存在操作记录，请勿重复操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['NO_REPEAT_OPERATION'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_REPEAT_OPERATION']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}*/
			//检查箱的操作员是否和用户符合，不符合禁止操作
			$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
			$res_u=$DdPlanContainer->where("id=$ctn_id")->field('operator_id')->find();
			if($res_u['operator_id']=='')
			{
				// 该箱尚无操作人员，请先接单
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['NEED_ACCEPT_TASK'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_ACCEPT_TASK']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			if($res_u['operator_id']!=$uid)
			{
				// 该配箱已被其他理货员操作，不得再次操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			$sql = "select i.is_must from __PREFIX__dd_plan_container c,__PREFIX__dd_instruction i where c.plan_id=i.plan_id and c.id='$ctn_id'";
			$res_i = M ()->query ( $sql );
			$is_must = $res_i [0] ['is_must'];
			if ($is_must == 'Y')
			{
				// 该箱必须作业
				if (I ( 'post.door_picture' ) == '')
				{
					// 该箱必须实际作业，请拍摄箱门照片
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NEED_CLOSE_DOOR_PICTURE'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_CLOSE_DOOR_PICTURE']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				if (I ( 'post.seal_picture' ) == '')
				{
					// 该箱必须实际作业，请拍摄铅封照片
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NEED_SEAL_PICTURE'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_SEAL_PICTURE']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
			}
				
			// 箱门照片
			if (I ( 'post.door_picture' ))
			{
				$door_picture = I ( 'post.door_picture' );
				$path_d = '.'.IMAGE_DD_DOOR;
				$res_d = base64_upload ( $door_picture, $path_d );
				if ($res_d ['code'] != 0)
				{
					// 文件上传失败
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
							'msg'=>$res_d ['msg']
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				} else {
					$door_file = $path_d.$res_d ['file'];
					$door_img = $res_d ['file'];
				}
			} else {
				$door_img = '';
			}
			// 铅封照片
			if (I ( 'post.seal_picture' ))
			{
				$seal_picture = I ( 'post.seal_picture' );
				$path_s = '.'.IMAGE_DD_SEAL;
				$res_s = base64_upload ( $seal_picture, $path_s );
				if ($res_s ['code'] != 0)
				{
					// 文件上传失败
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
							'msg'=>$res_s ['msg']
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				} else {
					$seal_file = $path_s.$res_s ['file'];
					$seal_img = $res_s ['file'];
				}
			} else {
				$seal_img = '';
			}
				
			//实际铅封号
			if (I ( 'post.true_sealno' ))
			{
				$true_sealno = I ( 'post.true_sealno' );
			} else {
				$true_sealno = '';
			}
				
			//箱残损备注
			if (I ( 'post.damage_remark' ) != '')
			{
				$damage_remark = I ( 'post.damage_remark' );
			} else {
				$damage_remark = '';
			}
			$DdOperation->startTrans();
			$dd_step=json_decode(dd_step,true);
			$data = array (
					'ctn_id' => $ctn_id,
					'door_picture' => $door_img,
					'seal_picture' => $seal_img,
					'true_sealno' => $true_sealno,
					'damage_remark' => $damage_remark,
					'operator_id' => $uid,
					'begin_time' => date('Y-m-d H:i:s'),
					'step'=>$dd_step['check'] //工作步骤-拍完铅封照片
			);
			if(!$DdOperation->create($data))
			{
				// 验证不通过
				// 参数不正确，参数缺失
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
						'msg'=>$DdOperation->getError()
				);
			}else {
				// 验证通过
				// 生成作业记录
				$result = $DdOperation->where("id = $operation_id")->save ( $data );
				if ($result !== false)
				{
					if (I ( 'post.damage_img' ) and is_array(I ( 'post.damage_img' )))
					{
						// 有残损照片
						$damage_image = I ( 'post.damage_img' );
						$path_dam = '.'.IMAGE_DD_DAMAGE;
						foreach ( $damage_image as $d )
						{
							// 上传一张残损图片
							$res_i = base64_upload ( $d, $path_dam );
							if ($res_i ['code'] != 0) 
							{
								// 文件上传失败
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
										'msg'=>$res_i ['msg']
								);
								echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
								exit ();
							} else {
								// start -------------------------------
								// 修改箱状态
								/*$where = array(
									'id'=>$ctn_id
								);
								$arr = array(
									'status'=>1
								);
								if($DdPlanContainer->create($arr)){
									$DdPlanContainer->where($where)->save();
									$DdOperation->commit();
								} else {
									$DdPlanContainer->rollback();
									
								}*/
 								// 提交事务
								 $DdOperation->commit();
 								// end ----------------------------------------
 								
 								
								// 上传成功，保存数据到箱体残损表
								// 上传成功的图片，防止插入残损表失败时回退
								$damage_img [] = $path_dam.$res_i ['file'];
								$data_damage [] = array (
										'operation_id' => $operation_id,
										'img' => $res_i ['file']
								);
							}
							$res_i='';
						}
						// 保存数据到残损表
						$DdCtnDamageImg=new \Common\Model\DdCtnDamageImgModel();
						$res_dam = $DdCtnDamageImg->addAll ( $data_damage );
						if ($res_dam !== false)
						{
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
									'msg' => '成功',
									'operation_id'=>$operation_id
							);
						} else {
							// 需要删除已上传的残损图
							foreach ( $damage_img as $k => $v )
							{
								@unlink ( $v );
							}
							// 删除作业记录
							// 事务回滚
							// $DdOperation->where("id='$operation_id'")->delete();
							$DdOperation->rollback();
							// 删除已上传的照片
							if($door_file)
							{
								@unlink ( $door_file );
							}
							if($seal_file)
							{
								@unlink ( $seal_file );
							}
							// 数据库操作错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}else {
						// 没有残损照片
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功',
								'operation_id'=>$operation_id
						);
					}
				} else {
					// 失败，删除已上传的照片
					if($door_file)
					{
						@unlink ( $door_file );
					}
					if($seal_file)
					{
						@unlink ( $seal_file );
					}
					// 数据库操作错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		} else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 拍摄整箱货物照片
	 * @param int $uid:操作员用户ID
	 * @param int $ctn_id:箱ID
	 * @param string $cargo_picture:整箱照片
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function OpenDoor()
	{
		if (I ( 'post.ctn_id' ) and I ( 'post.uid' ))
		{
			$ctn_id = I ( 'post.ctn_id' );
			$uid = I ( 'post.uid' );
			//检查箱的操作员是否和用户符合，不符合禁止操作
			$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
			$res_u=$DdPlanContainer->where("id=$ctn_id")->field('operator_id')->find();
			if($res_u['operator_id']!=$uid)
			{
				// 该箱已存在操作记录，请勿重复操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['NO_REPEAT_OPERATION'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_REPEAT_OPERATION']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			$sql = "select i.is_must from __PREFIX__dd_plan_container c,__PREFIX__dd_instruction i where c.plan_id=i.plan_id and c.id='$ctn_id'";
			$res_i = M ()->query ( $sql );
			$is_must = $res_i [0] ['is_must'];
			if ($is_must == 'Y')
			{
				// 该箱必须作业
				if (I ( 'post.cargo_picture' ) == '')
				{
					// 该箱必须实际作业，请拍摄整箱货物照片
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NEED_OPEN_DOOR_PICTURE'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_OPEN_DOOR_PICTURE']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
			}
	
			// 整箱照片
			if (I ( 'post.cargo_picture' ))
			{
				$cargo_picture = I ( 'post.cargo_picture' );
				$path_c = '.'.IMAGE_DD_CARGO;
				$res_c = base64_upload ( $cargo_picture, $path_c );
				if ($res_c ['code'] != 0)
				{
					// 文件上传失败
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
							'msg'=>$res_c ['msg']
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}else {
					$cargo_file = $path_c.$res_c ['file'];
					$cargo_img = $res_c ['file'];
				}
			} else {
				$cargo_img = '';
			}
	
			$dd_step=json_decode(dd_step,true);
			$data = array (
					'cargo_picture' => $cargo_img,
					'step' => $dd_step['opened'] //已开门
			);
			$DdOperation=new \Common\Model\DdOperationModel();
			if(!$DdOperation->create($data))
			{
				// 验证不通过
				// 参数不正确，参数缺失
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
						'msg'=>$DdOperation->getDbError()
				);
			}else {
				// 验证通过
				$res_o = $DdOperation->where("ctn_id='$ctn_id'")->save($data);
				if ($res_o !== false)
				{
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
							'msg' => '成功'
					);
				} else {
					// 失败，删除已上传的照片
					if($cargo_file)
					{
						@unlink ( $cargo_file );
					}
					// 数据库操作错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		} else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 获取关列表
	 * @param int ctn_id:箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回关列表
	 */
	public function getLevelList()
	{
		if(I('post.ctn_id'))
		{
			$ctn_id=I('post.ctn_id');
			$sql="select l.* from __PREFIX__dd_operation_level l,__PREFIX__dd_operation o where l.operation_id=o.id and o.ctn_id='$ctn_id' order by level_num asc";
			$list=M()->query($sql);
			if($list!==false)
			{
				$res=array(
						'code'=>0,
						'msg'=>'成功',
						'list'=>$list
				);
			}else{
				// 数据库操作错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'list'=>''
				);
			}
		}else{
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 关操作
	 * @param int operation_id:作业ID
	 * @param int level_num:关数
	 * @param int num:货物件数
	 * @param int damage_num:残损件数
	 * @param array cargo_picture :货物照片
	 * @param array damage_img:货残损图片(数组)
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function OperationLevel()
	{
		if (I ( 'post.operation_id' ) and I ( 'post.num' ) and I ( 'post.level_num' ))
		{
			$level_num = I('post.level_num');
			$operation_id = I ( 'post.operation_id' );
			$num = I ( 'post.num' );
			$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
			// 判断该关是否存在
			$where = array(
					'operation_id' => $operation_id,
					'level_num' => $level_num
				);
			if($DdOperationLevel->where($where)->count()>0){
				$res = array (
						'code' => '1003',
						'msg' => '该关已存在'
					);
				echo json_encode($res);exit;
			}
			$DdOperation=new \Common\Model\DdOperationModel();
			$res_o = $DdOperation->where ( "id=$operation_id" )->field ( 'id,operator_id,ctn_id' )->find ();
			if ($res_o ['id'] == '')
			{
				// 该作业记录不存在，请核实
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_NOT_EXIST'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_NOT_EXIST']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			//箱ID
			$ctn_id=$res_o['ctn_id'];
			//理货员ID
			$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
			$res_operator=$DdPlanContainer->where("id=$ctn_id")->field('operator_id')->find();
			$operator_id=$res_operator['operator_id'];
			if (I ( 'post.damage_num' )) 
			{
				$damage_num = I ( 'post.damage_num' );
			} else {
				$damage_num = 0;
			}
			//计算目前关数

			$level_num=$DdOperationLevel->where("operation_id=$operation_id")->count();
			$data = array (
					'operation_id' => $operation_id,
					'num' => $num,
					'damage_num' => $damage_num,
					'level_num'=>$level_num+1,
					'operator_id'=>$operator_id,
					'createtime'=>date('Y-m-d H:i:s'),
					'blno'   => I('post.blno')
			);

			if(!$DdOperationLevel->create($data))
			{
				// 验证不通过
				// 参数不正确，参数缺失
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
						'msg'=>$DdOperationLevel->getError()
				);
			}else {
				// 验证通过
				$level_id = $DdOperationLevel->add ( $data );
				if ($level_id !== false)
				{
					//存储货物照片
					if(is_array (I('post.cargo_picture')) and I ( 'post.cargo_picture' ))
					{
						$cargo_picture = I ( 'post.cargo_picture' );
						$path_s = '.' . IMAGE_DD_CARGO;
						foreach ( $cargo_picture as $e ) {
							// 上传一张货物图片
							$res_s = base64_upload ( $e, $path_s );
							if ($res_s ['code'] != 0) {
								// 上传失败
								$res = array (
										'code' => $this->ERROR_CODE_COMMON ['FILE_UPLOAD_ERROR'],
										'msg' => $res_s ['msg']
								);
								echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
								exit ();
							} else {
								// 上传成功，保存数据到空箱照片表
								// 上传成功的图片，防止插入空箱照片表失败时回退
								$cargo_img [] = $res_s ['file'];
								$data_cargo [] = array (
										'level_id' => $level_id,
										'level_num' => $level_num + 1,
										'level_img' => $res_s ['file']
								);
							}
							$res_s = '';
						}
						$level_cargo = D('dd_level_cargo_img');
						$res_car = $level_cargo->addAll ( $data_cargo );
						if ($res_car !== false) {
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
									'msg' => '成功',
									'operation_id' => $operation_id
							);
						} else {
							// 需要删除已上传的空箱照片
							foreach ( $cargo_img as $k => $v ) {
								@unlink ( $path_s . $v );
							}
							// 数据库连接错误
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
							);
						}
					}
					if (I ( 'post.damage_img' ) and is_array(I ( 'post.damage_img' )))
					{
						// 存在货物残损
						$damage_image = I ( 'post.damage_img' );
						$path_c = '.'.IMAGE_DD_CDAMAGE;
						foreach ( $damage_image as $d )
						{
							// 上传一张残损图片
							$res_c = base64_upload ( $d, $path_c );
							if ($res_c ['code'] != 0)
							{
								// 文件上传失败
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
										'msg' => $res_c ['msg']
								);
								echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
								exit ();
							} else {
								// 上传成功，保存数据到货物残损表
								// 上传成功的图片，防止插入货残损表失败时回退
								$damage_img [] = $path_c.$res_c ['file'];
								$data_damage1 [] = array (
										'level_id' => $level_id,
										'level_num'=> $level_num+1,
										'img' => $res_c ['file']
								);
							}
							$res_c = '';
						}
						//$DdCargoDamageImg=new \Common\Model\DdCargoDamageImgModel();
						$DdCargoDamageImg = $level_cargo = D('dd_cargo_damage_img');
						$res_car = $DdCargoDamageImg->addAll ( $data_damage1 );
						if ($res_car !== false)
						{
							// 修改工作步骤
							$dd_step=json_decode(dd_step,true);
							$data_s=array(
									'step'=>$dd_step['level'] //录关中
							);
							$DdOperation->where("id='$operation_id'")->save($data_s);
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
									'msg' => '成功',
									'level_num'=>$level_num+1
							);
						} else {
							// 需要删除已上传的货物残损图
							foreach ( $damage_image as $k => $v )
							{
								@unlink ( $v );
							}
							// 删除关记录
							$DdOperationLevel->where("id='$level_id'")->delete();
							// 数据库操作错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}else {
						// 不存在货物残损照片
						// 修改工作步骤
						$dd_step=json_decode(dd_step,true);
						$data_s=array(
								'step'=>$dd_step['level'] //录关中
						);
						$DdOperation->where("id='$operation_id'")->save($data_s);
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功',
								'level_num'=>$level_num+1
						);
					}
				} else {
					// 数据库操作错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		} else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}

	/**
	 * 删除关操作
	 * @param int $uid:用户ID
	 * @param int $operation_id:作业ID
	 * @param int $level_id:关ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function delLevel()
	{
		if (I ( 'post.uid' ) and I ( 'post.operation_id' ) and I ( 'post.level_id' )) 
		{
			$uid = I ( 'post.uid' );
			$operation_id = I ( 'post.operation_id' );
			$level_id = I ( 'post.level_id' );
			$sql = "select o.operator_id from __PREFIX__dd_plan_container c,__PREFIX__dd_operation o where c.id=o.ctn_id and o.id='$operation_id'";
			$res_o=M()->query($sql);
			$operator_id = $res_o [0] ['operator_id'];	
			if ($uid == $operator_id) 
			{
				$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
				$list = $DdOperationLevel->where("id>'$level_id' and operation_id = '$operation_id'")->field('id')->find();
				if ($list['id'] !='') 
				{
					// 该关不是最后一关，请先删除最后一关，再进行操作
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NOT_LAST_LEVEL'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NOT_LAST_LEVEL']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				} else {
					// 同步删除掉作业关中的货物图片
					$level_cargo = D('dd_level_cargo_img');
					$res_s = $level_cargo->where ( "level_id='$level_id'" )->select ();
					if($res_s !== false)
					{
						$res_a = $level_cargo->where ( "level_id='$level_id'" )->delete ();
						if($res_a !== false)
						{
							foreach ($res_s as $vo)
							{
								$img = '.' . IMAGE_DD_CARGO . $l ['level_img'];
								@unlink ( $img );
							}
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
									'msg' => '成功'
							);
						}else{
							// 数据库连接错误
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
							);
						}
					}
					$DdOperationLevel->where ( "id='$level_id'" )->delete ();
					$DdCargoDamageImg=new \Common\Model\DdCargoDamageImgModel();
					$imglist = $DdCargoDamageImg->where ( "level_id='$level_id'" )->select ();
					if ($imglist !== false) 
					{
						// 删除关的货残损图片数据
						$res_d=$DdCargoDamageImg->where ( "level_id=$level_id" )->delete ();
						if($res_d!==false)
						{
							// 获得货残损图片路径，删除图片
							foreach ( $imglist as $l )
							{
								$img = '.' .IMAGE_DD_CDAMAGE. $l ['img'];
								@unlink ( $img );
							}
							// 修改工作步骤
							$dd_step=json_decode(dd_step,true);
							$data_s=array(
									'step'=>$dd_step['level'] //录关中
							);
							$DdOperation=new \Common\Model\DdOperationModel();
							$DdOperation->where("id='$operation_id'")->save($data_s);
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
									'msg' => '成功'
							);
						}else {
							// 数据库操作错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					} else {
						// 数据库操作错误
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}
			} else {
				// 该配箱已被其他理货员操作，不得再次操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
			}
		}else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 取消作业
	 * @param int $uid:用户ID
	 * @param int $ctn_id:箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function cancelOperation()
	{
		if (I ( 'post.uid' ) and I ( 'post.ctn_id' )) 
		{
			$uid = I ( 'post.uid' );
			$ctn_id = I ( 'post.ctn_id' );
			$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
			$res_i = $DdPlanContainer->where ( "id=$ctn_id" )->field ( 'operator_id' )->find ();
			$operator_id = $res_i['operator_id'];
			// 判断用户是否有权限对箱进行操作
			if ($uid == $operator_id) 
			{
				//该箱属于用户，可以对其操作
				$DdOperation=new \Common\Model\DdOperationModel();
				$res_o = $DdOperation->where ( "ctn_id='$ctn_id'" )->field ( 'id' )->find ();
				$operation_id = $res_o ['id'];
				//箱状态
				$ctn_status=json_decode(ctn_status,true);
				if ($operation_id == '') 
				{
					// 只接单尚未作业，只需初始化箱状态与箱操作员
					$data = array (
							'status' => $ctn_status['nostart'],
							'operator_id' => null
					);
					if(!$DdPlanContainer->create($data))
					{
						// 验证不通过
						// 参数不正确，参数缺失
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
								'msg'=>$DdPlanContainer->getError()
						);
					}else {
						// 验证通过
						$res_s = $DdPlanContainer->where("id='$ctn_id'")->save($data);
						if ($res_s !== false)
						{
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
									'msg' => '成功'
							);
						} else {
							// 数据库操作错误
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				} else {
					// 已接单并开始作业
					$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
					$levelnumber = $DdOperationLevel->where ( "operation_id=$operation_id" )->count ();
					if ($levelnumber > 0) 
					{
						// 该作业下有关操作，请先删除关，再继续操作
						$res = array (
								'code' => $this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD'],
								'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD']]
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit();
					} else {
						// 箱下不存在关记录，允许取消
						// 对作业表的删除操作
						$res_d = $DdOperation->where ( "ctn_id=$ctn_id" )->find ();
						$door_picture = $res_d ['door_picture'];
						$seal_picture = $res_d ['seal_picture'];
						$cargo_picture = $res_d ['cargo_picture'];
						$res_w = $DdOperation->where ( "ctn_id=$ctn_id" )->delete ();
						if ($res_w !== false) 
						{
							// 同步删除掉作业表中的几种图片
							if ($door_picture != '') 
							{
								$door_picture = '.' .IMAGE_DD_DOOR. $door_picture;
								@unlink ( $door_picture );
							}
							if ($seal_picture != '') 
							{
								$seal_picture = '.' .IMAGE_DD_SEAL. $seal_picture;
								@unlink ( $seal_picture );
							}
							if ($cargo_picture != '') 
							{
								$cargo_picture = '.' .IMAGE_DD_CARGO. $cargo_picture;
								@unlink ( $cargo_picture );
							}
							// 删除作业前箱残损照片
							$DdCtnDamageImg=new \Common\Model\DdCtnDamageImgModel();
							$imglist = $DdCtnDamageImg->where ( "operation_id=$operation_id" )->select ();
							$res_h = $DdCtnDamageImg->where ( "operation_id=$operation_id" )->delete ();
							if ($res_h !== false) 
							{
								if ($imglist != '') 
								{
									foreach ( $imglist as $l ) 
									{
										$img = '.' .IMAGE_DD_DAMAGE. $l ['img'];
										@unlink ( $img );
									}
								}
							} else {
								// 数据库操作错误
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
								echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
								exit();
							}
							// 初始化箱状态与箱操作员
							$data = array (
									'status' => $ctn_status['nostart'],
									'operator_id' => null
							);
							if(!$DdPlanContainer->create($data))
							{
								// 验证不通过
								// 参数不正确，参数缺失
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
										'msg'=>$DdPlanContainer->getError()
								);
							}else {
								// 验证通过
								$res_c = $DdPlanContainer->where ( "id=$ctn_id" )->save ( $data );
								if ($res_c !== false)
								{
									// 修改工作步骤
									$dd_step=json_decode(dd_step,true);
									$data_s=array(
											'step'=>$dd_step['nostart'] //初始化
									);
									$DdOperation->where("id='$operation_id'")->save($data_s);
										
									$res = array (
											'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
											'msg' => '成功'
									);
								} else {
									// 数据库操作错误
									$res = array (
											'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}
						} else {
							// 数据库操作错误
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			} else {
				// 该配箱已被其他理货员操作，不得再次操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit();
			}
		} else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 完成拆箱作业
	 * @param int $uid:用户ID
	 * @param int $ctn_id:箱ID
	 * @param string $empty_picture:空箱照片
	 * @param string $damage_after_remark:作业中造成的箱残损备注
	 * @param array $damage_after_img:作业中造成的箱残损照片
	 * @param string $remark
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function operationFinish()
	{
		// echo "用户id".I('post.uid')." 箱id ".I('post.ctn_id');
		// exit;
		if(I('post.uid') and I('post.ctn_id'))
		{
			$uid = I('post.uid');
			$ctn_id = I('post.ctn_id');
			$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
			$res_c=$DdPlanContainer->where("id=$ctn_id")->field('operator_id,status')->find();
			// 箱状态
			$ctn_status=json_decode(ctn_status,true);
			// 判断该箱是否已完成，完成不准重复提交
			if($res_c['status']==$ctn_status['finished'])
			{
				// 该箱已完成，请勿重复操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPREATION_FINISHED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPREATION_FINISHED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			$operator_id=$res_c['operator_id'];
			//检查箱的操作员是否和用户符合，不符合禁止操作
			if($uid == $operator_id)
			{
				$DdOperation=new \Common\Model\DdOperationModel();
				$res_d = $DdOperation->where("ctn_id=$ctn_id")->field('id')->find();
				$operation_id = $res_d['id'];
				//判断配箱下是否有关存在，一关没有的情况下不允许完成操作
				$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
				$lnum=$DdOperationLevel->where("operation_id=$operation_id")->count();
				if($lnum==0)
				{
					// 该配箱尚未录关，无法进行完成操作，请先录关
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//该配箱属于操作员，可以进行操作
				$sql = "select i.is_must,i.plan_id,i.id from __PREFIX__dd_plan_container c,__PREFIX__dd_instruction i where c.plan_id=i.plan_id and c.id='$ctn_id'";
				$res_i = M ()->query ( $sql );
				//预报计划ID
				$plan_id = $res_i [0] ['plan_id'];
				//指令ID
				$instruction_id = $res_i [0] ['id'];
				$is_must = $res_i [0] ['is_must'];
				if ($is_must == 'Y')
				{
					// 该箱必须实际作业，空箱照片不能为空
					if (I ( 'post.empty_picture' ) == '')
					{
						// 该箱必须实际作业，请拍摄空箱照片
						$res = array (
								'code' => $this->ERROR_CODE_OPERATION['NEED_EMPTY_CTN_PICTURE'],
								'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_EMPTY_CTN_PICTURE']]
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit ();
					}
				}
					
				if(I('post.empty_picture'))
				{
					$empty_picture = I ( 'post.empty_picture' );
					$path_e = '.'.IMAGE_DD_EMPTY;
					$res_e = base64_upload ( $empty_picture, $path_e );
					if ($res_e ['code'] != 0)
					{
						// 图片上传失败
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
								'msg' => $res_e ['msg']
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit ();
					} else {
						$empty_file = $path_e.$res_e ['file'];
						$empty_img = $res_e ['file'];
					}
				} else {
					$empty_img = '';
				}
					
	
				if(I('post.damage_after_remark'))
				{
					$damage_after_remark=I('post.damage_after_remark');
				}else{
					$damage_after_remark='';
				}
					
				$data=array(
						'empty_picture'=>$empty_img,
						'damage_after_remark'=>$damage_after_remark
				);
				if(!$DdOperation->create($data))
				{
					// 验证不通过
					// 参数不正确，参数缺失
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>$DdOperation->getError()
					);
				}else {
					// 验证通过
					$res_o = $DdOperation->where("ctn_id=$ctn_id")->save($data);
					if($res_o !== false)
					{
						if(I('post.damage_after_img') and is_array(I('post.damage_after_img')))
						{
							//存在作业中造成的残损照片
							$damage_after_img=I('post.damage_after_img');
							$path_s = '.'.IMAGE_DD_DAMAGEAFTER;
							foreach ( $damage_after_img as $d )
							{
								// 上传一张残损图片
								$res_i = base64_upload ( $d, $path_s );
								if ($res_i ['code'] != 0)
								{
									// 图片上传失败
									$res = array (
											'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
											'msg' => $res_i ['msg']
									);
									echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
									exit ();
								} else {
									// 上传成功，保存数据到作业-作业中箱体残损表
									// 上传成功的图片，防止插入箱残损表失败时回退
									$damage_after_file [] = $path_s.$res_i ['file'];
									$data_damage [] = array (
											'operation_id' => $operation_id,
											'img' => $res_i ['file']
									);
								}
								$res_i='';
							}
							$DdCtnDamageAfterImg=new \Common\Model\DdCtnDamageAfterImgModel();
							$res_dam = $DdCtnDamageAfterImg->addAll ( $data_damage );
							if ($res_dam !== false)
							{
								
								// 存在作业中造成的箱残损照片
								// 修改箱的状态为已完成
								$data_s=array(
										'status'=>$ctn_status['finished'] //已完成
								);
								$res_s=$DdPlanContainer->where("id=$ctn_id")->save($data_s);
								if($res_s!==false)
								{
									//生成单证
									
// 									$remark=I('post.remark');
// 									$DdProve=new \Common\Model\DdProveModel();
// 									$res_p=$DdProve->generateDocument($ctn_id, $remark);
// 									if($res_p['code']==0)
// 									{
// 										// 生成单证成功
// 										// 修改工作步骤
// 										$dd_step=json_decode(dd_step,true);
// 										$data_s=array(
// 												'step'=>$dd_step['finished'] //已完成，拍摄空箱照片
// 										);
// 										$DdOperation->where("id='$operation_id'")->save($data_s);
// 										// 判断指令下的配箱是否都已经完成，都完成将指令状态改为完成
// 										$ctn_status_finished=$ctn_status['finished'];
// 										$no_container_num=$DdPlanContainer->where("plan_id=$plan_id and status!='$ctn_status_finished'")->count();
// 										if($no_container_num==0)
// 										{
// 											$instruction_status=json_decode(instruction_status,true);
// 											// 修改指令状态为已完成
// 											$data_i=array(
// 													'status'=>$instruction_status['finish']
// 											);
// 											$DdInstruction=new \Common\Model\DdInstructionModel();
// 											$DdInstruction->where("id=$instruction_id")->save($data_i);
// 										}
										$res=array(
												'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
												'msg'=>'拆箱作业完成！'
										);
// 									}else {
// 										// 生成单证失败
// 										// 将箱状态改回工作中
// 										$data_s2=array(
// 												'status'=>$ctn_status['workin'] //工作中
// 										);
// 										$DdPlanContainer->where("id=$ctn_id")->save($data_s2);
// 										// 需要删除已上传的箱残损图
// 										foreach ( $damage_after_file as $k => $v )
// 										{
// 											@unlink ( $v );
// 										}
// 										$res=$res_p;
// 									}
								}else {
									// 数据库操作错误
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							} else {
								// 需要删除已上传的箱残损图
								foreach ( $damage_after_file as $k => $v )
								{
									@unlink ( $v );
								}
								// 数据库操作错误
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}else {
							// 不存在作业中造成的箱残损照片
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
									'msg' => '成功'
							);
							// 修改箱的状态为已完成
							$data_s=array(
									'status'=>$ctn_status['finished'] //已完成
							);
							$res_s=$DdPlanContainer->where("id=$ctn_id")->save($data_s);
							if($res_s!==false)
							{
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'拆箱作业完成！'
								);
							}else {
								// 将箱状态改回工作中
								$data_s2=array(
										'status'=>$ctn_status['workin'] //工作中
								);
								$DdPlanContainer->where("id=$ctn_id")->save($data_s2);
								// 删除空箱照片
								if($empty_file)
								{
									@unlink ( $empty_file );
								}
								$res=$res_p;
								// 数据库操作错误
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}
					}else{
						// 删除空箱照片
						if($empty_file)
						{
							@unlink($empty_file);
						}
						// 数据库操作错误
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}
			}else{
				// 该配箱已被其他理货员操作，不得再次操作
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
			}
		}else{
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 获取完成箱详情
	 * @param int $ctn_id:箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function getContainerMsg()
	{
		if(I('post.ctn_id'))
		{
			$ctn_id=I('post.ctn_id');
			//根据箱ID获取箱详情
			$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
			$containerMsg=$DdPlanContainer->getContainerMsg($ctn_id);
			//根据箱ID获取作业详情
			$DdOperation=new \Common\Model\DdOperationModel();
			$operationMsg=$DdOperation->getOperationMsgByCtn($ctn_id);
			//根据作业ID获取关列表
			if($operationMsg['id'])
			{
				$operation_id=$operationMsg['id'];
				$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
				$levellist=$DdOperationLevel->getLevelList($operation_id);
			}else {
				$levellist='';
			}
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
					'msg' => '成功！',
					'containerMsg'=>$containerMsg,
					'operationMsg'=>$operationMsg,
					'levellist'=>$levellist
			);
		}else {
			// 参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}

	/**
	 * 补充照片，作业最后一步
	 *
	 * @param int operation_id:作业ID
	 * @param string $supplement_picture:补充照片
	 * @param int ctn_id:箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function supplement_picture() {
		if(I ( 'post.ctn_id' ) and I ( 'post.operation_id' ))
		{
			$ctn_id = I('post.ctn_id');
			if(is_array (I('post.supplement_picture')))
			{
				$operation_id = I('post.operation_id');
				// 补充照片
				if (I ( 'post.supplement_picture' )) {
					$supplement_picture = I ( 'post.supplement_picture' );
					$path_s = '.' . IMAGE_DD_SUPPLEMENT;
					foreach ( $supplement_picture as $e ) {
						// 上传一张空箱图片
						$res_s = base64_upload ( $e, $path_s );
						if ($res_s ['code'] != 0) {
							// 上传失败
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['FILE_UPLOAD_ERROR'],
									'msg' => $res_s ['msg']
							);
							echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
							exit ();
						}else{
							// 上传成功，保存数据到空箱照片表
							// 上传成功的图片，防止插入空箱照片表失败时回退
							$empty_img [] = $res_s ['file'];
							$data_empty [] = array (
									'operation_id' => $operation_id,
									'supplement_picture' => $res_s ['file']
							);
						}
						$res_s = '';
					}
					$ctn_empty = D('dd_supplement_picture');
					$res_car = $ctn_empty->addAll ( $data_empty );
					if ($res_car !== false) {
						//修改箱的状态
						$data = array(
								'status'  =>  2
						);
						$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
						$DdPlanContainer->where("id='$ctn_id'")->save($data);
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
								'msg' => '成功'
						);
					} else {
						// 需要删除已上传的空箱照片
						foreach ( $empty_img as $k => $v ) {
							@unlink ( $path_s . $v );
						}
						// 数据库连接错误
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
						);
					}
				}
			}else{
				$data = array(
						'status'  =>  2
				);
				$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
				$DdPlanContainer->where("id='$ctn_id'")->save($data);
				$res = array(
						'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
						'msg' => '成功',
				);
			}
		}else{
			// 参数缺失 参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	/**
	 * 暂停作业
	 * @param int $uid:用户ID
	 * @param int $ctn_id:箱ID
	 * @param string $tmp_sealno:临时铅封号
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function stopOperation()
	{
		if(I('post.uid') and I('post.ctn_id'))
		{
			$uid=I('post.uid');
			$ctn_id=I('post.ctn_id');
			//检查箱的操作员是否和用户符合，不符合禁止操作
			$operation = new \Common\Model\DdOperationModel();
			$res_u=$operation->where("ctn_id='$ctn_id'")->field('operator_id')->find();
			if ($res_u ['operator_id'] != $uid)
			{
				//该配箱已被其他理货员操作，不得再次操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			} else {
				// 记录临时铅封号,将暂停作业设置为已暂停
				$data_o = array (
						'tmp_sealno' => trim(I ( 'post.tmp_sealno' ),"'"),
						'is_stop' => 'Y'
				);
				$res_o = $operation->where ( "ctn_id='$ctn_id'" )->save ( $data_o );
				if ($res_o !== false)
				{
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
							'msg' => '成功'
					);
				} else {
					//数据库连接错误
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
					);
				}
			}
		}else {
			//参数缺失，参数不正确
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 恢复作业
	 * @param int $uid:用户ID
	 * @param int $ctn_id:箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function resumeOperation()
	{
		if(I('post.uid') and I('post.ctn_id'))
		{
			$uid=I('post.uid');
			$ctn_id=I('post.ctn_id');
			//检查箱的操作员是否和用户符合，不符合禁止操作
			$operation = new \Common\Model\DdOperationModel();
			$res_u=$operation->where("ctn_id='$ctn_id'")->find();
			if($res_u['operator_id']!=$uid)
			{
				//该配箱已被其他理货员操作，不得再次操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}else {
				//判断作业中箱子是否为暂停作业-Y
				$res_c = $operation->where("ctn_id='$ctn_id'")->field('is_stop')->find();
				if($res_c['is_stop']=='Y')
				{
						
					//将暂停作业修改为N不暂停
					$data = array(
							'is_stop'=>'N'
					);
					$res_s=$operation->where("ctn_id=$ctn_id")->save($data);
					if($res_s!==false)
					{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '恢复作业成功'
						);
					}else {
						//数据库连接错误
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}else {
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
							'msg' => '正常工作状态，无需修改！'
					);
				}
			}
		}else {
			//参数缺失，参数不正确
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取提单号 
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 * @param @return code:返回码
	 * @param @return msg:返回码说明
	 * @param @return blno:返回内容
	 */
	public function getblno()
	{
		if(I('post.instruction_id'))
		{
			$instruction_id = trim(I('post.instruction_id'));
			//通过指令ID获取所有的配货提单号
			$result = M('dd_plan_cargo')->alias('ca')->field("ca.blno")
			->join("left join tally_dd_instruction i on i.id='$instruction_id'")
			->join("left join tally_dd_plan p on p.id=i.plan_id")
			->where("ca.plan_id = p.id")
			->select();
			if($result !== false)
			{
				$res = array(
						'code'   =>   $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'    =>   $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
						'blno'   =>   $result
				);
			}else{
				//数据库连接错误
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else{
			//参数缺失，参数不正确
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>