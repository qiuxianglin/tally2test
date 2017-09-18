<?php

/**
 * 起驳装箱业务类
 * 作业管理类
 */

namespace App\Controller;
use App\Common\BaseController;

class QbzxOperationController extends BaseController
{
	public $appaddress = '192.168.1.92';
	
	/**
	 * 获取指令下的配箱列表
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回结果列表
	 */
	public function getContainer()
	{
		if(I('post.instruction_id'))
		{
			$instruction_id = I('post.instruction_id');
			$sql="select c.*,m.ctn_master as cmaster from __PREFIX__qbzx_instruction_ctn c,__PREFIX__container_master m where c.instruction_id='$instruction_id' and c.ctn_master=m.id";
			$list=M()->query($sql);			
			$n = count($list);
			for($i = 0; $i < $n; $i ++)
			{
				$operation = new \Common\Model\QbzxOperationModel();
				$data_o = $operation->where("ctn_id='".$list[$i]['id']."'")->field('id')->find();
				if($data_o['id'])
				{
					$list[$i]['operation_id'] = $data_o['id'];
				}else{
					$list[$i]['operation_id'] = '';
				}
				// 箱状态
				$ctn_status_d = json_decode ( ctn_status_d, true );
				$status_zh = $ctn_status_d [$list [$i] ['status']];
				$list [$i] ['status_zh'] = $status_zh;
			}
			if ($list !== false) {
				$res = array (
						'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
						'msg' => '成功',
						'list' => $list 
				);
			} else {
					$res = array (
					'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list' => ''
			);
			}
		}else{
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['PARAMETER_ERROR']] 
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 获取预报计划下的配货列表
	 * @param plan_id 预报计划ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回结果列表
	 */
	public function getCargoList()
	{
		if(I('post.plan_id'))
		{
			$plan_id = I('post.plan_id');
			$cargo = new \Common\Model\QbzxPlanCargoModel();
			$list = $cargo->where("plan_id='$plan_id'")->select();
			if($list !== false)
			{
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg' => '成功',
						'list' => $list
				);
			}else{
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'list' => ''
				);
			}
		}else{
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
					'list' => ''
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 待办箱列表
	 * @param uid 用户ID
	 * @param status 状态
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
			$sql1 = "select d.instruction_id from __PREFIX__dispatch d,__PREFIX__dispatch_detail dd where d.id = dd.dispatch_id and dd.clerk_id='$uid' and d.business='qbzx' and d.mark!='1'";
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
			// 获取相应状态的箱列表-未开始
			if($status == '1')
			{
				$sql2 = "select c.*,cm.ctn_master from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_instruction_ctn c,__PREFIX__container_master cm where i.id in ($instruction_id) and i.id=c.instruction_id and c.status='0' and c.ctn_master=cm.id order by c.id desc";
				$list = M ()->query ( $sql2 );
				if ($list !== false) {
					$container = new \Common\Model\QbzxInstructionCtnModel ();
					$cnum = count ( $list );
					for($i = 0; $i < $cnum; $i ++) {
						$ctn_id = $list [$i] ['id'];
						$res_c = $container->is_begina ( $ctn_id );
						$list [$i] ['is_stop'] = $res_c ['is_stop'];
						$list [$i] ['is_reservation'] = $res_c ['is_stop'];
						$res_b = $container->is_begin ( $ctn_id );
						if ($res_b === true) {
							$list [$i] ['is_begin'] = 'Y';
						} else {
							$list [$i] ['is_begin'] = 'N';
						}
					}
				}
			}
			
			if($status == '2' or $status == '3')
			{
				$status = $status-1;
				// 获取相应状态的箱列表-工作中、已完成
				$sql3 = "select c.*,cm.ctn_master from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_instruction_ctn c,__PREFIX__container_master cm where i.id=c.instruction_id and cm.id=c.ctn_master and i.id in ($instruction_id)  and c.status='$status' and c.operator_id='$uid' order by c.id desc";
				$list = M ()->query ( $sql3 );
				if ($list !== false) {
					$container = new \Common\Model\QbzxInstructionCtnModel ();
					$cnum = count ( $list );
					for($i = 0; $i < $cnum; $i ++) {
						$ctn_id = $list [$i] ['id'];
						$res_b = $container->is_begin ( $ctn_id );
						if ($res_b === true) {
							$list [$i] ['is_begin'] = 'Y';
						} else {
							$list [$i] ['is_begin'] = 'N';
						}
						$sql = "select l.id from __PREFIX__qbzx_operation o,__PREFIX__qbzx_operation_level l where o.ctn_id='$ctn_id' and o.id=l.operation_id";
						$res_l = M ()->query ( $sql );
						$operation = new \Common\Model\QbzxOperationModel ();
						$res_o = $operation->where ( "ctn_id='$ctn_id'" )->find ();
						if ($res_o ['sealno'] !== null) {
							$list [$i] ['sealno'] = $res_o ['sealno'];
						} else {
							$list [$i] ['sealno'] = '';
						}
						if ($res_o ['cargo_weight'] == null) {
							$list [$i] ['cargo_weight'] = '0';
						} else {
							$list [$i] ['cargo_weight'] = $res_o ['cargo_weight'];
						}
						//审核状态、不通过的原因
						$list [$i] ['operation_examine']  = $res_o['operation_examine'];
						$list [$i] ['examine_remark']     = $res_o['examine_remark'];
							
						$list [$i] ['step'] = $res_o ['step'];
						$list [$i] ['tmp_sealno'] = $res_o ['tmp_sealno'];
						$operation_id = $res_l [0] ['id'];
						$list [$i] ['operation_id'] = $res_o ['id'];
						$list [$i] ['is_stop'] = $res_o ['is_stop'];
						if ($operation_id !== null) {
							$list [$i] ['is_level'] = 'Y';
						} else {
							$list [$i] ['is_level'] = 'N';
						}
					}
				}	
			}
// 			$list = array_merge_recursive ( $list2, $list3 );
			$num = count ( $list );
			for($i = 0; $i < $num; $i ++) {
				// 箱状态
				$ctn_status_d = json_decode ( ctn_status_d, true );
				$status_zh = $ctn_status_d [$list [$i] ['status']];
				$list [$i] ['status_zh'] = $status_zh;
			}
			// 成功
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
					'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['SUCCESS']],
					'list' => $list 
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
	
   /**
    * 理货员接单
    * @param uid 用户ID
    * @param ctn_id 箱ID
    * @return array
    * @return @param code:返回码
    * @return @param msg:返回码说明
    */
	public function ordertaking() {
		if (I ( 'post.uid' ) and I ( 'post.ctn_id' )) {
			$uid = I ( 'post.uid' );
			$ctn_id = I ( 'post.ctn_id' );
			$instruction_id = I('post.instruction_id');
			$container = new \Common\Model\QbzxInstructionCtnModel ();
			$operation = new \Common\Model\QbzxOperationModel ();
			// 获取符合条件的信息
			$res_c = $container->where ( "id=$ctn_id" )->field ( 'operator_id' )->find ();
			$sql = "select c.id from __PREFIX__qbzx_operation o,__PREFIX__qbzx_instruction_ctn c where c.id=o.ctn_id and c.id='$ctn_id' and c.status='1' and o.is_stop!='Y'";
			$res_x = M ()->query ( $sql );
			$n = count ( $res_x );
			if ($n >= 100) {
				// 不能同时接50个以上箱子
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION ['HAVE_THREEOPERATION_CTN'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['HAVE_THREEOPERATION_CTN']] 
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			if ($res_c ['operator_id']) {
				// 该配箱已被其他理货员操作，不得再次操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION ['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['OPERATION_ALREADY_HANDLED']] 
				);
			} else {
				$where = "id=$ctn_id and status = '0' and operator_id is null";
				$data = array (
						'operator_id' => $uid,
						'status' => 1 
				);
				$res_m = $container->where ( $where )->save ( $data );
				if ($res_m !== false) {
					// 判断该箱是否存在作业记录，存在的情况下修改作业的操作人
					$operation = new \Common\Model\QbzxOperationModel ();
					$res_o = $operation->where ( "ctn_id=$ctn_id" )->find ();
					if ($res_o ['id'] != '') {
						// 存在记录，修改操作人
						$data_o = array (
								'operator_id' => $uid 
						);
						$operation->where ( "ctn_id=$ctn_id" )->save ( $data_o );
						$data_c = array (
								'status' => '1' 
						);
						$container->where ( "id='$ctn_id'" )->save ( $data_c );
					} else {
						$back = array (
								'ctn_id' => $ctn_id,
								'operator_id' => $uid,
								'begin_time' => date ( 'Y-m-d H:i:s' ) 
						);
						if (! $operation->create ( $back )) {
							// 对back数据进行验证
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['PARAMENT_ERROR'],
									'msg' => $operation->getError () 
							);
						} else {
							// 验证通过 可以对数据进行操作
							$operation->add ( $back );
						}
					}

					// 成功
					$res_o = $operation->where ( "ctn_id=$ctn_id" )->find ();
					$operationlevel = new \Common\Model\QbzxOperationLevelModel();
					$operationlevelnum = $operationlevel->sumLevelNum($res_o['id']);
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
							'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['SUCCESS']],
							'step'    => $res_o['step'],
							'level_num'   => $operationlevelnum
					);
				} else {
					// 数据库连接错误
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
					);
				}
			}
		} else {
			// 参数缺失，参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['PARAMETER_ERROR']] 
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
   
   
   /**
    * 起驳装箱作业信息核对
    * @param int $uid:操作员用户ID
    * @param int $ctn_id:箱ID
    * @param string $empty_picture:空箱照片
    * @param array  $empty_weight:空箱重量
    * @return array
    * @return @param code:返回码
    * @return @param msg:返回码说明
    * @return @param operation_id:作业ID
    */
   public function OperationCheck() 
   {
		if (I ( 'post.ctn_id' ) and I ( 'post.uid' ) and I ( 'post.empty_weight' ) and is_array ( I ( 'post.empty_picture' ) )) 
		{
			$ctn_id = I ( 'post.ctn_id' );
			$uid = I ( 'post.uid' );
			$empty_weight = I ( 'post.empty_weight' );
			// 检查该箱的操作员是否为本人，不是则中止操作
			
			$operation = new \Common\Model\QbzxOperationModel ();
			
			$container = new \Common\Model\QbzxInstructionCtnModel ();
			$res_u = $container->where ( "id='$ctn_id'" )->field ( 'operator_id' )->find ();
			if ($res_u ['operator_id'] == '') 
			{
				//该箱尚无操作人员，请先接单！
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION ['NEED_ACCEPT_TASK'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['NEED_ACCEPT_TASK']] 
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			if ($res_u ['operator_id'] != $uid) 
			{
				//该配箱已被其他理货员操作，不得再次操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION ['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['OPERATION_ALREADY_HANDLED']] 
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			$qbzx_step=json_decode(qbzx_step,true);
			$data = array (
					'empty_weight' => $empty_weight,
					'step' => $qbzx_step['check'] 
			);
			if(!$operation->create($data))
			{
				//对data数据进行验证
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
						'msg'=>$operation->getError()
				);
			}else{
				//验证通过 可以对数据进行操作
				$operation->where ( "ctn_id='$ctn_id'" )->save ( $data );
				$res_i = $operation->where ( "ctn_id='$ctn_id'" )->field ( 'id' )->find ();
				$operation_id = $res_i ['id'];
				if ($operation_id !== false)
				{
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
							'msg' => '成功',
							'operation_id' => $operation_id
					);
					// 空箱照片
					if (I ( 'post.empty_picture' ))
					{
						$empty_picture = I ( 'post.empty_picture' );
						$path_s = '.'.IMAGE_QBZX_EMPTY;
						foreach ( $empty_picture as $e )
						{
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
							} else {
								// 上传成功，保存数据到空箱照片表
								// 上传成功的图片，防止插入空箱照片表失败时回退
								$empty_img [] = $res_s ['file'];
								$data_empty [] = array (
										'operation_id' => $operation_id,
										'empty_picture' => $res_s ['file']
								);
							}
							$res_s = '';
						}
						$ctn_empty = new \Common\Model\QbzxEmptyCtnImgModel ();
						$res_car = $ctn_empty->addAll ( $data_empty );
						if ($res_car !== false)
						{
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
									'msg' => '成功',
									'operation_id' => $operation_id
							);
						} else {
							// 需要删除已上传的空箱照片
							foreach ( $empty_img as $k => $v )
							{
								@unlink ($path_s.$v);
							}
							//数据库连接错误
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
							);
						}
					} else {
						//请拍摄空箱照片
						$res = array (
								'code' => $this->ERROR_CODE_OPERATION ['NEED_EMPTY_CTN_PICTURE'],
								'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['NEED_EMPTY_CTN_PICTURE']]
						);
					}
				} else {
					//数据库连接错误
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
					);
				}
			}
			
		} else {
			//参数缺失 参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['PARAMETER_ERROR']] 
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
			$sql = "select l.* from __PREFIX__qbzx_operation_level l,__PREFIX__qbzx_operation o where l.operation_id=o.id and o.ctn_id='$ctn_id'";
			$list = M ()->query ( $sql );
		    $n = count($list);
		    //返回运输方式
		    for($i=0;$i<$n;$i++)
		    {
		    	if($list[$i]['ship_id'] != '0')
		    	{
		    		$list[$i]['transport'] = '0';
		    	}else if($list[$i]['location_id'] != '0'){
		    		$list[$i]['transport'] = '1';
		    	}else{
		    		$list[$i]['transport'] = '2';
		    	}
		    }
			if ($list !== false)
			{
				$res = array (
						'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
						'msg' => '成功',
						'list' => $list
				);
			} else {
				//数据库连接错误
				$res = array (
						'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']],
						'list' => ''
				);
			}
		}else{
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
	 * @param int cargo_number:货物件数
	 * @param int cargo_picture:货照片
	 * @param int damage_num:残损件数
	 * @param array damage_img:货残损图片
	 * @param array billno:提单号
	 * @param array transport:运输方式
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function OperationLevel() 
	{
// 		sleep(10);
		if (I ( 'post.level_num' ) and I ( 'post.operation_id' ) and I ( 'post.cargo_number' ) !== '' and I ( 'post.billno' ))
		{
			$operation_id = I ( 'post.operation_id' );
			// 判断该关是否存在 已存在则不允许覆盖
			$level = new \Common\Model\QbzxOperationLevelModel ();
			$where = array(
					'operation_id' => $operation_id,
					'level_num' => I('post.level_num')
				);
			if($level->where($where)->count()>0){
				$res = array (
						'code' => '0',
						'msg'  =>  '成功'
					);
				echo json_encode($res);exit;
			}
			$blno = I ( 'post.billno' );
			
			$cargo_number = ( int ) I ( 'post.cargo_number' );
			$operation = new \Common\Model\QbzxOperationModel ();
			$res_o = $operation->where ( "id='$operation_id'" )->field ( 'id,operator_id,ctn_id' )->find ();
			if ($res_o ['id'] == '') 
			{
				//该作业不存在
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION ['OPERATION_NOT_EXIST'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['OPERATION_NOT_EXIST']] 
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			// 箱ID
			$ctn_id = $res_o ['ctn_id'];
			// 理货员ID
			$container = new \Common\Model\QbzxInstructionCtnModel ();
			$res_operator = $container->where ( "id='$ctn_id'" )->field ( 'operator_id' )->find ();
			$operator_id = $res_operator ['operator_id'];
			if (I ( 'post.damage_num' )) 
			{
				$damage_num = I ( 'post.damage_num' );
			} else {
				$damage_num = 0;
			}
			
			$transport = I ( 'post.transport' );
			switch ($transport) 
			{
				case '0' :
					$ship_id = I ( 'post.ship_id' );
					$location_id = '';
					$car = '';
					break;
				case '1' :
					$location_id = I ( 'post.location_id' );
					$ship_id = '';
					$car = '';
					break;
				case '2' :
					$car = I ( 'post.car' );
					$ship_id = '';
					$location_id = '';
					break;
				default :
					$ship_id = '';
					$location_id = '';
					$car = '';
			}
			$remark = I ( 'post.remark' );
			// 计算目前关数
			
			$level_num = $level->where ( "operation_id='$operation_id'" )->count ();
			$data = array (
					'operation_id' => $operation_id,
					'cargo_number' => $cargo_number,
					'damage_num' => $damage_num,
					'damage_explain' => I ( 'post.damage_explain' ),
					'ship_id' => $ship_id,
					'location_id' => $location_id,
					'car' => $car,
					'level_num' => $level_num + 1,
					'billno' => $blno,
					'comment' => $remark,
					'operator_id' => $operator_id,
					'createtime' => date ( 'Y-m-d H:i:s' ) 
			);
			if(!$level->create($data))
			{
				//对data数据进行验证
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
						'msg'=>$level->getError()
				);
			}else{
				//验证通过 可以对数据进行操作
				$level_id = $level->add ( $data );
				if ($level_id !== false)
				{
					if(is_array (I('post.cargo_picture')))
					{
						// 货物照片
						if (I ( 'post.cargo_picture' )) {
							$cargo_picture = I ( 'post.cargo_picture' );
							$path_s = '.' . IMAGE_QBZX_CARGO;
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
											'cargo_picture' => $res_s ['file']
									);
								}
								$res_s = '';
							}
							$level_cargo = D('qbzx_level_cargo_img');
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
					}
					if (I ( 'post.damage_img' ))
					{
						$damage_img = I ( 'post.damage_img' );
						$path_c = '.'.IMAGE_QBZX_CDAMAGE;
						foreach ( $damage_img as $d ) {
							// 上传一张残损图片
							$res_c = base64_upload ( $d, $path_c );
							if ($res_c ['code'] != 0) {
								// 上传失败
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
										'msg' => $res_c ['msg']
								);
								echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
								exit ();
							} else {
								// 上传成功，保存数据到货物残损表
								// 上传成功的图片，防止插入货残损表失败时回退
								$damage_img [] = $res_c ['file'];
								$data_damage [] = array (
										'level_id' => $level_id,
										'level_num' => $level_num + 1,
										'damage_picture' => $res_c ['file']
								);
							}
							$res_c = '';
						}
						$cargo_damage = new \Common\Model\QbzxLevelDamageImgModel ();
						$res_car = $cargo_damage->addAll ( $data_damage );
						if ($res_car !== false)
						{
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
									'msg' => '成功',
									'level_num' => $level_num + 1
							);
						} else {
							// 需要删除已上传的货物残损图
							foreach ( $damage_img as $k => $v ) {
								@unlink ( $path_c.$v );
							}
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
							);
						}
					} else {
						$qbzx_step=json_decode(qbzx_step,true);
						$data_o = array (
								'step' => $qbzx_step['levelin']
						);
						$operation->where ( "id='$operation_id'" )->save ( $data_o );
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
								'msg' => '成功',
								'level_num' => $level_num + 1
						);
					}
				} else {
					//需要删除已删除上传的货照片
					@unlink($path_s.$cargo_img);
					//数据库连接错误
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
					);
				}
			}
		} else {
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['PARAMETER_ERROR']] 
			);
		}
		
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
		
	}
	
	
	/**
	 * 半关门
	 * @param int $uid:用户ID
	 * @param int $ctn_id:箱ID
	 * @param array $halfclose_door_picture:半关门照片
	 */
	public function halfclosedoor(){
		if(I('post.uid') and I('post.ctn_id') and I('post.halfclose_door_picture'))
		{
			$uid = I('post.uid');
			$ctn_id = I('post.ctn_id');
			$container=new \Common\Model\QbzxInstructionCtnModel();
			$res_c=$container->where("id=$ctn_id")->find();
			$operator_id=$res_c['operator_id'];
			//检查箱的操作员是否和用户符合，不符合禁止操作
			if($uid == $operator_id)
			{
				$operation=new \Common\Model\QbzxOperationModel();
				$res_d = $operation->where("ctn_id=$ctn_id")->field('id')->find();
				$operation_id = $res_d['id'];
				//判断配箱下是否有关存在，一关没有的情况下不允许完成操作
				$level = new \Common\Model\QbzxOperationLevelModel();
				$lnum=$level->where("operation_id='$operation_id'")->count();
				if($lnum==0)
				{
					// 该箱没有录关，不能进行关门操作
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD'] ,
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//该配箱属于操作员，可以进行操作
				$sql = "select i.id from __PREFIX__qbzx_instruction_ctn c,__PREFIX__qbzx_instruction i where c.instruction_id=i.id and c.id='$ctn_id'";
				$res_i = M ()->query ( $sql );
				//指令ID
				$instruction_id = $res_i [0] ['id'];
				// 半关门照片不能为空
				if (I ( 'post.halfclose_door_picture' ) == '')
				{
					//需要拍摄半关门照
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NEED_HALFCLOSE_DOOR_PICTURE'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_HALFCLOSE_DOOR_PICTURE']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//半关门照片
				if(I('post.halfclose_door_picture'))
				{
					$halfclose_door_picture = I ( 'post.halfclose_door_picture' );
					$path_h = '.'.IMAGE_QBZX_HALFCLOSEDOOR;
					$res_h = base64_upload ( $halfclose_door_picture, $path_h );
					if ($res_h ['code'] != 0)
					{
						//图片上传失败
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
								'msg' => $res_h ['msg']
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit ();
					} else {
						$halfclose_door_file = $res_h ['file'];
						$halfclose_door_img = $res_h ['file'];
					}
				} else {
					$halfclose_door_img = '';
				}
				$qbzx_step=json_decode(qbzx_step,true);
				$data=array(
						'halfclose_door_picture'=>$halfclose_door_img,
						'step'=>$qbzx_step['halfclosedoor']
				);
				$res_o = $operation->where("ctn_id=$ctn_id")->save($data);
				if($res_s !== false)
				{
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功'
					);
				}else{
					//需要对已上传的图片进行删除
					@unlink($path_h.$halfclose_door_img);
					//数据库连接错误
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}else{
				//该配箱已被其他理货员操作，不得再次操作
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
			}
		}else{
			//参数缺失，参数不正确
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	/**
	 * 关门
	 * @param int $uid:用户ID
	 * @param int $ctn_id:箱ID
	 * @param array $close_door_picture:全关门照片
	 */
	public function closeDoor()
	{
		if(I('post.uid') and I('post.ctn_id') and I('post.close_door_picture'))
		{
			$uid = I('post.uid');
			$ctn_id = I('post.ctn_id');
			$container=new \Common\Model\QbzxInstructionCtnModel();
			$res_c=$container->where("id=$ctn_id")->find();
			$operator_id=$res_c['operator_id'];
			//检查箱的操作员是否和用户符合，不符合禁止操作
			if($uid == $operator_id)
			{
				$operation=new \Common\Model\QbzxOperationModel();
				$res_d = $operation->where("ctn_id=$ctn_id")->field('id')->find();
				$operation_id = $res_d['id'];
				//判断配箱下是否有关存在，一关没有的情况下不允许完成操作
				$level = new \Common\Model\QbzxOperationLevelModel();
				$lnum=$level->where("operation_id='$operation_id'")->count();
				if($lnum==0)
				{
					// 该箱没有录关，不能进行关门操作
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//该配箱属于操作员，可以进行操作
				$sql = "select i.id from __PREFIX__qbzx_instruction_ctn c,__PREFIX__qbzx_instruction i where c.instruction_id=i.id and c.id='$ctn_id'";
				$res_i = M ()->query ( $sql );
				//指令ID
				$instruction_id = $res_i [0] ['id'];
				//全关门照片不能为空
				if (I('post.close_door_picture') == '')
				{
					//需要拍摄全关门照
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NEED_CLOSE_DOOR_PICTURE'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_CLOSE_DOOR_PICTURE']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//全关门照片
				if(I('post.close_door_picture'))
				{
					$close_door_picture = I ( 'post.close_door_picture' );
					$path_c = '.'.IMAGE_QBZX_CLOSEDOOR;
					$res_c = base64_upload ( $close_door_picture, $path_c );
					if ($res_c ['code'] != 0)
					{
						//图片上传失败
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
								'msg' => $res_c ['msg']
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit ();
					} else {
						$close_door_file = $res_c ['file'];
						$close_door_img = $res_c ['file'];
					}
				} else {
					$close_door_img = '';
				}
				$qbzx_step=json_decode(qbzx_step,true);
				$data=array(
						'close_door_picture'=>$close_door_img,
						'step'=>$qbzx_step['closedoor']
				);
				$res_o = $operation->where("ctn_id=$ctn_id")->save($data);
				if($res_s !== false)
				{
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功'
					);
				}else{
					//需要对已上传的图片进行删除
					@unlink($path_c.$close_door_img);
					//数据库连接错误
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}else{
				//该配箱已被其他理货员操作，不得再次操作
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
			}
		}else{
			//参数缺失，参数不正确
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	
	/**
	 * 完成起驳装箱作业
	 * @param int $uid:用户ID
	 * @param int $ctn_id:箱ID
	 * @param array $sealno:铅封号
	 * @param array $seal_picture:铅封照片
	 * @param array $cargo_weight:货物重量
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function operationFinish()
	{
		if(I('post.uid') and I('post.ctn_id') and I('post.sealno') and I('post.cargo_weight'))
		{
			$uid = I('post.uid');
			$ctn_id = I('post.ctn_id');
			$sealno = I('post.sealno');
			$cargo_weight = I('post.cargo_weight');
			$container=new \Common\Model\QbzxInstructionCtnModel();
			$res_c=$container->where("id=$ctn_id")->field('operator_id,status')->find();
			//判断该箱是否已完成，完成不准重复提交
			if($res_c['status']=='2')
			{
				//该箱已完成，请勿重复操作！
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
				$operation=new \Common\Model\QbzxOperationModel();
				$res_d = $operation->where("ctn_id=$ctn_id")->field('id')->find();
				$operation_id = $res_d['id'];
				//判断配箱下是否有关存在，一关没有的情况下不允许完成操作
				$level = new \Common\Model\QbzxOperationLevelModel();
				$lnum=$level->where("operation_id='$operation_id'")->count();
				if($lnum==0)
				{
					// 该箱没有录关，不能进行完成操作
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//该配箱属于操作员，可以进行操作
				$sql = "select i.id from __PREFIX__qbzx_instruction_ctn c,__PREFIX__qbzx_instruction i where c.instruction_id=i.id and c.id='$ctn_id'";
				$res_i = M ()->query ( $sql );
				//指令ID
				$instruction_id = $res_i [0] ['id'];
				// 铅封照片不能为空
				if (I ( 'post.seal_picture' ) == '')
				{
					//请拍摄铅封照
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NEED_SEAL_PICTURE'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_SEAL_PICTURE']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				if(I('post.seal_picture'))
				{
					$seal_picture = I ( 'post.seal_picture' );
					$path_e = '.'.IMAGE_QBZX_SEAL;
					$res_e = base64_upload ( $seal_picture, $path_e );
					if ($res_e ['code'] != 0)
					{
						//图片上传失败
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
								'msg' => $res_e ['msg']
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit ();
					} else {
						$seal_file = $res_e ['file'];
						$seal_img = $res_e ['file'];
					}
				} else {
					$seal_img = '';
				}
				$qbzx_step=json_decode(qbzx_step,true);
				$data=array(
						'sealno'=>$sealno,
						'seal_picture'=>$seal_img,
						'cargo_weight'=>$cargo_weight,
						'step'=>$qbzx_step['finished']
				);
				if(!$operation->create($data))
				{
					//对data数据进行验证
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMENT_ERROR'],
							'msg'=>$operation->getError()
					);
				}else{
					//验证通过 可以对数据进行验证
					$res_o = $operation->where("ctn_id=$ctn_id")->save($data);
					//修改箱的状态为已完成
					$data_s=array(
							'status'=>'2'
					);
					$res_s=$container->where("id='$ctn_id'")->save($data_s);
					if($res_s!==false)
					{
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'起驳装箱作业完成！'
						);
					}
				}
			}else{
				//该配箱已被其他理货员操作，不得再次操作
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
			}
		}else{
			//参数缺失 参数不正确
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
			$container=new \Common\Model\QbzxInstructionCtnModel();
			$containerMsg=$container->getContainerMsg($ctn_id);
			//根据箱ID获取作业详情
			$operationModel=new \Common\Model\QbzxOperationModel();
			$operationMsg=$operationModel->getOperationMsg($ctn_id);
			//根据作业ID获取关列表
			if($operationMsg['id'])
			{
				$operation_id=$operationMsg['id'];
				$level=new \Common\Model\QbzxOperationLevelModel();
				$levellist=$level->getLevelList($operation_id);
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
			//参数缺失，参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
		
	/**
	 * 新增配箱
	 * @param uid 用户ID
	 * @param instruction_id 指令ID
	 * @param ctnno 箱号
	 * @param ctn_type_code 箱型尺寸
	 * @param ctn_master 箱主
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function addContainer()
	{
		if(I('post.instruction_id') and I('post.uid'))
		{
			$uid = I ( 'post.uid' );
			$instruction_id = I ( 'post.instruction_id' );
			$sql = "select s.shift_master from __PREFIX__shift s,__PREFIX__qbzx_instruction i where i.id='$instruction_id' and i.department_id=s.department_id";
			$res_i = M ()->query ( $sql );
			$shift_master = $res_i [0] ['shift_master'];
			if ($shift_master == $uid)
			{
				//箱型尺寸
				$container=new \Common\Model\ContainerModel();
				$containerlist=$container->getContainerList();
				$this->assign('contanierlist',$containerlist);
				//箱主
				$containerMaster=new \Common\Model\ContainerMasterModel();
				$cmlist=$containerMaster->getContainerMasterList();
				$this->assign('cmlist',$cmlist);
	
				if(I('post.'))
				{
					if(I('post.ctnno'))
					{
						$ctnno=strtoupper(I('post.ctnno'));
					}else {
						$this->error('箱号不能为空！');
					}
					$data=array(
							'instruction_id'=>$instruction_id,
							'ctnno'=>$ctnno,
							'ctn_type_code'=>I('post.ctn_type_code'),
							'ctn_master'=>I('post.ctn_master'),
							'operator_id'=>$uid,
							'status'=>'0'
					);
					$instructionContainer=new \Common\Model\QbzxInstructionCtnModel();
					$res=$instructionContainer->add($data);
					if($res !== false)
					{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功'
						);
					}else{
						//数据库连接错误
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}
			}else{
				//需要理货长权限
				$res = array (
						'code' => $this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY'],
						'msg' => $this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY']]
				);
			}
		}else{
			//参数缺失 参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 删除配箱
	 * @param uid 用户ID
	 * @param ctn_id 箱ID
	 * @param instruction_id 指令ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function delContainer()
	{
		if (I ( 'post.ctn_id' ) and I ( 'post.uid' ) and I ( 'post.instruction_id' ))
		{
			$uid = I ( 'post.uid' );
			$instruction_id = I ( 'post.instruction_id' );
			$sql = "select s.shift_master from __PREFIX__shift s,__PREFIX__qbzx_instruction i where i.id='$instruction_id' and i.department_id=s.department_id";
			$res_i = M ()->query ( $sql );
			$shift_master = $res_i [0] ['shift_master'];
			if ($shift_master == $uid)
			{
				$ctn_id = I ( 'post.ctn_id' );
				$instructionContainer = new \Common\Model\QbzxInstructionCtnModel ();
				$res_c = $instructionContainer->where ( "id='$ctn_id'" )->field ( 'status' )->find ();
				$status = $res_c ['status'];
				if ($status == '0' or $status == '-1')
				{
					$res = $instructionContainer->where ( "id='$ctn_id'" )->delete ();
					if ($res !== false)
					{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功'
						);
					} else {
						//数据库连接错误
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				} else {
					//该箱已作业，不能删除
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['CTN_OPERATION_NOTDEL'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['CTN_OPERATION_NOTDEL']]
					);
				}
			} else {
				//需要理货长权限
				$res = array (
						'code' => $this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY'],
						'msg' => $this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY']]
				);
			}
		} else {
			//参数缺失，参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取提单号列表
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 * @param @return code:返回码
	 * @param @return msg:返回码说明
	 */
	public function billnolist() {
		if(I('post.instruction_id'))
		{
			$instruction_id = I ( 'post.instruction_id' );
			$instruction = new \Common\Model\QbzxInstructionModel();
			$res_i = $instruction->where("id='$instruction_id'")->field('plan_id')->find();
			$plan_id = $res_i['plan_id'];
			$cargo = new \Common\Model\QbzxPlanCargoModel ();
			$list = $cargo->where ( "plan_id='$plan_id'" )->select ();
			if($list !== false)
			{
				$billno = count ( $list );
				$ship = new \Common\Model\ShipModel ();
				for($i = 0; $i < $billno; $i ++) {
					$ship_id = $list [$i] ['ship_id'];
					if ($ship_id != '') {
						$res_s= $ship->where ( "id in ($ship_id)" )->field ( 'id,ship_code,ship_name' )->select ();
						$list[$i]['shiplist']=$res_s;
					}else{
						$list[$i]['shiplist']='';
					}
				}
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'list'=>$list
				);
			}else{
				//数据库连接错误
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'list'=>''
				);
			}
		}else{
			//参数缺失，参数不正确
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 装箱要求
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param loadingrequire:装箱要求 
	 */
	public function packingRequire() {
		if(I('post.instruction_id'))
		{
			$instruction_id = I ( 'post.instruction_id' );
			$instruction = new \Common\Model\QbzxInstructionModel();
			$ins = $instruction->where ( "id='$instruction_id'" )->find ();
			$plan = new \Common\Model\QbzxPlanModel();
			$res_p = $plan->where ( "id='" . $ins ['plan_id'] . "'" )->find ();
			
			if ($res_p) {
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'loadingrequire' => $res_p ['packing_require']
				);
			} else {
				//无装箱要求
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['NO_LOADINGREQUIRE'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_LOADINGREQUIRE']]
				);
			}
		}else{
			//参数缺失，参数不正确
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMENT_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMENT_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 根据指令ID查询配货信息
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 * @param @return code:返回码
	 * @param @return msg:返回码说明
	 * @param @return content:返回内容
	 */
	public function getPlanCargo()
	{
		if(I('post.instruction_id'))
		{
			$instruction_id=I('post.instruction_id');
			//根据指令ID获取预报ID
			$instruction = new \Common\Model\QbzxInstructionModel();
			$res_p=$instruction->where("id='$instruction_id'")->find();
			if($res_p['plan_id'])
			{
				$plan_id=$res_p['plan_id'];
				//根据预报计划ID获取配货信息
				$cargo = new \Common\Model\QbzxPlanCargoModel();
				$res_b=$cargo->where("plan_id='$plan_id'")->field('billno,ship_id,location_id')->select();
				$num=count($res_b);
				$ship=new \Common\Model\ShipModel();
				$location=new \Common\Model\LocationModel();
				for($i=0;$i<$num;$i++)
				{
				    $billno=$res_b[$i]['billno'];
					// 获取驳船列表
					$ship_id = $res_b [$i] ['ship_id'];
					if ($ship_id != '') 
					{
						$res_s = $ship->where ( "id='$ship_id'" )->field ( 'id,ship_code,ship_name' )->select ();
						$res_b [$i] ['shiplist'] = $res_s;
					} else {
						$res_b [$i] ['shiplist'] = '';
						$res_b [$i] ['ship_id'] = '';
					}
					// 获取来源场地列表
					$location_id = $res_b [$i] ['location_id'];
					if ($location_id != '') 
					{
						$res_l = $location->where ( "id='$location_id'" )->field ( 'id,location_code,location_name' )->select ();
						$res_b [$i] ['locationlist'] = $res_l;
					} else {
						$res_b [$i] ['locationlist'] = '';
						$res_b [$i] ['location_id'] = '';
					}
				}
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'content'=>$res_b
				);
			}else {
				//预报计划不存在
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['PLAN_NOT_EXIST'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['PLAN_NOT_EXIST']]
				);
			}
		}else {
			//参数缺失，参数不正确
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMENT_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMENT_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 根据提单号查询驳船
	 * @param string $billno:提单号
	 * @return array
	 */
	public function getShipListByBill()
	{
		if(I('post.billno'))
		{
			$billno=I('post.billno');
			$cargo = new \Common\Model\QbzxPlanCargoModel();
			$res_b = $cargo->where("billno='$billno'")->field('ship_id')->find();
			if($res_b!==false)
			{
				//根据提单号查询驳船ID
				$ship_id=$res_b['ship_id'];
				$ship=new \Common\Model\ShipModel();
				if($ship_id!='')
				{
					$res_s=$ship->where("id in ($ship_id)")->field('id,ship_code,ship_name')->select();
					$shiplist=$res_s;
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'content'=>$res_s
					);
				}else {
					//没有该驳船
					$res=array(
							'code'=>$this->ERROR_CODE_OPERATION['NO_BARSE'],
							'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_BARSE']],
							'content'=>''
					);
				}
			}else {
				//数据库连接错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
				);
			}
		}else {
			//参数缺失，参数不正确
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMENT_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMENT_ERROR']],
					'content'=>''
			);
		}
		echo json_encode ( $res );
	}
	
	/**
	 * 根据提单号查询来源场地
	 * @param string $billno:提单号
	 * @return array|boolean
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param content:返回说明
	 */
	public function getLocationListByBill()
	{
		if(I('post.billno'))
		{
			$billno=I('post.billno');
			$cargo = new \Common\Model\QbzxPlanCargoModel();
			$res_b = $cargo->where("billno='$billno'")->field('location_id')->find();
			if($res_b!==false)
			{
				//根据提单号查询来源场地ID
				$location_id=$res_b['location_id'];
				$location=new \Common\Model\LocationModel();
				if($location_id!='')
				{
					$res_l=$location->where("id in ($location_id)")->field('id,location_code,location_name')->select();
					$locationlist=$res_l;
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'content'=>$locationlist
					);
				}else {
					//作业场地不存在
					$res=array(
							'code'=>$this->ERROR_CODE_OPERATION['NO_LOCATION'],
							'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_LOCATION']],
							'content'=>''
					);
				}
			}else {
				//数据库连接错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
				);
			}
		}else {
			//参数缺失，参数不正确
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMENT_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMENT_ERROR']],
					'content'=>''
			);
		}
		echo json_encode ( $res );
	}
	
	
	/**
	 * 箱残损
	 * @param int $uid 用户ID
	 * @param int $ctn_id 箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function cancelContainer()
	{
		if (I ( 'post.uid' ) and I ( 'post.ctn_id' ))
		{
			$uid = I ( 'post.uid' );
			$ctn_id = I ( 'post.ctn_id' );
			$container = new \Common\Model\QbzxInstructionCtnModel();
			$res_i = $container->where ( "id='$ctn_id'" )->field ( 'operator_id' )->find ();
			$operator_id = $res_i['operator_id'];
			// 判断用户是否有权限对箱进行操作
			if ($uid == $operator_id)
			{
				//该箱属于用户，可以对其操作
				$operation = new \Common\Model\QbzxOperationModel();
				$res_o = $operation->where ( "ctn_id='$ctn_id'" )->field ( 'id' )->find ();
				$operation_id = $res_o ['id'];
				if ($operation_id == '')
				{
					// 只接单尚未作业，只需初始化箱状态与箱操作员
					$data = array (
							'status' => '-1',
							'operator_id' => null
					);
					$res_s = $container->where("id='$ctn_id'")->save($data);
					if ($res_s !== false)
					{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']]
						);
					} else {
						//数据库连接错误
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				} else {
					// 已接单并开始作业
					$level = new \Common\Model\QbzxOperationLevelModel();
					$levelnumber = $level->where ( "operation_id=$operation_id" )->count ();
					if ($levelnumber > 0)
					{
						// 箱下存在关记录，不允许取消
						$res = array (
								'code' => $this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD'],
								'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD']]
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit();
					} else {
						// 箱下不存在关记录，允许取消
						// 对作业表的删除操作
						$res_d = $operation->where ( "ctn_id='$ctn_id'" )->find ();
						$empty_picture = $res_d ['empty_picture'];
						$res_w = $operation->where ( "ctn_id='$ctn_id'" )->delete ();
						if ($res_w !== false)
						{
							// 同步删除掉作业表中的几种图片
							if ($empty_picture != '')
							{
								$empty_picture = '.' . $empty_picture;
								@unlink ( $empty_picture );
							}
							// 初始化箱状态与箱操作员
							$data = array (
									'status' => '-1',
									'operator_id' => null
							);
							if($container->create($data))
							{
								//对data数据进行验证
								$res = array(
										'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
										'msg'=>$container->getError()
								);
							}else{
								//验证通过  可以对数据进行操作
								$res_c = $container->where ( "id=$ctn_id" )->save ( $data );
								if ($res_c !== false)
								{
									$res = array (
											'code' =>$this->ERROR_CODE_COMMON['SUCCESS'],
											'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']]
									);
								} else {
									//数据库连接错误
									$res = array (
											'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}
						} else {
							//数据库连接错误
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			} else {
				//用户没有权限对该箱操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit();
			}
		} else {
			//参数缺失 参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMENT_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMENT_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
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
			$container = new \Common\Model\QbzxInstructionCtnModel();
			$detail = $container->getContainerMsg($ctn_id);
			if ($detail !== false) {
				//根据箱ID获取作业ID
				$operation = new \Common\Model\QbzxOperationModel();
				$res_o=$operation->where("ctn_id=$ctn_id")->field('id')->find();
				$detail['operation_id']=$res_o['id'];
				//判断箱下面是否有关存在
				$sql="select count(l.id) from __PREFIX__qbzx_operation o,__PREFIX__qbzx_operation_level l where o.ctn_id=$ctn_id and o.id=l.operation_id";
				$level_num=M()->query($sql);
				if($level_num>0)
				{
					$detail['has_level']='Y';
				}else {
					$detail['has_level']='N';
				}
				if($detail['cargo_weight'] == '')
				{
					$detail['cargo_weight']='0.00';
				}
				if($detail['sealno']=='')
				{
					$detail['sealno']='';
				}
				//箱状态
				$ctn_status_d=json_decode(ctn_status_d,true);
				$status_zh=$ctn_status_d[$detail['status']];
				$detail['status_zh'] = $status_zh;
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg' => '成功',
						'content' => $detail
				);
			} else {
				//数据库连接错误
				$res = array (
						'code' => $this->ERROR_CODE_COMMON_ZH['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON_ZH['DB_ERROR']],
						'content' => ''
				);
			}
		} else {
			//参数缺失 参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMENT_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMENT_ERROR']],
					'content' => ''
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
		
	/**
	 * 删除关操作
	 * @param int $uid 用户ID
	 * @param int $operation_id 作业ID
	 * @param int $level_id 关ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function delLevel() {
		if (I ( 'post.uid' ) and I ( 'post.operation_id' ) and I ( 'post.level_id' )) {
			$uid = I ( 'post.uid' );
			$operation_id = I ( 'post.operation_id' );
			$level_id = I ( 'post.level_id' );
			$sql = "select o.operator_id from __PREFIX__qbzx_instruction_ctn c,__PREFIX__qbzx_operation o where c.id=o.ctn_id and o.id='$operation_id'";
			$res_o = M ()->query ( $sql );
			$operator_id = $res_o [0] ['operator_id'];
			$level = new \Common\Model\QbzxOperationLevelModel ();
			if ($uid == $operator_id) {
				$list = $level->where ( "id>'$level_id' and operation_id = '$operation_id'" )->find ();
				if ($list ['id'] != '') {
					// 不是最后一关，不允许删除
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION ['NOT_LAST_LEVEL'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['NOT_LAST_LEVEL']] 
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				} else {
					// 判断作业表中半关门照，全关门照是否存在，存在则删除
					$operation = new \Common\Model\QbzxOperationModel ();
					if ($res_p !== false) {
						$halfclose_door_picture = $data_o ['halfclose_door_picture'];
						$close_door_picture = $data_o ['close_door_picture'];
						// 同步删除掉作业表中的半关门图片
						if ($halfclose_door_picture != '') {
							$halfclose_door_picture = '.' . $halfclose_door_picture;
							@unlink ( '.' . IMAGE_QBZX_HALFCLOSEDOOR . $halfclose_door_picture );
						}
						// 同步删除掉作业表中的全关门图片
						if ($close_door_picture != '') {
							$close_door_picture = '.' . $close_door_picture;
							@unlink ( '.' . IMAGE_QBZX_CLOSEDOOR . $close_door_picture );
						}
						$ctn_id = $res_p ['ctn_id'];
						$data = array (
								'status' => '1' 
						);
						$back = array (
								'halfclose_door_picture' => '',
								'close_door_picture' => '' 
						);
						$res_e = $operation->where ( "id='$operation_id'" )->save ( $back );
						$container = new \Common\Model\QbzxInstructionCtnModel ();
						$res_i = $container->where ( "id='$ctn_id'" )->save ( $data );
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
								'msg' => '成功' 
						);
					}

					$res_l = $level->where ( "id='$level_id'" )->delete ();
					if ($res_l !== false) {
						//获取关的残损照片并删除
						$cargo_damage = new \Common\Model\QbzxLevelDamageImgModel ();
						$imglist = $cargo_damage->where ( "level_id='$level_id'" )->select ();
						if ($imglist) {
							// 删除关的货残损图片数据
							$res_d = $cargo_damage->where ( "level_id='$level_id'" )->delete ();
							if ($res_d !== false) {
								// 获得货残损图片路径，删除图片
								foreach ( $imglist as $l ) {
									$img = '.' . IMAGE_QBZX_CDAMAGE . $l ['damage_picture'];
									@unlink ( $img );
								}
								//删除关的货物照片
								
								$res = array (
										'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
										'msg' => '成功' 
								);
							} else {
								// 数据库连接错误
								$res = array (
										'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
										'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
								);
							}
						}
						// 同步删除掉作业关中的货物图片
						$level_cargo = D('qbzx_level_cargo_img');
						$res_s = $level_cargo->where ( "level_id='$level_id'" )->select ();
						if($res_s !== false)
						{
							$res_a = $level_cargo->where ( "level_id='$level_id'" )->delete ();
							if($res_a !== false)
							{
								foreach ($res_s as $vo)
								{
									$img = '.' . IMAGE_QBZX_CARGO . $l ['cargo_picture'];
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
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
								'msg' => '成功' 
						);
					} else {
						// 数据库连接错误
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
						);
					}
				}
			} else {
				// 该配箱已被其他理货员操作，不得再次操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION ['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['OPERATION_ALREADY_HANDLED']] 
				);
			}
		} else {
			// 参数缺失，参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['PARAMETER_ERROR']] 
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 取消作业
	 * @param int $uid 用户ID
	 * @param int $ctn_id 箱ID
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
			$container = new \Common\Model\QbzxInstructionCtnModel();
			$res_i = $container->where ( "id=$ctn_id" )->field ( 'operator_id' )->find ();
			$operator_id = $res_i['operator_id'];
			// 判断用户是否有权限对箱进行操作
			if ($uid == $operator_id)
			{
				//该箱属于用户，可以对其操作
				$operation = new \Common\Model\QbzxOperationModel();
				$res_o = $operation->where ( "ctn_id=$ctn_id" )->field ( 'id' )->find ();
				$operation_id = $res_o ['id'];
				if ($operation_id == '')
				{
					// 只接单尚未作业，只需初始化箱状态与箱操作员 ，同时删除箱接单后生成的作业记录
					$data = array (
							'status' => '0',
							'operator_id' => null
					);
					$res_s = $container->where("id=$ctn_id")->save($data);
					$operation->where("ctn_id='$ctn_id'")->delete();
					if ($res_s !== false)
					{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功'
						);
					} else {
						//数据库连接错误
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				} else {
					// 已接单并开始作业
					$level = new \Common\Model\QbzxOperationLevelModel();
					$levelnumber = $level->where ( "operation_id=$operation_id" )->count ();
					if ($levelnumber > 0)
					{
						// 箱下存在关记录，不允许取消
						$res = array (
								'code' => $this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD'],
								'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD']]
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit();
					} else {
						// 箱下不存在关记录，允许取消
						// 对空箱，作业表的删除操作
						$empty_img = new \Common\Model\QbzxEmptyCtnImgModel();
						$operation = new \Common\Model\QbzxOperationModel();
						$emptyimglist = $empty_img->where("operation_id='$operation_id'")->select();
						$operation->where("id='$operation_id'")->delete();
						if ($emptyimglist !== false)
						{
							// 删除空箱图片数据
							$res_e = $empty_img->where ( "operation_id='$operation_id'" )->delete ();
							if ($res_e !== false)
							{
								// 获得空箱图片路径，删除图片
								foreach ( $emptyimglist as $l ) {
									$img = '.'.IMAGE_QBZX_EMPTY . $l ['empty_picture'];
									@unlink ( $img );
								}
							}
							// 初始化箱状态与箱操作员,同时删除箱的作业记录
							$data = array (
									'status' => '0',
									'operator_id' => null
							);
							$res_c = $container->where ( "id=$ctn_id" )->save ( $data );
							$operation->where("ctn_id='$ctn_id'")->delete();
							if ($res_c !== false)
							{
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
										'msg' => '成功'
								);
							} else {
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						} else {
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			} else {
				//用户没有权限对该箱操作
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit();
			}
		} else {
			//参数缺失，参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	
	/**
	 * 预约铅封
	 * @param  int $uid:用户ID
	 * @param  string sealno:铅封号
	 * @param  int ctn_id:箱ID
	 * @param  string cargo_weight:货物重量
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function reservation()
	{
		if (I ( 'post.sealno' ) and I ( 'post.uid' ) and I ( 'post.ctn_id' ))
		{
			$sealno = strtoupper ( I ( 'post.sealno' ) );
			$sealno = str_replace("'", "", $sealno);
			$uid = I ( 'post.uid' );
			$ctn_id = I ( 'post.ctn_id' );
			$cargo_weight = I ( 'post.cargo_weight' );
			// 查看铅封号是否重复
			$operation = new \Common\Model\QbzxOperationModel();
			$res_s = $operation->where ( "sealno='$sealno'" )->find ();
			if ($res_s)
			{
				//铅封号已存在
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['SEALNO_EXIST'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['SEALNO_EXIST']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			// 将预约铅封设置为已预约,并保存集装箱信息
			$back = array (
					'sealno' => $sealno,
					'cargo_weight' => $cargo_weight,
					'is_reservation' =>'Y'
			);
			if(!$operation->create($back))
			{
				//对back数据进行验证
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
						'msg'=>$operation->getError()
				);
			}else{
				//验证通过 可以对数据进行操作
				$res_o = $operation->where ( "ctn_id='$ctn_id'" )->save ( $back );
				if ($res_o !== false)
				{
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
							'msg' => '成功'
					);
				} else {
					//数据库连接错误
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		} else {
			//参数缺失，参数不正确
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
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
			$operation = new \Common\Model\QbzxOperationModel();
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
				$operation = new \Common\Model\QbzxOperationModel ();
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
			$operation = new \Common\Model\QbzxOperationModel();
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
					//先判断理货员工作中的箱子是否已有3个
					$sql="select c.id from __PREFIX__qbzx_operation o,__PREFIX__qbzx_instruction_ctn c where c.id=o.ctn_id and c.operator_id='$uid' and o.operator_id='$uid' and c.status='1' and o.is_stop!='Y'";
					$res_n=M()->query($sql);
					$n = count($res_n);
					if($n>=50)
					{
						//不能同时处理3个以上箱子
						$res = array (
								'code' => $this->ERROR_CODE_OPERATION_ZH['HAVE_THREEOPERATION_CTN'],
								'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION_ZH['HAVE_THREEOPERATION_CTN']]
						);
					}else {
						//将暂停作业修改为i不暂停
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
	 * 修改关信息 
	 * @param int $uid:用户ID
	 * @param int $operation_id:作业ID
	 * @param int $level_id:关ID
	 * @param string $cargo_number:货物件数
	 * @param string $damage_num:残损货物件数 
	 * @param string $billno:提单号
	 * @param string $remark:备注
	 * @return @param array|boolean
	 */
	public function editLevel() 
	{
		if(I('post.uid') and I('post.operation_id') and I('post.level_id'))
		{
			$uid = I('post.uid');
			$operation_id = I('post.operation_id');
			$level_id = I('post.level_id');
			$cargo_number = I('post.cargo_number');
			$damage_num = I('post.damage_num');
			$billno = strtoupper( I ('post.billno'));//把字符串转换为大写
			
			if(I ( 'post.remark' ))
			{
				$remark = I ( 'post.remark' );
			}else {
				//修改原因不能为空
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['EDITREASON_NOT_EMPTY'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['EDITREASON_NOT_EMPTY']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			
			$where = array (
					'uid' => $uid
			);
			$userinfo = new \Common\Model\UserModel();
			$user = $userinfo->where ($where)->find ();
			$shift = new \Common\Model\ShiftModel();
			$shift_id = $user['shift_id'];
			$g =$shift->where("shift_id='$shift_id'")->find();
			if ($g ['shift_master'] != $uid) 
			{
				//需要理货长权限
				$res = array (
						'code' => $this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY'],
						'msg' => $this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			//没有修改项
			$res = array (
					'code' => $this->ERROR_CODE_OPERATION['NOT_EDIT'],
					'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NOT_EDIT']]
			);
			
//			$container = new \Common\Model\QbzxInstructionCtnModel();
//			$ctn = $container->where("id='$operation_id'")->find ();
/**
 * 理货公司新增要求，本来是工作中不可修改的，现在提出可修改
 */
// 			if ($ctn ['status'] == 1) 
// 			{
// 				//该箱工作中，不可修改
// 				$res = array (
// 						'error_code' => $this->ERROR_CODE_OPERATION['CTN_WORKIN'],
// 						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['CTN_WORKIN']]
// 				);
// 				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
// 				exit ();
// 			}
			$operation = new \Common\Model\QbzxOperationModel();
			$op = $operation->where ( "id='$operation_id'" )->find ();
			if ($op ['per_no'] == 3) {
				$u = $userinfo->where("uid='$uid'")->find();
				if ($u ['position'] == '理货长' || $u ['position'] == '理货员') 
				{
					//该工班已交班
					$res = array (
							'code' => $this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED'],
							'msg' => $this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
			}
			$level = new \Common\Model\QbzxOperationLevelModel();
			$levellist = $level->where ( "id='$level_id'" )->find ();
			
			if ($levellist ['cargo_number'] != $cargo_number) 
			{
				$back1 = array(
						'cargo_number'=>$cargo_number
				);
				
				$res = $level->where("id='$level_id'")->save($back1);
			
				$data = array (
						'business' => 'qbzx',
						'category' => 'operation_level',
						'operation_id' => $operation_id,
						'info_id' => $level_id,
						'field_name' => 'cargo_number',
						'field_old_value' => $levellist ['cargo_number'],
						'field_new_value' => $cargo_number,
						'uid' => $uid,
						'date' => date ( 'Y-m-d H:i:s', time () ),
						'remark' => $remark
				);
				$amend = new \Common\Model\AmendModel();
				if(!$amend->create($data))
				{
					//对data数据进行验证
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>$amend->getError()
					);
				}else{
					//验证通过 可以对数据进行操作
					$amend->add($data);
				}
			}
			
			if ($levellist ['damage_num'] != $damage_num) 
			{
				$back2 = array(
						'damage_num'=>$damage_num
				);
				$res = $level->where("id='$level_id'")->save($back2);
			
				$data = array (
						'business' => 'qbzx',
						'category' => 'operation_level',
						'operation_id' => $operation_id,
						'info_id' => $level_id,
						'field_name' => 'damage_num',
						'field_old_value' => $levellist ['damage_num'],
						'field_new_value' => $damage_num,
						'uid' => $uid,
						'date' => date ( 'Y-m-d H:i:s', time () ),
						'remark' => $remark
				);
				$amend = new \Common\Model\AmendModel();
				if(!$amend->create($data))
				{
					//对data数据进行验证
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>$amend->getError()
					);
				}else{
					//验证通过 可以对数据进行操作
					$amend->add($data);
				}
			}
			
			if ($levellist ['billno'] != $billno) 
			{
				$back3 = array(
						'billno'=>$billno
				);
				$res = $level->where("id='$level_id'")->save($back3);
			
				$data = array (
						'business' => 'qbzx',
						'category' => 'operation_level',
						'operation_id' => $operation_id,
						'info_id' => $level_id,
						'field_name' => 'billno',
						'field_old_value' => $levellist['billno'],
						'field_new_value' => $billno,
						'uid' => $uid,
						'date' => date ( 'Y-m-d H:i:s', time () ),
						'remark' => $remark
				);
				$amend = new \Common\Model\AmendModel();
				if(!$amend->create($data))
				{
					//对data数据进行验证
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>$amend->getError()
					);
				}else{
					//验证通过 可以对数据进行操作
					$amend->add($data);
				}
			}
			// 根据箱id找出单证
			$prove = new \Common\Model\QbzxProveModel();
			$ctn_certify = $prove->where ( "ctn_id='".$op['ctn_id']."'" )->find ();
			// 将原单证备注保存
			$ccremark = $ctn_certify ['remark'];
			// 删除原单证
			$ctn_certify = $prove->where ( "ctn_id='" .$op['ctn_id'] . "'" )->delete ();
			// 重新生成单证
			$result = $prove->generateDocumentByQbzx($op['ctn_id'],$ccremark);
			if($result)
			{
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功'
				);
			}else{
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else{
			//参数缺失，参数不正确
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETR_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETR_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	
/**
 * 上传base64图片
 * @param string $base64:文件base64编码
 * @param string $path:文件保存路径
 * @param array $exts:允许上传的文件后缀
 * @param string $method:数据传输方式 POST GET
 * @return string|code
 * @return 成功返回文件完整路径
 * @return code:0成功 1不是正确的图片文件 2文件上传失败
 */
function base64_upload($base64,$path,$method='post') 
{
	//post的数据里面，加号会被替换为空格，需要重新替换回来；如果不是post的数据，则注释掉这一行
	if($method=='post')
	{
		$base64_file = str_replace(' ', '+', $base64);
	}
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_file, $result))
	{
		//匹配成功
		$file_name = uniqid().rand(111, 999).'.'.$result[2];
		$save_file = $path.$file_name;
		//服务器文件存储路径
		if (file_put_contents($save_file, base64_decode(str_replace($result[1], '', $base64_file))))
		{
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'file'=>$save_file
			);
		}else{
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR']]
			);
		}
	}else{
		$res=array(
				'code'=>$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
				'msg'=>'不是正确的图片文件'
		);
	}
	return $res;
}
	
	/**
	 * 修改工作箱信息
	 * @param int $uid:用户ID
	 * @param int $operation_id:作业ID
	 * @param string $sealno:铅封号
	 * @param string $empty_weight:空箱重量
	 * @param string $cargo_weight:货物重量
	 * @param string $remark:备注
	 * @return @param array|boolean
	 * @return code:返回码
	 * @return msg:返回码说明
	 */

	public function editContainer() 
	{
		if(I('post.uid') and I('post.operation_id'))
		{
			$uid = I ('post.uid');
			$operation_id = I ('post.operation_id');
			$sealno = strtoupper(I('post.sealno'));
			$empty_weight = I('post.empty_weight');
			$cargo_weight = I('post.cargo_weight');
			
			if(I ('post.remark'))
			{
				$remark = I ( 'remark' );
			}else {
				//修改原因不能为空
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['EDITREASON_NOT_EMPTY'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['EDITREASON_NOT_EMPTY']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			$where = array (
					'uid' => $uid
			);
			$userinfo = new \Common\Model\UserModel();
			$user = $userinfo->where ( $where )->find ();
			$shift = new \Common\Model\ShiftModel();
			$g = $shift->where("shift_id='".$user[shift_id]."'")->find();
			if ($g ['shift_master'] != $uid) 
			{
				//需要理货长权限
				$res = array (
						'code' => $this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY'],
						'msg' => $this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['NEED_PERMISSION_CHIEFTALLY']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			//没有修改项
			$res = array (
					'code' => $this->ERROR_CODE_OPERATION['NOT_EDIT'],
					'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NOT_EDIT']]
			);
			$operation = new \Common\Model\QbzxOperationModel();
			$data_o = $operation->where("id='$operation_id'")->find();
			
			$container = new \Common\Model\QbzxInstructionCtnModel();
			$ctn =$container->where ( "id='".$data_o['ctn_id']."'" )->find ();
			if ($ctn ['status'] == 1) 
			{
				//该箱工作中，不可修改
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['CTN_WORKIN'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['CTN_WORKIN']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			$operation = new \Common\Model\QbzxOperationModel();
			$op = $operation->where ( "id='$operation_id'" )->find ();
			if ($op ['per_no'] == 3) 
			{
				$u = $userinfo->where("uid='$uid'")->find();
				if ($u ['position'] == '理货长' || $u ['position'] == '理货员') 
				{
					//该工班已交班
					$res = array (
							'code' => $this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED'],
							'msg' => $this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['SHIFT_EXCHANGED']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
			}
			
			$sealno_old = $op ['sealno'];
			$emptyweight_old = $op ['empty_weight'];
			$cargoweight_old = $op ['cargo_weight'];
			
			if ($sealno != $sealno_old) 
			{
				$data_s = array(
						'sealno'=>$sealno
				);
				$res = $operation->where("id='$operation_id'")->save($data_s);
			
				$data = array (
						'business' => 'qbzx',
						'category' => 'operation',
						'operation_id' => $operation_id,
						'info_id' => $operation_id,
						'field_name' => 'sealno',
						'field_old_value' => $sealno_old,
						'field_new_value' => $sealno,
						'uid' => $uid,
						'date' => date ( 'Y-m-d H:i:s', time () ),
						'remark' => $remark
				);
				$amend = new \Common\Model\AmendModel();
				if(!$amend->create($data))
				{
					//对data数据进行验证
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>$amend->getError()
					);
				}else{
					//验证通过 可以对数据进行操作
					$amend->add($data);
				}
			}
			
			if ($empty_weight != $emptyweight_old) 
			{
				$data_e = array(
						'empty_weight'=>$empty_weight
				);
				$res = $operation->where("id='$operation_id'")->save($data_e);
				$data = array (
						'business' => 'qbzx',
						'category' => 'operation',
						'op_id' => $operation_id,
						'info_id' => $operation_id,
						'field_name' => 'empty_weight',
						'field_old_value' => $emptyweight_old,
						'field_new_value' => $empty_weight,
						'uid' => $uid,
						'date' => date ( 'Y-m-d H:i:s', time () ),
						'remark' => $remark
				);
				$amend = new \Common\Model\AmendModel();
				if(!$amend->create($data))
				{
					//对data数据进行验证
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>$amend->getError()
					);
				}else{
					//验证通过 可以对数据进行操作
					$amend->add($data);
				}
			}
			
			if ($cargo_weight != $cargoweight_old) 
			{
				$data_c = array(
						'cargo_weight'=>$cargo_weight
				);
				$res = $operation->where("id='$operation_id'")->save($data_c);
				$data = array (
						'business' => 'qbzx',
						'category' => 'operation',
						'op_id' => $operation_id,
						'info_id' => $operation_id,
						'field_name' => 'cargo_weight',
						'field_old_value' => $cargoweight_old,
						'field_new_value' => $cargo_weight,
						'uid' => $uid,
						'date' => date ( 'Y-m-d H:i:s', time () ),
						'remark' => $remark
				);
				$amend = new \Common\Model\AmendModel();
				if(!$amend->create($data))
				{
					//对data数据进行验证
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>$amend->getError()
					);
				}else{
					//验证通过 可以对数据进行操作
					$amend->add($data);
				}
			}
			
			if (I ( 'post.picture' )) 
			{
				$seal_picture = I ( 'post.picture' );
				$path_s = '.'.IMAGE_QBZX_SEAL;
				$res_s = base64_upload ( $seal_picture, $path_s );
				if ($res_s ['code'] != 0)
				{
					//文件上传错误
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['FILE_UPLOAD_ERROR'],
							'msg' => $res_s ['msg']
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				} else {
					$seal_file = $path_s.$res_s ['file'];
					$seal_img = $res_s ['file'];
				}
				$path_old = $op ['seal_picture'];
			
				@unlink($path_s.$path_old);
				$data_p = array(
						'seal_picture'=>$seal_img
				);
				$res = $operation->where("id='$operation_id'")->save($data_p);
			
				$data = array (
						'business' => 'qbzx',
						'category' => 'operation',
						'op_id' => $operation_id,
						'info_id' => $operation_id,
						'field_name' => 'seal_picture',
						'field_old_value' => $path_old,
						'field_new_value' => $seal_img,
						'uid' => $uid,
						'date' => date ( 'Y-m-d H:i:s', time () ),
						'remark' => $remark
				);
				$amend = new \Common\Model\AmendModel();
				if(!$amend->create($data))
				{
					//对data数据进行验证
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>$amend->getError()
					);
				}else{
					//验证通过 可以对数据进行操作
					$amend->add($data);
				}
			}
			
			// 根据箱id找出单证
			$prove = new \Common\Model\QbzxProveModel();
			$ctn_certify = $prove->where ( "ctn_id='" . $op['ctn_id'] . "'" )->find ();
			// 将原单证备注保存
			$ccremark = $ctn_certify ['remark'];
			// 删除原单证
			$ctn_certify = $prove->where ( "ctn_id='" . $op['ctn_id'] . "'" )->delete ();
			// 重新生成单证
			$result = $prove->generateDocumentByQbzx($op['ctn_id'], $ccremark );
			if($result)
			{
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功'
				);
			}else{
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else{
			//参数缺失，参数不正确
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 修改指令
	 * @param int $uid:用户ID
	 * @param int $instruction_id:指令ID
	 * @param int $location_id:作业场地ID
	 * @param int $plan_id:预报计划ID
	 * @param string $loadingtype:装箱方式
	 */
	public function editInstruction() 
	{
		if(I('post.uid') and I('post.instruction_id') and I('post.location_id') and I('post.plan_id'))
		{
			$instruction_id = I ('post.instruction_id');
			$location_id = I('post.location_id');
			$plan_id = I('post.plan_id');
			$loadingtype = I ('post.loadingtype');	
			$data = array(
					'plan_id'=>$plan_id,
					'location_id'=>$location_id,
					'loadingtype'=>$loadingtype,
					'ordertime'=>date ('Y-m-d'),
					'last_operator'=> I('post.uid'),
					'last_operationtime'=>date('Y-m-d H:i:s')
			);
			
			$instruction = new \Common\Model\QbzxInstructionModel();
			if(!$instruction->create($data))
			{
				//对data数据进行验证
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
						'msg'=>$instruction->getError()
				);
			}else{
				//验证通过 可以对数据进行处理
				$res = $instruction->where ("id='$instruction_id'")->save($data);
				if ($res) {
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
							'msg' => '修改成功'
					);
				} else {
					//数据库连接错误
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else{
			//参数缺失，参数不正确
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
}