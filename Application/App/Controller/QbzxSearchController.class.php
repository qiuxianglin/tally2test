<?php
/**
 * 起驳装箱业务类
 * 查询管理接口
 */

namespace App\Controller;
use App\Common\BaseController;

class QbzxSearchController extends BaseController
{
	/**
	 * 起驳装箱作业指令查询
	 * @param $location_id:作业地点id
	 * @param $ship_id:船id
	 * @param $voyage :航次
	 * @param $ordertime:指令下达日期
	 * @param $status:指令状态
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param result:成功时返回指令查询结果列表
	 */
	public function instruction()
	{
		$location = new \Common\Model\LocationModel();
		$ship = new \Common\Model\ShipModel();
		$plan = new \Common\Model\QbzxPlanModel();
		$instruction = new \Common\Model\QbzxInstructionModel();
		
		$where = '1';
		//根据船名，航次获取预报计划信息
		if(I('post.ship_id') or I('post.voyage'))
		{
			$ship_name = I('post.ship_id');
			$ship = new \Common\Model\ShipModel();
			$shipMsg = $ship->where("ship_name='$ship_name'")->field('id')->find();
			$ship_id = $shipMsg['id'];
			$voyage = I('post.voyage');
			$planlist = $plan->where("ship_id='$ship_id' or voyage='$voyage'")->field('id')->find();
			$plan_id = $planlist['id'];
			$where .= " and plan_id='$plan_id'";
		}
		
 		if (I ( 'post.location_id' )) {
			$location_id = I ( 'post.location_id' );
			$where .= " and location_id='$location_id'";
		}
		if (I('post.ordertime'))
		{
			$ordertime = I('post.ordertime');
			$where .= " and ordertime='$ordertime'";
		}
		if (I('post.status') !== '' && I('post.status') !== null)
		{
			$status = I('post.status');
			$where .= " and status='$status'";
		}
			
		$list = $instruction->where ( $where )->order("id desc")->limit(0,50)->select ();
		
		if($list !== false)
		{
			$n = count ( $list );
			for($i = 0; $i < $n; $i ++) 
			{
				$plan_id = $list[$i]['plan_id'];
				$location_id = $list[$i]['location_id'];
				//根据作业ID查询作业地点名
				$locationlist = $location->where("id='$location_id'")->field('location_name')->find();
				$list[$i]['location_name'] = $locationlist['location_name'];
			
				// 指令状态
				$instruction_status_d = json_decode ( instruction_status_d, true );
				$status_zh = $instruction_status_d [$list [$i] ['status']];
				$list [$i] ['status_zh'] = $status_zh;
					
				//装箱方式
				$operate_contanier_method_d = json_decode ( operate_contanier_method_d, true );
				$loadingtype = $operate_contanier_method_d[$list[$i]['loadingtype']];
				$list[$i]['loadingtype_zh'] = $loadingtype;
				
				// 根据预报计划ID查询委托编号
				$planMsg = $plan->where("id='$plan_id'")->field('entrustno')->find();
				$list[$i]['entrustno'] = $planMsg['entrustno'];
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
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 起驳装箱作业指令详情
	 * @param int $instruction_id:指令ID
	 * @param @return array
	 * @param @return code:返回码
	 * @param @return msg:返回码说明
	 * @param @return
	 */
	public function instructionDetail()
	{
		if(I('post.instruction_id'))
		{
			$instruction_id = I('post.instruction_id');
			//根据指令ID获取指令详情
			$instruction = new \Common\Model\QbzxInstructionModel();
			$instructionMsg = $instruction->where("id='$instruction_id'")->find();
			if($instructionMsg !== false)
			{
				// 指令状态
				$instruction_status_d = json_decode ( instruction_status_d, true );
				$istatus_zh = $instruction_status_d [$instructionMsg['status']];
				$instructionMsg['status_zh'] = $istatus_zh;
				//装箱方式
				if($instructionMsg['loadingtype'] == '0')
				{
					$instructionMsg['loadingtype_zh'] = '人工';
				}else{
					$instructionMsg['loadingtype_zh'] = '机械';
				}
				//指令理货地点
				$location = new \Common\Model\LocationModel();
				$location_name = $location->getLocationMsg($instructionMsg['location_id']);
				$instructionMsg['location_name'] = $location_name['location_name'];
				//根据指令ID获取配箱列表
				$container = new \Common\Model\QbzxInstructionCtnModel();
				$ctnlist = $container->getContainerList($instruction_id);
				//指令已配箱数
				$instructionMsg['has_container_num'] = count($ctnlist);
				
				for($i=0;$i<count($ctnlist);$i++)
				{
					// 箱状态
					$ctn_status_d = json_decode ( ctn_status_d, true );
					$status_zh = $ctn_status_d [$ctnlist [$i] ['status']];
					$ctnlist [$i] ['status_zh'] = $status_zh;
				}
				//根据预报计划ID获取配货列表
				$plan_id = $instructionMsg['plan_id'];
				$plan = new \Common\Model\QbzxPlanModel();
				$cargo = new \Common\Model\QbzxPlanCargoModel();
				$cargolist = $cargo->getCargoList($plan_id);
				
				//根据预报计划ID获取预报计划详情
				$planMsg = $plan->getPlanMsg($plan_id);
				
				if($planMsg !== false)
				{
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'instructionMsg'=>$instructionMsg,
							'ctnlist'=>$ctnlist,
							'cargolist'=>$cargolist,
							'planMsg'=>$planMsg
					);
				}else{
					//数据库连接错误 2
					$res = array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
							'instructionMsg'=>'',
							'ctnlist'=>'',
							'cargolist'=>'',
							'planMsg'=>''
					);
				}
			}else{
				//数据库连接错误 2
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else{
			//参数缺失 参数不正确 3
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	
	/**
	 *  查询起驳装箱预报计划
	 *  @param $location_id:作业地点ID
	 *  @param $voyage:航次
	 *  @param $entrust_time:委托时间
	 *  @param $entrust_company:委托单位ID
	 *  @param $ship_id:船ID
	 *  @return array|boolean
	 *  @return @param code:返回码
	 *  @return @param msg:返回码说明
	 *  @return @param list:成功时返回相应列表
	 */
	public function qbzxplan() {
		
		$ship = new \Common\Model\ShipModel();
		$location = new \Common\Model\LocationModel();
		$customer = new \Common\Model\CustomerModel();
		$where = '1';
		
		if (I('post.location_id'))
		{
			$location_id = I('post.location_id');
			$where .= " and location_id='$location_id'";
		}
		
		if (I('post.ship_id'))
		{
			$ship_name = I('post.ship_id');
			$ship = new \Common\Model\ShipModel();
			$shipMsg = $ship->where("ship_name='$ship_name'")->field('id')->find();
			$ship_id = $shipMsg['id'];
			$where .= " and ship_id='$ship_id'";
		}
		
		if (I('post.voyage'))
		{
			$voyage = I('post.voyage');
			$where .= " and voyage like '$voyage%'";
		}
		
		if (I('post.entrust_company'))
		{
			$entrust_company = I('post.entrust_company');
			$where .= " and entrust_company='$entrust_company'";
		}
		
		if (I('post.entrust_time'))
		{
			$entrust_time = I('post.entrust_time');
			$where .= " and entrust_time='$entrust_time'";
		}
	
		$plan = new \Common\Model\QbzxPlanModel();
		$list = $plan->where ( $where )->order("id desc")->limit(0,50)->select ();
		if($list !== false)
		{
			$n = count ( $list );
			for($i = 0; $i < $n; $i ++) {
					
				$res_a = $ship->where ( "id=" . $list [$i] ['ship_id'] )->find ();
				$res_b = $location->where ( "id=" . $list [$i] ['location_id'] )->find ();
				$res_c = $customer->where ( "id=" . $list [$i] ['entrust_company'] )->find ();
			
				$list [$i] ['ship_name'] = $res_a ['ship_name'];
				$list [$i] ['location_name'] = $res_b ['location_name'];
				$list [$i] ['entrust_name'] = $res_c['customer_name'];
			}
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$list,
					
			);
		}else{
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list'=>''
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 预报计划详情
	 * @param int $plan_id:预报计划ID
	 * @param @return array
	 * @param @return code:返回码
	 * @param @return msg:返回码说明
	 * @param @return content:成功时返回相应预报计划信息
	 * @param @return cargolist:成功时返回相应的预报配货信息
	 * @param @return ctnlist:成功时返回相应的预报配箱信息
	 */
	public function planDetail()
	{
		if(I('post.plan_id'))
		{
			$plan_id = I('post.plan_id');
			$plan = new \Common\Model\QbzxPlanModel();
			$planMsg = $plan->getPlanMsg($plan_id);
			if($planMsg !== false)
			{
				//根据预报计划获取预报下配货列表
				$cargo = new \Common\Model\QbzxPlanCargoModel();
				$cargolist = $cargo->getCargoList($plan_id);
				
				//根据预报计划获取预报下配箱列表
				$container = new \Common\Model\QbzxPlanCtnModel();
				$ctnlist = $container->getContainerList($plan_id);
				$n = count($ctnlist);
				for($i=0;$i<$n;$i++)
				{
					if($ctnlist[$i]['flflag'] == 'F')
					{
						$ctnlist[$i]['flflag_zh'] = '整箱';
					}else{
						$ctnlist[$i]['flflag_zh'] = '拼箱';
					}
				}
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'content'=>$planMsg,
						'cargolist'=>$cargolist,
						'ctnlist'=>$ctnlist
				);
			}else{
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>'',
						'cargolist'=>'',
						'ctnlist'=>''
				);
			}
		}else{
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode( $res, JSON_UNESCAPED_UNICODE );
	}
	
	
	/**
	 * 作业明细查询
	 * @param string $ship_name:船名
	 * @param string $voyage:航次
	 * @param string $ctnno:箱号
	 * @param @return array
	 * @param @return code:返回码
	 * @param @return msg:返回码说明
	 * @param @return ctnMsg:成功时返回箱详情
	 * @param @return operationMsg:成功时返回作业详情
	 * @param @return levellist:成功时返回对应的关列表
	 */
	public function operation() 
	{
		if(I('post.voyage') and I('post.ship_name') and I('post.ctnno'))
		{
			$ship_name = I('post.ship_name');
			$ship = new \Common\Model\ShipModel();
			$shipMsg = $ship->where("ship_name='$ship_name'")->field('id')->find();
			if(empty($shipMsg['id'])){
				$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'], // 3
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']] //参数不正确，参数缺失
				);//
			} else {
				$ship_id = $shipMsg['id'];
				$voyage = I('post.voyage');
				//根据船ID和航次查询预报计划详情
				$plan = new \Common\Model\QbzxPlanModel();
				$planMsg = $plan->where("ship_id='$ship_id' and voyage='$voyage'")->find();
				if(empty($planMsg['id'])){
					$res = array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'], // 3
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']] //参数不正确，参数缺失
					);//
//
				} else {
					$plan_id = $planMsg['id'];
					//根据预报计划ID确定指令详情
					$instruction = new \Common\Model\QbzxInstructionModel();
					$insturctionMsg = $instruction->where("plan_id='$plan_id'")->find();
					$instruction_id = $insturctionMsg['id'];
					$ctnno = strtoupper ( I ( 'post.ctnno' ) );
					//根据箱号和指令ID获取唯一箱ID
					$container = new \Common\Model\QbzxInstructionCtnModel();
					$ctnMsg = $container->where("instruction_id='$instruction_id' and ctnno='$ctnno'")->find();
					if($ctnMsg !== false)
					{
						$ctn_id = $ctnMsg['id'];
						// 箱状态
						$ctn_status_d = json_decode ( ctn_status_d, true );
						$status_zh = $ctn_status_d [$ctnMsg ['status']];
						$ctnMsg ['status_zh'] = $status_zh;
						//根据箱ID查询作业详情
						$operation = new \Common\Model\QbzxOperationModel();
						$operationMsg = $operation->getOperationMsg($ctn_id);
						if($operationMsg !== false)
						{
							$operation_id = $operationMsg['id'];
							//根据作业ID查询关列表
							$level = new \Common\Model\QbzxOperationLevelModel();
							$levellist = $level->where("operation_id='$operation_id'")->select();
							if($levellist !== false)
							{
								$res = array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'成功',
										'ctnMsg'=>$ctnMsg,
										'operationMsg'=>$operationMsg,
										'levellist'=>$levellist
								);
							}else{
								$res = array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'成功',
										'ctnMsg'=>$ctnMsg,
										'operationMsg'=>$operationMsg,
										'levellist'=>''
										
								);
							}
						}else{
							$res = array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'成功',
									'ctnMsg'=>$ctnMsg,
									'operationMsg'=>$operationMsg
							);
						}
					}else{
						//数据库连接错误
						$res = array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
					
				}
				
			}
		}else{
			//参数不正确 参数缺失
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'], // 3
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']] //参数不正确，参数缺失
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	
	/**
	 *  查询历史交班记录 
	 *  @param string $department_code:部门 代码
	 *  @param date $date:签到日期
	 *  @param int  $classes:班次 白班/夜班
	 *  @return array|boolean
	 *  @param @return code:返回码
	 *  @param @return msg:返回码说明
	 *  @param @return content:成功时返回相关信息
	 */
	public function shift() {
		if(I('post.department_code') and I('post.date') and I('post.classes'))
		{
			$user = new \Common\Model\UserModel();
			
			$department_code = strtoupper(I ( 'department_code' ));
			$date = I ( 'post.date' );
			$date = trim ( $date, '-' );
			$classes = I ( 'post.classes' );
			
			$shift_id = $deptcode . $date . $classes;
			$shiftdetail = new \Common\Model\ShiftDetailModel();
			$msg = $shiftdetail->where ( "shift_id='$shift_id'" )->find ();
			
			if($list)
			{
				// 由用户id得到name(交班人名称)
				$msga = $user->where ( "uid='" . $msg ['user_exchanged_id'] . "'" )->find ();
				$msg ['user_exchanged_name'] = $msga['user_name'];
				// 由用户id得到name(接班人名称)
				$msgb = $user->where ( "uid='" . $msg ['user_carryon_id'] . "'" )->find ();
				$msg ['user_carryon_name'] = $msgb['user_name'];
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'content'=>$msg
				);
			}else{
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
				);
			}
			
		}else{
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']],
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}	
	
	/**
	 *  查询集装箱单证
	 *  @param $ctnno:箱号
	 *  @param $ship_name:船名
	 *  @param $voyage:航次
	 *  @return array|boolean
	 *  @param @return code:返回码
	 *  @param @return msg:返回码说明
	 *  @param @return list:成功时返回相应信息吧  
	 */
	public function prove() {
		$where = '1';
		if (I ( 'post.ctnno' )) 
		{
			$ctnno = I ( 'post.ctnno' );
			$where .= " and ctnno like '$ctnno%' ";
		}
		if (I ( 'post.ship_name' )) 
		{
			$ship_name = I ( 'post.ship_name' );
			$where .= " and ship_name='$ship_name' ";
		}
		if (I ( 'post.voyage' )) 
		{
			$voyage = I ( 'post.voyage' );
			$where .= " and voyage like '$voyage%' ";
		}
		// 根据条件查询参数
		$prove = new \Common\Model\QbzxProveModel();
		$list = $prove->where ( $where )->order("id desc")->limit(0,50)->select ();
		
		if($list !== false)
		{
			for($i = 0; $i < count ( $list ); $i ++) 
			{
				// 将数据库保存的json格式字符串转为数组
				$list [$i] ['content'] = json_decode ( $list [$i] ['content'] );
			
				// 显示整拼箱汉字
				if ($list [$i] ['flflag'] == 'F')
				{
					$list [$i] ['flflag_zh'] = '整箱';
				}else{
					$list [$i] ['flflag_zh'] = '拼箱';
				}
			
				// 显示装拆箱方式汉字
				if ($list [$i] ['loadingtype'] == '1'){
					$list [$i] ['loadingtype_zh'] = '机械';
				}	
				else{
					$list [$i] ['loadingtype_zh'] = '人工';
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
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	
	/**
	 * 单证详情
	 * @param int $prove_id:单证ID
	 * @param @return array
	 * @param @return code:返回码
	 * @param @return msg:返回码说明
	 * @param @return proveMsg:成功时返回相应单证详情
	 * @param @return content:成功时返回相应信息
	 */
	public function proveDetail()
	{
		if(I('post.prove_id'))
		{
			$prove_id = I('post.prove_id');
			$prove = new \Common\Model\QbzxProveModel();
			$proveMsg = $prove->where("id='$prove_id'")->find();
			if($proveMsg !== false)
			{
				$content = json_decode($proveMsg['content'],true);
				//整拼标志
				if($proveMsg['flflag'] == 'F')
				{
					$proveMsg['flflag_zh'] = '整箱';
				}else{
					$proveMsg['flflag_zh'] = '拼箱';
				}
				//装箱方式
				if($proveMsg['loadingtype'] == '0')
				{
					$proveMsg['loadingtype_zh'] = '人工';
				}else{
					$proveMsg['loadingtype_zh'] = '机械';
				}
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'proveMsg'=>$proveMsg,
						'content'=>$content
				);
			}else{
				//数据库连接错误 2
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else{
			//参数缺失  参数不正确 3
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 实时作业查询
	 * @param string $ship_name: 中文船名
	 * @param string $voyage:航次
	 * @param string $location_name:作业地点
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回指令查询结果列表
	 */
	public function realtime() {
		// 集装箱船列表
		$ship = new \Common\Model\ShipModel ();
		// 作业场地
		$location = new \Common\Model\LocationModel ();
		
		$where = "c.status in (1) and c.operator_id!=''";
		if (I ( 'post.ship_name' )) 
		{
			$ship_name = I ( 'post.ship_name' );
			// 根据船名获取船ID
			$res_s = $ship->where ( "ship_name='$ship_name'" )->field ( 'id' )->find ();
			$ship_id = $res_s ['id'];
			$where .= " and p.ship_id='$ship_id'";
		}
		if (I ( 'post.voyage' )) 
		{
			$voyage = I ( 'post.voyage' );
			$voyage = str_replace ( "'", " ", $voyage );
			$where .= " and p.voyage='$voyage'";
		}
		if (I ( 'post.location_name' )) 
		{
			$location_name = I ( 'post.location_name' );
			// 根据理货地点获取理货地点ID
			$res_l = $location->where ( "location_name='$location_name'" )->field ( 'id' )->find ();
			$location_id = $res_l ['id'];
			$where .= " and i.location_id='$location_id'";
		}
		$sql = "select p.voyage,s.ship_name,l.location_name,c.* from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_instruction_ctn c ,__PREFIX__location l,__PREFIX__qbzx_plan p,__PREFIX__ship s where i.id=c.instruction_id and l.id=i.location_id and i.plan_id=p.id and p.ship_id=s.id and $where order by c.id desc";
		$list = M ()->query ( $sql );
		if ($list !== false) 
		{
			// 遍历结果，取出其它数据
			$num = count ( $list );
			$level = new \Common\Model\QbzxOperationLevelModel ();
			for($i = 0; $i < $num; $i ++) 
			{
				$ctn_id = $list [$i] ['id'];
				$operation = new \Common\Model\QbzxOperationModel ();
				$res_o = $operation->where ( "ctn_id=$ctn_id" )->find ();
				if ($res_o ['id'] != '') 
				{
					$operation_id = $res_o ['id'];
					$list [$i] ['begin_time'] = $res_o ['begin_time'];
					// 关数
					$levelnum = $level->sumLevelNum ( $operation_id );
					$list [$i] ['level_num'] = $levelnum;
					// 货物件数
					$cargonum = $level->sumCargoNum ( $operation_id );
					$list [$i] ['cargo_number'] = $cargonum;
					// 残损件数
					$damage_num = $level->sumDamageNum ( $operation_id );
					$list [$i] ['damage_num'] = $damage_num;
					// 最新操作时间
					$res_new = $level->where ( "operation_id=$operation_id" )->field ( 'createtime' )->order ( "id desc" )->find ();
					if ($res_new ['createtime'] != '') 
					{
						$list [$i] ['newtime'] = $res_new ['createtime'];
					} else {
						$list [$i] ['newtime'] = $res_o ['begin_time'];
					}
					$time1 = strtotime ( $list [$i] ['newtime'] );
					$time2 = time ();
					if (($time2 - $time1) / 300 > 1) {
						$list [$i] ['red'] = 1;
					}
				}
			}
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
					'msg' => '成功',
					'list' => $list 
			);
		} else {
			//数据库连接错误
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list' => '' 
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	
	/**
	 * 实时作业详情
	 * @param int $ctn_id:箱id
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param ctn_msg:成功时返回符合条件的箱信息
	 * @return @param operation_msg:成功时返回符合条件的箱的作业信息
	 * @return @param instructionMsg:成功时返回符合条件的箱的关列表
	 * @return @param emptylist:成功时返回符合条件的空箱照片
	 * */
	public function realtimeDetail($ctn_id)
	{
		if(I('post.ctn_id')) {
			$ctn_id = I('post.ctn_id');
			//根据箱ID获取箱详情
			$container = new \Common\Model\QbzxInstructionCtnModel();
			$ctn_msg = $container->getContainerMsg($ctn_id);
			//根据指令ID查询关信息
			$instruction_id = $ctn_msg['instruction_id'];
			$sql = "select i.*,s.ship_name,p.voyage,l.location_name,c.billno,c.pack,c.mark from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_plan_cargo c, __PREFIX__location l,__PREFIX__ship s,__PREFIX__qbzx_plan p where i.id='$instruction_id' and l.id=i.location_id and s.id=p.ship_id and i.plan_id=p.id and p.id=c.plan_id order by i.id desc";
			$res_i = M()->query($sql);
			if ($res_i !== false) {
				$instructionMsg = $res_i[0];
				// 指令状态
				$instructionMsg = json_decode ( instruction_status_d, true );
				$status_zh = $instruction_status_d [$i ['status']];
				$instructionMsg['status_zh'] = $status_zh;
				//根据箱ID获取作业详情
				$operation = new \Common\Model\QbzxOperationModel();
				$operationMsg = $operation->getOperationMsgByCtn($ctn_id);
				$operation_id = $operationMsg['id'];
				//根据作业ID获取空箱照片
				$empty = new \Common\Model\QbzxEmptyCtnImgModel();
				$emptylist = $empty->where("operation_id='$operation_id'")->select();
				$res = array(
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg' => '成功',
						'ctn_msg' => $ctn_msg,
						'instructionMsg' => $instructionMsg,
						'operation_msg' => $operationMsg,
						'emptylist' => $emptylist
				);
			} else {
				//数据库连接错误
				$res = array(
						'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'ctn_msg' => '',
						'instructionMsg' => '',
						'operation_msg' => '',
						'emptylist' => ''
				);
			}
		}else{
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
}