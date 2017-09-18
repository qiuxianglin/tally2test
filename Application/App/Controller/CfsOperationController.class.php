<?php
/**
 * APP接口
 * CFS装箱作业接口
 * @author 殷根朋 2016-8-8
 */
namespace App\Controller;
use App\Common\BaseController;
header ( "Access-Control-Allow-Origin: *" );

class CfsOperationController extends BaseController
{
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
			$sql="select c.*,m.ctn_master as cmaster from __PREFIX__cfs_instruction_ctn c,__PREFIX__container_master m where c.instruction_id='$instruction_id' and c.ctn_master=m.id";
			$list=M()->query($sql);
			$n = count($list);
			for($i = 0; $i < $n; $i ++)
			{
				if ($list [$i] ['lcl'] == 'L') {
					$list [$i] ['lcl'] = '拼箱';
				} else {
					$list [$i] ['lcl'] = '整箱';
				}

				// 箱状态
				$ctn_status_d = json_decode ( ctn_status_d, true );
				$status_zh = $ctn_status_d [$list [$i] ['status']];
				$list [$i] ['status_zh'] = $status_zh;
			}
			if ($list !== false) {
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
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
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 新增配箱
	 * @param uid 用户ID
	 * @param instruction_id 指令ID
	 * @param ctnno 箱号
	 * @param ctn_size 箱型尺寸
	 * @param ctn_master 箱主
	 * @param lcl 拼箱状态（提货方式），F整箱、L拼箱
	 * @param pre_number 预配件数
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
			$sql = "select s.shift_master from __PREFIX__shift s,__PREFIX__cfs_instruction i where i.id='$instruction_id' and i.department_id=s.department_id";
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
							'ctn_size'=>I('post.ctn_size'),
							'ctn_master'=>I('post.ctn_master'),
							'lcl'=>I('post.lcl'),
							'pre_number'=>I('post.pre_number'),
							'status'=>'0'
					);
					$instructionContainer=new \Common\Model\CfsInstructionCtnModel();
					$res=$instructionContainer->add($data);
					if($res !== false)
					{
						$res = array (
								'code' =>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功'
						);
					}else{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}
			}else{
				//该用户不是当班理货长，不具备理货长权限  208
				$res = array (
						'code' => $this->ERROR_CODE_SHIFT['NOT_ONDUTY_CHIEFTALLY'],
						'msg' => $this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['NOT_ONDUTY_CHIEFTALLY']]
				);
			}
		}else{
			$res = array (
					'code' => $this->ERROR_CODE_LOCATION['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_LOCATION['PARAMETER_ERROR']]
			);
		}
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
			$sql = "select s.shift_master from __PREFIX__shift s,__PREFIX__cfs_instruction i where i.id='$instruction_id' and i.department_id=s.department_id";
			$res_i = M ()->query ( $sql );
			$shift_master = $res_i [0] ['shift_master'];
			if ($shift_master == $uid) 
			{
				$ctn_id = I ( 'post.ctn_id' );
				$instructionContainer = new \Common\Model\CfsInstructionCtnModel ();
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
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']] 
						);
					}
				} else {
					//该指令配箱已作业，不能删除 633
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['CTN_OPERATION_NOTDEL'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['CTN_OPERATION_NOTDEL']]
					);
				}
			} else {
				//您不是当班理货长，没有权限进行操作 208
				$res = array (
						'code' => $this->ERROR_CODE_SHIFT['NOT_ONDUTY_CHIEFTALLY'],
						'msg' => $this->ERROR_CODE_SHIFT_ZH[$this->ERROR_CODE_SHIFT['NOT_ONDUTY_CHIEFTALLY']] 
				);
			}
		} else {
			//参数缺失 参数不正确 3
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	/**
	 * 待办箱列表
	 * @param uid 用户ID
	 * @param status 状态1：未作业2：作业中3：已铅封
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
			// 根据用户ID查询被分配的指令任务
			$sql1 = "select d.instruction_id from __PREFIX__dispatch d,__PREFIX__dispatch_detail dd where d.id = dd.dispatch_id and dd.clerk_id='$uid' and d.business='cfs' and d.mark!='1'";
			$res_i = M ()->query ( $sql1 );
			if (count ( $res_i ) > 0)
			{
				foreach ( $res_i as $instruction ) 
				{
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
			if(I('post.status') == '1')
			{
				// 获取相应状态的箱列表-未开始
				$sql2 = "select c.*,i.operation_type from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_ctn c where i.id in ($instruction_id) and i.id=c.instruction_id and c.status='0' order by c.id desc";
				$list = M ()->query ( $sql2 );
				if ($list !== false)
				{
					$container = new \Common\Model\CfsInstructionCtnModel ();
					$cnum = count ( $list );
					for($i = 0; $i < $cnum; $i ++)
					{
						$ctn_id = $list [$i] ['id'];
						$res_b = $container->is_begin ( $ctn_id );
						if ($res_b === true)
						{
							$list [$i] ['is_begin'] = 'Y';
						} else {
							$list [$i] ['is_begin'] = 'N';
						}
					}
				}
			}

			if(I('post.status') == '2' or I('post.status')=='3')
			{
				$status = I('post.status')-1;
				// 获取相应状态的箱列表-工作中、已完成
				$sql3 = "select c.*,i.operation_type from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_ctn c where i.id=c.instruction_id and i.id in ($instruction_id)  and c.status='$status' and c.operator_id='$uid' order by c.id desc";
				$list = M ()->query ( $sql3 );
				if ($list !== false)
				{
					$container = new \Common\Model\CfsInstructionCtnModel ();
					$cnum = count ( $list );
					for($i = 0; $i < $cnum; $i ++)
					{
						$ctn_id = $list [$i] ['id'];
						$res_b = $container->is_begin ( $ctn_id );
						if ($res_b === true)
						{
							$list [$i] ['is_begin'] = 'Y';
						} else {
							$list [$i] ['is_begin'] = 'N';
						}
						$sql = "select l.id from __PREFIX__cfs_operation o,__PREFIX__cfs_operation_level l where o.ctn_id='$ctn_id' and o.id=l.operation_id";
						$res_l = M ()->query ( $sql );
						$operation = new \Common\Model\CfsOperationModel ();
						$res_o = $operation->where ( "ctn_id='$ctn_id'" )->find ();
						$list [$i] ['tmp_sealno'] = $res_o ['tmp_sealno'];
						//步骤
						$list [$i] ['step'] = $res_o ['step'];
						//暂停标志
						$list [$i] ['is_stop'] = $res_o ['is_stop'];
						//作业ID
						$list [$i] ['operation_id'] = $res_o ['id'];
						if ($res_o ['sealno'] !== null)
						{
							$list [$i] ['sealno'] = $res_o ['sealno'];
						} else {
							$list [$i] ['sealno'] = '';
						}
						if ($res_o ['cargo_weight'] == null)
						{
							$list [$i] ['cargo_weight'] = '0';
						} else {
							$list [$i] ['cargo_weight'] = $res_o ['cargo_weight'];
						}
						$operation_id = $res_l [0] ['id'];
						if ($operation_id !== null)
						{
							$list [$i] ['is_level'] = 'Y';
						} else {
							$list [$i] ['is_level'] = 'N';
						}
					}
				}
			}

			$num = count ( $list );
			for($i = 0; $i < $num; $i ++) 
			{
				// 整拼状态
				switch ($list [$i] ['lcl']) 
				{
					case 'F' :
						$list [$i] ['lcl_zh'] = '整箱';
						break;
					case 'L' :
						$list [$i] ['lcl_zh'] = '拼箱';
						break;
				}
			}
			for($i = 0; $i < $num; $i ++) 
			{
				// 箱状态
				$ctn_status_d = json_decode ( ctn_status_d, true );
				$status_zh = $ctn_status_d [$list [$i] ['status']];
				$list [$i] ['status_zh'] = $status_zh;
				//获取装箱方式
				switch ($list [$i] ['operation_type'])
				{
					case '0' :
						$list [$i] ['operation_type_zh'] = '人工';
						break;
					case '1' :
						$list [$i] ['operation_type_zh'] = '机械';
						break;
				}
			}
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
					'msg' => '成功',
					'list' => $list 
			);
		} else {
			$res = array (
					'code' => $this->ERROR_CODE_COMMON ['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['PARAMETER_ERROR']],
					'list' => '' 
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 获取指令下的配货列表
	 * @param instruction_id 指令ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回结果列表
	 */
	public function getCargoList()
	{
        if(I('post.instruction_id'))
        {
        	$instruction_id = I('post.instruction_id');
        	$cargo = new \Common\Model\CfsInstructionCargoModel();
        	$list = $cargo->where("instruction_id='$instruction_id'")->select();
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
	 * 理货员接单
	 * @param uid 用户ID
	 * @param ctn_id 箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function ordertaking()
	{
		if(I('post.uid') and I('post.ctn_id') and I('post.instruction_id'))
		{
			$uid=I('post.uid');
			$ctn_id=I('post.ctn_id');
			$instruction_id = I('post.instruction_id');
			$container=new \Common\Model\CfsInstructionCtnModel();
			//获取符合条件的信息
			$res_c=$container->where("id=$ctn_id")->field('operator_id ')->find();
			$res_x = $container->where("status in (1) and operator_id='$uid'")->select();
			$n = count($res_x);
			if($n>=100)
			{
				//不能同时接100个以上箱子
				$res = array(
						'code'=>$this->ERROR_CODE_OPERATION['HAVE_THREEOPERATION_CTN'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['HAVE_THREEOPERATION_CTN']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
			if($res_c['operator_id']!='')
			{
				//该配箱已被其他理货员操作，不得再次操作
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']],
				);
			}else {
				$where="id=$ctn_id and status = '0' and operator_id is null";
				$data=array(
						'operator_id'=>$uid,
						'status'=>1
				);
				$res_m=$container->where($where)->save($data);
				if($res_m!==false){
					//判断该箱是否存在作业记录，存在的情况下修改作业的操作人
					$operation = new \Common\Model\CfsOperationModel();
					$res_o=$operation->where("ctn_id=$ctn_id")->find();	
					if($res_o['id']!='')
					{
						//存在记录，修改操作人
						$data_o=array(
								'operator_id'=>$uid
						);
						$operation->where("ctn_id=$ctn_id")->save($data_o);
						$data_c=array(
								'status'=>'1'
						);
						$container->where("id='$ctn_id'")->save($data_c);
					}else{
						$back=array(
								'ctn_id'=>$ctn_id,
								'operator_id'=>$uid,
								'begin_time'=>date('Y-m-d H:i:s')
						);
						$operation->add($back);
					}
					$operationlevel = new \Common\Model\CfsOperationLevelModel();
					$operationlevelnum = $operationlevel->sumLevelNum($res_o['id']);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'step'    => $res_o['step'],
							'level_num'   => $operationlevelnum
					);
				}else{
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					);
				}
			}
		}else{
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
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
			$container = new \Common\Model\CfsInstructionCtnModel();
			$res_i = $container->where ( "id='$ctn_id'" )->field ( 'operator_id' )->find ();
			$operator_id = $res_i['operator_id'];
			// 判断用户是否有权限对箱进行操作
			if ($uid == $operator_id)
			{
				//该箱属于用户，可以对其操作
				$operation = new \Common\Model\CfsOperationModel();
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
								'msg' => '成功'
						);
					} else {
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				} else {
					// 已接单并开始作业
					$level = new \Common\Model\CfsOperationLevelModel();
					$levelnumber = $level->where ( "operation_id=$operation_id" )->count ();
					if ($levelnumber > 0)
					{
						// 箱下存在关记录，不允许取消
						$res = array (
								'code' =>$this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD'],
								'msg' =>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD']]
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
							$res_c = $container->where ( "id=$ctn_id" )->save ( $data );
							if ($res_c !== false)
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
			//参数不正确，参数缺失
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
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
			$container = new \Common\Model\CfsInstructionCtnModel();
			$detail = $container->getContainerMsg($ctn_id);
			//写日志
			writeLog(json_encode($detail));
			if ($detail !== false) {
				//根据箱ID获取作业ID
				$operation = new \Common\Model\CfsOperationModel();
				$res_o=$operation->where("ctn_id=$ctn_id")->field('id,step')->find();
				$detail[0]['operation_id']=$res_o['id'];
				//判断箱下面是否有关存在
				$sql="select count(l.id) from __PREFIX__cfs_operation o,__PREFIX__cfs_operation_level l where o.ctn_id=$ctn_id and o.id=l.operation_id";
				$level_num1=M()->query($sql);
				$level_num = $level_num1[0]['count(l.id)'];
				if($level_num>0)
				{
					$detail['has_level']='Y';
				}else {
					$detail['has_level']='N';
				}
				if($detail[0]['cargo_weight'] == '')
				{
					$detail[0]['cargo_weight']='0.00';
				}
				if($detail[0]['sealno']=='')
				{
					$detail[0]['sealno']='';
				}
				//关号
				$detail[0]['num_level']=$level_num;
				$detail[0]['step'] = $res_o['step'];
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg' => '成功',
						'content' => $detail[0]
				);
			} else {
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content' => ''
				);
			}
		} else {
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
					'content' => ''
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * CFS装箱作业信息核对
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
		if(I('post.ctn_id') and I('post.uid') and I('post.empty_weight') and is_array(I ( 'post.empty_picture' )))
		{
			$ctn_id = I ( 'post.ctn_id' );
			$uid = I ( 'post.uid' );
			$empty_weight = I('post.empty_weight');
			
			$operation = new \Common\Model\CfsOperationModel();
			//检查该箱的操作员是否为本人，不是则中止操作
			$container = new \Common\Model\CfsInstructionCtnModel();
			$res_u = $container->where("id='$ctn_id'")->field('operator_id')->find();
			if($res_u['operator_id'] == '')
			{
				//该箱尚无操作人员，请先接单！ 622
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['NEED_ACCEPT_TASK'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_ACCEPT_TASK']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			if($res_u['operator_id']!=$uid)
			{
				//该箱已被其它理货员操作，请勿重复操作！ 601
				$res = array (
						'code' =>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			
			$cfs_step=json_decode(cfs_step,true);
			$data = array (
					'empty_weight' => $empty_weight,
					'step'=>$cfs_step['check']
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
				$operation->where("ctn_id='$ctn_id'")->save ($data);
				$res_i = $operation->where("ctn_id='$ctn_id'")->field('id')->find();
				$operation_id=$res_i['id'];
				if($operation_id !== false)
				{
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
							'msg' => '成功',
							'operation_id'=>$operation_id
					);
					// 空箱照片
					if (is_array(I ( 'post.empty_picture' )))
					{
						$empty_picture = I ( 'post.empty_picture' );
						$path_s = '.'.IMAGE_CFS_EMPTY;
						foreach ($empty_picture as $e)
						{
							// 上传一张空箱图片
							$res_s = base64_upload ( $e, $path_s );
							if ($res_s ['code'] != 0)
							{
								// 上传失败
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
										'msg' => $res_s ['msg']
								);
								echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
								exit ();
							} else {
								// 上传成功，保存数据到空箱照片表
								// 上传成功的图片，防止插入空箱照片表失败时回退
								$empty_img [] = $res_s ['file'];
								$data_empty [] = array (
										'operation_id'=>$operation_id,
										'empty_img' => $res_s ['file']
								);
							}
							$res_s = '';
						}
						$ctn_empty = new \Common\Model\CfsCtnEmptyImgModel();
						$res_car = $ctn_empty->addAll ( $data_empty );
						if ($res_car !== false)
						{
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
									'msg' => '成功',
									'operation_id'=>$operation_id
							);
						} else {
							// 需要删除已上传的空箱照片
							foreach ( $empty_img as $k => $v )
							{
								@unlink ($path_s.$v);
							}
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}else{
						//必须拍摄空箱照片 604
						$res = array(
								'code'=>$this->ERROR_CODE_OPERATION['NEED_EMPTY_CTN_PICTURE'],
								'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_EMPTY_CTN_PICTURE']]
						);
					}
				}else {
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
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
			$sql="select l.* from __PREFIX__cfs_operation_level l,__PREFIX__cfs_operation o where l.operation_id=o.id and o.ctn_id='$ctn_id' order by l.level_num asc";
			$list=M()->query($sql);
			if($list!==false)
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'list'=>$list
				);
			}else{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'list'=>''
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
	 * @param int num:货物件数
	 * @param int level_picture:关照片
	 * @param int damage_num:残损件数
	 * @param array damage_img:货残损图片
	 * @param array blno:提单号 
	 * @return array 
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function OperationLevel()
	{
		if (I ( 'post.operation_id' ) and I ( 'post.num' ) !== '' and I('post.blno'))
		{
			$blno = I('post.blno');
			$operation_id = I ( 'post.operation_id' );
			$num = (int)I ( 'post.num' );
			$operation = new \Common\Model\CfsOperationModel();
			$res_o = $operation->where ( "id='$operation_id'" )->field ( 'id,operator_id,ctn_id' )->find ();
			if ($res_o ['id'] == '')
			{
				//该作业记录不存在，请核实！ 621
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
			$container = new \Common\Model\CfsInstructionCtnModel();
			$res_operator=$container->where("id=$ctn_id")->field('operator_id')->find();
			$operator_id=$res_operator['operator_id'];
			if (I ( 'post.damage_num' ))
			{
				$damage_num = (int)I ( 'post.damage_num' );
			} else {
				$damage_num = 0;
			}
			//计算目前关数
			$level = new \Common\Model\CfsOperationLevelModel();
			$level_num=$level->where("operation_id='$operation_id'")->count();
			$data = array (
					'operation_id' => $operation_id,
					'num' => $num,
					'damage_num' => $damage_num,
					'level_num'=>$level_num+1,
					'blno'=>$blno,
					'operator_id'=>$operator_id,
					'createtime'=>date('Y-m-d H:i:s')
			);
			$level_id = $level->add ( $data );
	
			if ($level_id !== false)
			{
				if(is_array (I('post.level_picture')))
				{
					// 货物照片
					if (I ( 'post.level_picture' )) {
						$level_picture = I ( 'post.level_picture' );
						$path_s = '.' . IMAGE_CFS_CARGO;
						foreach ( $level_picture as $e ) {
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
						$level_cargo = D('cfs_level_cargo_img');
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
					$path_c = '.'.IMAGE_CFS_CDAMAGE;
					foreach ( $damage_img as $d )
					{
						// 上传一张残损图片
						$res_c = base64_upload ( $d, $path_c );
						if ($res_c ['code'] != 0)
						{
							//上传失败
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
									'level_num'=>$level_num+1,
									'img' => $res_c ['file']
							);
						}
						$res_c = '';
					}
					$cargo_damage = new \Common\Model\CfsCargoDamageImgModel();
					$res_car = $cargo_damage->addAll ( $data_damage );
					if ($res_car !== false)
					{
						$cfs_step=json_decode(cfs_step,true);
						$data_o = array (
								'step' => $cfs_step['levelin']
						);
						$operation->where ( "id='$operation_id'" )->save ( $data_o );
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功',
								'level_num'=>$level_num+1
						);
					} else {
						// 需要删除已上传的货物残损图
						foreach ( $damage_img as $k => $v )
						{
							@unlink ($path_c.$v);
						}
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				} else {
					$cfs_step=json_decode(cfs_step,true);
					$data_o = array (
							'step' => $cfs_step['levelin']
					);
					$operation->where ( "id='$operation_id'" )->save ( $data_o );
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
							'msg' => '成功',
							'level_num'=>$level_num+1
					);
				}
			} else {
				//需要删除已删除上传的货照片
				@unlink($path_s.$cargo_img);
				//数据库错误 2
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		} else {
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
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
	public function delLevel()
	{
		if (I ( 'post.uid' ) and I ( 'post.operation_id' ) and I ( 'post.level_id' )) 
		{
			$uid = I ( 'post.uid' );
			$operation_id = I ( 'post.operation_id' );
			$level_id = I ( 'post.level_id' );
			$sql = "select o.operator_id from __PREFIX__cfs_instruction_ctn c,__PREFIX__cfs_operation o where c.id=o.ctn_id and o.id='$operation_id'";
			$res_o = M ()->query ( $sql );
			$operator_id = $res_o [0] ['operator_id'];
			$level = new \Common\Model\CfsOperationLevelModel();
			if ($uid == $operator_id) 
			{
				$list = $level->where ( "id>'$level_id' and operation_id='$operation_id'" )->find ();
				if ($list ['id'] != '') {
					//该关不是最后一关，请先删除最后一关，再进行操作！ 623
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NOT_LAST_LEVEL'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NOT_LAST_LEVEL']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}else {
					$sql = "select c.status from __PREFIX__cfs_operation o,__PREFIX__cfs_instruction_ctn c where o.id='$operation_id' and o.ctn_id=c.id";
					$res_c = M()->query($sql);
					if($res_c[0]['status'] == '7')
					{
							$operation = new \Common\Model\CfsOperationModel();
							$res_p = $operation->where("id='$operation_id'")->find();
							$halfclose_door_picture = $res_p['halfclose_door_picture'];
							$close_door_picture = $res_p['close_door_picture'];
							$res_s = $operation->where("id='$operation_id'")->delete();
							if($res_s !== false)
							{
								// 同步删除掉作业表中的半关门图片
								if ($halfclose_door_picture != '')
								{
									$halfclose_door_picture = '.' . $halfclose_door_picture;
									@unlink ('.'.IMAGE_CFS_CLOSEDOOR.$halfclose_door_picture );
								}
								// 同步删除掉作业表中的全关门图片
								if ($close_door_picture != '')
								{
									$close_door_picture = '.' .$close_door_picture;
									@unlink ('.'.IMAGE_CFS_CLOSEDOOR.$close_door_picture );
								}
								$ctn_id = $res_p['ctn_id'];
								$data = array(
										'status'=>'1',
								);
								$back = array(
										'halfclose_door_picture'=>'',
										'close_door_picture'=>''
								);
								$res_e = $operation->where("id='$operation_id'")->save($back); 
								$container = new \Common\Model\CfsInstructionCtnModel();
								$res_i = $container->where("id='$ctn_id'")->save($data);
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
					}
					// 同步删除掉作业关表中的关图片
// 					$res_b = $level->where("id='$level_id'")->find();
// 					if ($res_b !== false)
// 					{
// 						$level_img = '.'.IMAGE_CFS_CARGO. $res_b['level_img'];
// 						@unlink ( $level_img );
// 					}
					
					$res_l = $level->where ( "id='$level_id'" )->delete ();
					if ($res_l !== false) 
					{
						//获取关的残损照片并删除
						$cargo_damage = new \Common\Model\CfsCargoDamageImgModel();
						$imglist = $cargo_damage->where ( "level_id='$level_id'" )->select ();
						if ($imglist !== false)
						{
							// 删除关的货残损图片数据
							$res_d = $cargo_damage->where ( "level_id='$level_id'" )->delete ();
							if ($res_d !== false) {
								// 获得货残损图片路径，删除图片
								foreach ( $imglist as $l ) {
									$img = '.'.IMAGE_CFS_CDAMAGE. $l ['img'];
									@unlink ($img );
								}
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
										'msg' => '成功'
								);
							} else {
								//数据库连接错误 2
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}
						// 同步删除掉作业关中的货物图片
						$level_cargo = D('cfs_level_cargo_img');
						$res_s = $level_cargo->where ( "level_id='$level_id'" )->select ();
						if($res_s !== false)
						{
							$res_a = $level_cargo->where ( "level_id='$level_id'" )->delete ();
							if($res_a !== false)
							{
								foreach ($res_s as $vo)
								{
									$img = '.' . IMAGE_CFS_CARGO . $l ['level_img'];
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
					}else{
						// 数据库连接错误
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
						);
					}
				}
			} else {
				//该箱已被其他理货员操作，请勿重复操作! 601
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']] 
				);
			}
		} else {
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']] 
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
			$container=new \Common\Model\CfsInstructionCtnModel();
			$res_c=$container->where("id=$ctn_id")->find();
			$operator_id=$res_c['operator_id'];
			//检查箱的操作员是否和用户符合，不符合禁止操作
			if($uid == $operator_id)
			{
				$operation=new \Common\Model\CfsOperationModel();
				$res_d = $operation->where("ctn_id=$ctn_id")->field('id')->find();
				$operation_id = $res_d['id'];
				//判断配箱下是否有关存在，一关没有的情况下不允许完成操作
				$level = new \Common\Model\CfsOperationLevelModel();
				$lnum=$level->where("operation_id='$operation_id'")->count();
				if($lnum==0)
				{
					// 该箱没有录关，不能进行半关门操作 626
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//该配箱属于操作员，可以进行操作
				$sql = "select i.id from __PREFIX__cfs_instruction_ctn c,tally_cfs_instruction i where c.instruction_id=i.id and c.id='$ctn_id'";
				$res_i = M ()->query ( $sql );
				//指令ID
				$instruction_id = $res_i [0] ['id'];
				// 半关门照片不能为空
				if (I ( 'post.halfclose_door_picture' ) == '')
				{
					//该箱必须实际作业，请拍摄半关门照片！606
					$res = array (
							'code' =>$this->ERROR_CODE_OPERATION['NEED_HALFCLOSE_DOOR_PICTURE'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NEED_HALFCLOSE_DOOR_PICTURE']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//半关门照片
				if(I('post.halfclose_door_picture'))
				{
					$halfclose_door_picture = I ( 'post.halfclose_door_picture' );
					$path_o = '.'.IMAGE_CFS_HALFCLOSEDOOR;
					$res_o = base64_upload ( $halfclose_door_picture, $path_o );
					if ($res_o ['code'] != 0)
					{
						//图片上传失败
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
								'msg' => $res_o ['msg']
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit ();
					} else {
						$halfclose_door_file = $res_o ['file'];
						$halfclose_door_img = $res_o ['file'];
					}
				} else {
					$halfclose_door_img = '';
				}
				$cfs_step=json_decode(cfs_step,true);
				$data=array(
						'halfclose_door_picture'=>$halfclose_door_img,
						'step'=>$cfs_step['halfclosedoor']
				);
				$res_o = $operation->where("ctn_id=$ctn_id")->save($data);
				if($res_o !== false)
				{
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功'
					);
				}else{
					//需要删除已上传的半关门照
					@unlink($path_o.$halfclose_door_img);
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}else{
				//该箱已被其它理货员操作，请勿重复操作！ 601
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
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
	 * 关门
	 * @param int $uid:用户ID
	 * @param int $ctn_id:箱ID
	 * @param array $close_door_picture:全关门照片
	 * @param array $cargo_weight:货物重量
	 */
	public function closeDoor()
	{
		if(I('post.uid') and I('post.ctn_id') and I('post.cargo_weight')!=null and I('post.close_door_picture') )
		{
			$uid = I('post.uid');
			$ctn_id = I('post.ctn_id');
			$cargo_weight = I('post.cargo_weight');
			$container=new \Common\Model\CfsInstructionCtnModel();
			$res_c=$container->where("id=$ctn_id")->find();
			$operator_id=$res_c['operator_id'];
			//检查箱的操作员是否和用户符合，不符合禁止操作
			if($uid == $operator_id)
			{
				$operation=new \Common\Model\CfsOperationModel();
				$res_d = $operation->where("ctn_id=$ctn_id")->field('id')->find();
				$operation_id = $res_d['id'];
				//判断配箱下是否有关存在，一关没有的情况下不允许完成操作
				$level = new \Common\Model\CfsOperationLevelModel();
				$lnum=$level->where("operation_id='$operation_id'")->count();
				if($lnum==0)
				{
					// 该箱没有录关，不能进行关门操作 626
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//该配箱属于操作员，可以进行操作
				$sql = "select i.id from __PREFIX__cfs_instruction_ctn c,__PREFIX__cfs_instruction i where c.instruction_id=i.id and c.id='$ctn_id'";
				$res_i = M ()->query ( $sql );
				//指令ID
				$instruction_id = $res_i [0] ['id'];
				//全关门照片不能为空
				if (I('post.close_door_picture') == '')
				{
					//请拍摄全关门照片！ 603
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
					$path_t = '.'.IMAGE_CFS_CLOSEDOOR;
					$res_t = base64_upload ( $close_door_picture, $path_t );
					if ($res_t ['code'] != 0)
					{
						//图片上传失败
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
								'msg' => $res_t ['msg']
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit ();
					} else {
						$close_door_file = $res_t ['file'];
						$close_door_img = $res_t ['file'];
					}
				} else {
					$close_door_img = '';
				}
				$cfs_step=json_decode(cfs_step,true);
				$data=array(
						'close_door_picture'=>$close_door_img,
						'cargo_weight'=>$cargo_weight,
						'step'=>$cfs_step['closedoor']
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
					$res_o = $operation->where("ctn_id=$ctn_id")->save($data);
					if($res_o !== false)
					{
						$res = array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'成功'
						);
					}else{
						//需要对已上传的图片进行删除
						@unlink($path_t.$close_door_img);
						$res = array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}
				
			}else{
				//该箱已被其它理货员操作，请勿重复操作！ 601
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
			}
		}else{
			\Think\Log::record('uid='.I('post.uid') .';ctn_id='. I('post.ctn_id') .';cargo_weight='.  I('post.cargo_weight') . ';close_door_picture='.I('post.close_door_picture'));
			//参数缺失 参数不正确  3
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 完成CFS装箱作业
	 * @param int $uid:用户ID
	 * @param int $ctn_id:箱ID
	 * @param array $sealno:铅封号
	 * @param array $seal_picture:铅封照片
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function operationFinish()
	{
		if(I('post.uid') and I('post.ctn_id') and I('post.sealno'))
		{
			$uid = I('post.uid');
			$ctn_id = I('post.ctn_id');
			$sealno = I('post.sealno');
			$container=new \Common\Model\CfsInstructionCtnModel();
			$res_c=$container->where("id=$ctn_id")->field('operator_id,status')->find();
			//判断该箱是否已完成，完成不准重复提交
			if($res_c['status']=='2')
			{
				//该箱已完成，请勿重复操作！ 625
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
				$operation=new \Common\Model\CfsOperationModel();
				$res_d = $operation->where("ctn_id=$ctn_id")->field('id')->find();
				$operation_id = $res_d['id'];
				//判断配箱下是否有关存在，一关没有的情况下不允许完成操作
				$level = new \Common\Model\CfsOperationLevelModel();
				$lnum=$level->where("operation_id='$operation_id'")->count();
				if($lnum==0)
				{
					// 该箱没有录关，不能进行完成操作 626
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD'] ,
							'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['NO_LEVEL_RECORD']]
					);
					echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
					exit ();
				}
				//该配箱属于操作员，可以进行操作
				$sql = "select i.id from __PREFIX__cfs_instruction_ctn c,__PREFIX__cfs_instruction i where c.instruction_id=i.id and c.id='$ctn_id'";
				$res_i = M ()->query ( $sql );
				//指令ID
				$instruction_id = $res_i [0] ['id'];
				
				if (I ( 'post.seal_picture' ) == '')
				{
					// 该箱必须实际作业，铅封照片不能为空  602
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
					$path_e = '.'.IMAGE_CFS_SEAL;
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
				$cfs_step=json_decode(cfs_step,true);
				$data=array(
						'sealno'=>$sealno,
						'seal_picture'=>$seal_img,
						'step'=>$cfs_step['finished']
				);
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
						'msg'=>'CFS装箱作业完成！'
					);
				}else{
					//需要对已上传的图片进行删除
					@unlink($path_e.$seal_img);
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}else{
				//该箱已被其它理货员操作，请勿重复操作！ 601
				$res=array(
						'code'=>$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg'=>$this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
			}
		}else{
			//参数缺失，参数不正确 3
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
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
			$container = new \Common\Model\CfsInstructionCtnModel();
			$res_i = $container->where ( "id=$ctn_id" )->field ( 'operator_id' )->find ();
			$operator_id = $res_i['operator_id'];
			// 判断用户是否有权限对箱进行操作
			if ($uid == $operator_id)
			{
				//该箱属于用户，可以对其操作
				$operation = new \Common\Model\CfsOperationModel();
				$res_o = $operation->where ( "ctn_id=$ctn_id" )->field ( 'id' )->find ();
				$operation_id = $res_o ['id'];
				if ($operation_id == '')
				{
					// 只接单尚未作业，只需初始化箱状态与箱操作员
					$data = array (
							'status' => '0',
							'operator_id' => null
					);
					$res_s = $container->where("id=$ctn_id")->save($data);
					if ($res_s !== false)
					{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功'
						);
					} else {
						//数据库错误 2
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				} else {
					// 已接单并开始作业
					$level = new \Common\Model\CfsOperationLevelModel();
					$levelnumber = $level->where ( "operation_id=$operation_id" )->count ();
					if ($levelnumber > 0)
					{
						// 箱下存在关记录，不允许取消 624
						$res = array (
								'code' => $this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD'],
								'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['HAVE_LEVEL_RECORD']]
						);
						echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
						exit();
					} else {
						// 箱下不存在关记录，允许取消
						// 对空箱，作业表的删除操作
					    $empty_img = new \Common\Model\CfsCtnEmptyImgModel();
					    $operation = new \Common\Model\CfsOperationModel();
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
								$img = '.'.IMAGE_CFS_EMPTY . $l ['empty_img'];
								@unlink ($img );
							}
						   }
							// 初始化箱状态与箱操作员
							$data = array (
									'status' => '0',
									'operator_id' => null
							);
							$res_c = $container->where ( "id=$ctn_id" )->save ( $data );
							if ($res_c !== false)
							{
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
										'msg' => '成功'
								);
							} else {
								//数据库错误 2
								$res = array (
										'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						} else {
							//数据库错误 2
							$res = array (
									'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			} else {
				//用户没有权限对该箱操作 601
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit();
			}
		} else {
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
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
			$container=new \Common\Model\CfsInstructionCtnModel();
			$containerMsg=$container->getContainerMsg($ctn_id);
			//根据箱ID获取作业详情
			$operationModel=new \Common\Model\CfsOperationModel();
			$operationMsg=$operationModel->getOperationMsgByCtn($ctn_id);
			//根据作业ID获取关列表
			if($operationMsg['id'])
			{
				$operation_id=$operationMsg['id'];
				$level=new \Common\Model\CfsOperationLevelModel();
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
	 	writeLog(json_encode($_POST));
		if (I ( 'post.sealno' ) and I ( 'post.uid' ) and I ( 'post.ctn_id' )) 
		{
			$sealno = strtoupper ( I ( 'post.sealno' ) );
			$sealno = str_replace("'", "", $sealno);
			$uid = I ( 'post.uid' );
			$ctn_id = I ( 'post.ctn_id' );
			$cargo_weight = I ( 'post.cargo_weight' );
			// 查看铅封号是否重复
			$operation = new \Common\Model\CfsOperationModel();
			$res_s = $operation->where ( "sealno='$sealno'" )->find ();
			if ($res_s) 
			{
				//该铅封号已存在 631
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['SEALNO_EXIST'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['SEALNO_EXIST']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
				// 保存集装箱信息
				$back = array (
						'sealno' => $sealno,
						'cargo_weight' => $cargo_weight,
						'is_reservation'=>'Y'
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
					$res_o = $operation->where ( "ctn_id='$ctn_id'" )->save ( $back );
					if ($res_o !== false)
					{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功'
						);
					} else {
						//数据库连接错误 2
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}	
		} else {
			//参数缺失 参数不正确 3
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
			$operation = new \Common\Model\CfsOperationModel();
			$res_u=$operation->where("ctn_id='$ctn_id'")->field('operator_id')->find();
			if($res_u['operator_id']!=$uid)
			{
				//该箱已被其他理货员操作，您没有权限对其操作！ 601
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH[$this->ERROR_CODE_OPERATION['OPERATION_ALREADY_HANDLED']]
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}else {
				//6-暂停作业 记录临时铅封号和暂停标志
				$data=array(
						'tmp_sealno'=>trim(I('post.tmp_sealno'),"'"),
						'is_stop'=>'Y'
				);
				$res_s=$operation->where("ctn_id='$ctn_id'")->save($data);
				if($res_s!==false)
				{
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
							'msg' => '成功'
					);
				}else {
					//数据库错误 2
					$res = array (
							'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
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
			$operation = new \Common\Model\CfsOperationModel();
			$res_u=$operation->where("ctn_id='$ctn_id'")->find();
			if ($res_u ['operator_id'] != $uid) 
			{
				// 该箱已被其他理货员操作 不得重复操作 601
				$res = array (
						'code' => $this->ERROR_CODE_OPERATION ['OPERATION_ALREADY_HANDLED'],
						'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['OPERATION_ALREADY_HANDLED']] 
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			} else {
				// 判断作业中箱子是否为暂停作业状态
				// 先判断理货员工作中的箱子是否已有3个
				$sql = "select c.id from __PREFIX__cfs_operation o,__PREFIX__cfs_instruction_ctn c where c.id=o.ctn_id and c.operator_id='$uid' and o.operator_id='$uid' and c.status in (1) and is_stop!='Y'";
				$res_n = M ()->query ( $sql );
				$n = count ( $res_n );
				if ($n >= 3) 
				{
					// 您已有3个正在工作的箱，不允许接更多箱进行作业！ 627
					$res = array (
							'code' => $this->ERROR_CODE_OPERATION ['HAVE_THREEOPERATION_CTN'],
							'msg' => $this->ERROR_CODE_OPERATION_ZH [$this->ERROR_CODE_OPERATION ['HAVE_THREEOPERATION_CTN']] 
					);
				} else {
					$data = array(
							'is_stop'=>'N'
					);
					$res_s = $operation->where ( "ctn_id=$ctn_id" )->save ( $data );
					if ($res_s !== false) 
					{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '恢复作业成功' 
						);
					} else {
						//数据库错误 2
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']] 
						);
					}
				}
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
}