<?php
/**
 * 港航系统发送预报计划接口
 */
namespace App\Controller;
use Think\Controller;

class DelegateController extends Controller
{
	//新增门到门拆箱预报计划接口
	public function dtd() 
	{
		writeLog(json_encode($_POST));
		if(I('post.requestdata') and I('post.sign'))
		{
			//①验证签名
			$sign = I('post.sign');
			$requestdata = $_POST['requestdata'];
			$str="requestdata=$requestdata&key=".KEY;
			$sign_str=md5($str);
			if($sign==$sign_str)
			{
				$dtd_departmentid=dtd_departmentid;
				$customer_paytype=json_decode(customer_paytype,true);
				$instruction_status=json_decode(instruction_status,true);
				//②验签通过，处理数据
				$request=simplexml_load_string($requestdata);
				$json=json_encode($request,JSON_UNESCAPED_UNICODE);
				$request=json_decode($json,true);
				//理货记录
				$bill=$request['BILL'];
				//判断联合国编号
				if(strlen($bill ['UNDGNO'])>9)
				{
					$res=array(
							'code'=>107,
							'msg'=>'联合国编号不能超过9位'
					);
					echo json_encode($res,JSON_UNESCAPED_UNICODE);
					exit();
				}
				//判断件数为整数
				if(is_numeric($bill ['NUMBERSOFPACKAGES'])===false)
				{
					$res=array(
							'code'=>108,
							'msg'=>'预报计划总件数为整数'
					);
					echo json_encode($res,JSON_UNESCAPED_UNICODE);
					exit();
				}
				//集装箱清单
				$ctn_str=json_encode($request['CTNS']['CTN']);
				if(strpos($ctn_str, '},')>0)
				{
					$ctns=$request['CTNS']['CTN'];
				}else {
					$ctns=$request['CTNS'];
				}
				if(!empty($bill['ORDERID']) and !empty($bill['ORDER_DATE']) and !empty($bill['VSLNAME']) and !empty($bill['VOYAGE']) and !empty($bill['BLNO']) and !empty($bill['APPLYCODE']) and !empty($bill['APPLYNAME']) and !empty($bill['PAYCODE']) and !empty($bill['PAYMEN']) and !empty($bill['LCL']) and !empty($ctns))
				{
					//③判断委托编号是否存在，存在不准重复提交
					$DdPlan=new \Common\Model\DdPlanModel();
					$planCargo=new \Common\Model\DdPlanCargoModel();
					$orderid=$bill ['ORDERID'];
					$res_dp=$DdPlan->where("orderid='$orderid'")->count();
					if($res_dp>0)
					{
						$res=array(
								'code'=>101,
								'msg'=>'该委托计划已存在，不能重复提交'
						);
						echo json_encode($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
					//判断客户是否合法
					$customer_code=strtoupper($bill ['PAYCODE']);
					$Customer=new \Common\Model\CustomerModel();
					$res_c=$Customer->is_valid($customer_code);
					if($res_c['code']!=0)
					{
						//客户不合法，返回码采用和判断客户是否合法的一致
						$res=$res_c;
						echo json_encode($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
					//判断船名是否存在
					$Ship=new \Common\Model\ShipModel();
					$ship_exist=$Ship->is_exist($bill ['VSLNAME']);
					if($ship_exist!==true)
					{
						$res=array(
								'code'=>701,
								'msg'=>'该船舶名称不存在！'
						);
						echo json_encode($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
					//判断拆箱地点是否存在
					$Location=new \Common\Model\LocationModel();
					$location_exist=$Location->is_exist($bill ['UNPACKAGINGPLACE']);
					if($location_exist!==true)
					{
						$res=array(
								'code'=>801,
								'msg'=>'该作业地点不存在！'
						);
						echo json_encode($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
					
					$blno=$bill ['BLNO'];
					//根据拼箱状态判断是否重复提交
					if($bill ['LCL']=='N')
					{
						$ctnno_arr=array();
						foreach ($ctns as $c)
						{
							$ctnno_arr[]=$c['CTNNO'];
						}
						// 判断是否存在重复箱子
						// 获取去掉重复数据的数组
						$unique_arr = array_unique( $ctnno_arr);
						// 获取重复数据的数组
						$repeat_arr = array_diff_assoc ( $ctnno_arr, $unique_arr );

						if(count($repeat_arr)>0)
						{
							$res=array(
									'code'=>105,
									'msg'=>'整箱状态时同一提单号下不能有相同箱子！'
							);
							echo json_encode($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
						//判断提单号是否存在
						$res_blno=$DdPlan->where("blno='$blno'")->find();
						if($res_blno)
						{
							$res=array(
									'code'=>106,
									'msg'=>'整箱状态时该提单号已存在！'
							);
							echo json_encode($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
					}
					if($bill ['LCL']=='Y')
					{
						//拼箱
						//提单号、箱号不能重复存在
						foreach ($ctns as $c)
						{
							$ctn_no=$c['CTNNO'];
							$sql="select p.id from __PREFIX__dd_plan p,__PREFIX__dd_plan_container c where p.blno='$blno' and c.ctnno='$ctn_no' and p.id=c.plan_id";
							$res_lcl=M()->query($sql);
							if($res_lcl[0]['id']!='')
							{
								$res=array(
										'code'=>104,
										'msg'=>'拼箱状态时同一箱里不能有重复提单号！'
								);
								echo json_encode($res,JSON_UNESCAPED_UNICODE);
								exit();
							}
						}
					}
					
					//根据申报公司代码判断客户类别，计算出应付金额
					//箱子总价
					$RateDetail=new \Common\Model\RateDetailModel();
					$totalPrice=$RateDetail->calculateTotalPrice($ctns);
					//客户对应费率标准折扣后应付总价
					$due=$Customer->due($customer_code, $totalPrice);
					if($due)
					{
						$amount=$due;
					}else {
						$amount=$totalPrice;
					}
					//根据客户代码获取客户信息
					$customer_msg=$Customer->getMsgByCode($customer_code);
					if($customer_msg['paytype']==$customer_paytype['month'])
					{
						$is_valid='Y';
						$rcvflag='0';
					}else {
						$is_valid='N';
						$rcvflag='0';
					}
					$paytype=$customer_msg['paytype'];
					//如果申报公司为理货公司本身，则结算方式为线下结算，预报计划也有效
					if($bill ['APPLYCODE']==COMPANY_CODE)
					{
						$paytype=0;
						$is_valid='Y';
						$rcvflag='1';
					}
					//如果运输方式为CFS、或者拆箱类别为港内拆箱，则该预报计划属于CFS装箱业务，不生成指令进行作业
					/* if($bill ['TRANSIT']=='CFS' OR $bill ['CATEGORY']=='1')
					{
						$is_valid='N';
					} */
					//保存预报计划
					$data = array (
							'orderid' => $bill ['ORDERID'],
							'orderdate' => $bill ['ORDER_DATE'],
							'vslname' => $bill ['VSLNAME'],
							'voyage' => $bill ['VOYAGE'],
// 							'blno' => $bill ['BLNO'],
							'applycode' => $bill ['APPLYCODE'],
							'applyname' => $bill ['APPLYNAME'],
// 							'paycode' => $bill ['PAYCODE'],
// 							'payman' => $bill ['PAYMEN'],
							'amount' => $amount,
							'paytype' => $paytype,
// 							'cargoname' => $bill ['CARGONAME'],
// 							'numbersofpackages' => $bill ['NUMBERSOFPACKAGES'],
// 							'package' => $bill ['PACKAGE'],
// 							'mark' => $bill ['MARK'],
							'rcvflag' => $rcvflag,
							'lcl' => $bill ['LCL'],
// 							'consignee' => $bill ['CONSIGNEE'],
							'unpackagingplace' => $bill ['UNPACKAGINGPLACE'],
							'operating_type' => $bill ['OPERATINGTYPE'],
// 							'classes' => $bill ['CLASSES'],
// 							'undgno' => $bill ['UNDGNO'],
// 							'contactuser' => $bill ['CONTACTUSER'],
// 							'contact' => $bill ['CONTACT'],
							'note' => $bill ['NOTE'],
							'transit' => $bill ['TRANSIT'],
							'category' => $bill ['CATEGORY'],
							'is_valid' => $is_valid
					);
					$plan_id=$DdPlan->add($data);
					if($plan_id!==false)
					{
						//新增配货
						$data=array(
								'plan_id'=>$plan_id,
								'blno'=>$bill ['BLNO'],
								'cargoname'=>$bill ['CARGONAME'],
								'numbersofpackages'=>$bill ['NUMBERSOFPACKAGES'],
								'package'=>$bill ['PACKAGE'],
								'mark'=>$bill ['MARK'],
								'classes'=>$bill ['CLASSES'],
								'last_operator'=>null,
								'last_operationtime'=>date('Y-m-d H:i:s'),
								'payman'   => $bill ['PAYMEN'],
								'paycode'   => $bill ['PAYMEN'],
								'consignee'  => $bill ['CONSIGNEE'],
								'undgno'     => $bill ['UNDGNO'],
								'contactuser'  => $bill ['CONTACTUSER'],
								'contact'  => $bill ['CONTACT']
						);
						
						if(!$planCargo->create($data))
						{
							//删除预报计划
							$DdPlan->where("id=$plan_id")->delete();
							//对data数据进行验证
							$this->error($planCargo->getError());
						}else{
							//通过验证 可以对数据进行操作
							$cargoplan = $planCargo->add($data);
							//保存预报计划配箱信息
							$PlanContainer=new \Common\Model\DdPlanContainerModel();
							foreach ($ctns as $c)
							{
								//判断联合国编号
								if(strlen($c ['UNDGNO'])>9)
								{
									//删除预报计划
									$DdPlan->where("id=$plan_id")->delete();
									//删除预报配货
									$planCargo->where("id=$cargoplan")->delete();
									$res=array(
											'code'=>107,
											'msg'=>'联合国编号不能超过7位'
									);
									echo json_encode($res,JSON_UNESCAPED_UNICODE);
									exit();
								}
								//判断件数为整数
								if(is_numeric($c ['NUMBERSOFPACKAGES'])===false)
								{
									//删除预报计划
									$DdPlan->where("id=$plan_id")->delete();
									//删除预报配货
									$planCargo->where("id=$cargoplan")->delete();
									$res=array(
											'code'=>109,
											'msg'=>'每箱件数为整数'
									);
									echo json_encode($res,JSON_UNESCAPED_UNICODE);
									exit();
								}
								//判断重量为整数
								if(is_numeric($c ['WEIGHT'])===false)
								{
									//删除预报计划
									$DdPlan->where("id=$plan_id")->delete();
									//删除预报配货
									$planCargo->where("id=$cargoplan")->delete();
									$res=array(
											'code'=>110,
											'msg'=>'每箱重量为整数'
									);
									echo json_encode($res,JSON_UNESCAPED_UNICODE);
									exit();
								}
								//判断体积为整数
								if(is_numeric($c ['VOLUME'])===false)
								{
									//删除预报计划
									$DdPlan->where("id=$plan_id")->delete();
									//删除预报配货
									$planCargo->where("id=$cargoplan")->delete();
									$res=array(
											'code'=>111,
											'msg'=>'每箱体积为整数'
									);
									echo json_encode($res,JSON_UNESCAPED_UNICODE);
									exit();
								}
								//判断危险品等级
								if(strlen($c ['CLASSES'])>5)
								{
									//删除预报计划
									$DdPlan->where("id=$plan_id")->delete();
									//删除预报配货
									$planCargo->where("id=$cargoplan")->delete();
									$res=array(
											'code'=>112,
											'msg'=>'危险品等级不能超过5位'
									);
									echo json_encode($res,JSON_UNESCAPED_UNICODE);
									exit();
								}
								if($c ['CTNNO'])
								{
									$c_CTNNO=$c ['CTNNO'];
								}else {
									$c_CTNNO=null;
								}
								if($c ['CTNSIZE'])
								{
									$c_CTNSIZE=$c ['CTNSIZE'];
								}else {
									$c_CTNSIZE=null;
								}
								if($c ['CTNTYPE'])
								{
									$c_CTNTYPE=$c ['CTNTYPE'];
								}else {
									$c_CTNTYPE=null;
								}
								if($c ['SEALNO'])
								{
									$c_SEALNO=$c ['SEALNO'];
								}else {
									$c_SEALNO=null;
								}
								if($c ['NUMBERSOFPACKAGES'])
								{
									$c_NUMBERSOFPACKAGES=$c ['NUMBERSOFPACKAGES'];
								}else {
									$c_NUMBERSOFPACKAGES=null;
								}
								if($c ['WEIGHT'])
								{
									$c_WEIGHT=$c ['WEIGHT'];
								}else {
									$c_WEIGHT=null;
								}
								if($c ['VOLUME'])
								{
									$c_VOLUME=$c ['VOLUME'];
								}else {
									$c_VOLUME=null;
								}
								if($c ['FLFLAG'])
								{
									$c_FLFLAG=$c ['FLFLAG'];
								}else {
									$c_FLFLAG=null;
								}
								if($c ['CLASSES'])
								{
									$c_classes=$c ['CLASSES'];
								}else {
									$c_classes=null;
								}
								if($c ['UNDGNO'])
								{
									$c_undgno=$c ['UNDGNO'];
								}else {
									$c_undgno=null;
								}
								$data2[] = array (
										'ctnno' => $c_CTNNO,
										'ctnsize' => $c_CTNSIZE,
										'ctntype' => $c_CTNTYPE,
										'sealno' => $c_SEALNO,
										'numbersofpackages' => $c_NUMBERSOFPACKAGES,
										'weight' => $c_WEIGHT,
										'volume' => $c_VOLUME,
										'flflag' => $c_FLFLAG,
										'classes' => $c_classes,
										'undgno' => $c_undgno,
										'plan_id' => $plan_id
								);
							}
							//addAll方法中不能出现空值，否则其他数据会自动向前移动，导致添加失败。
							$res_pc=$PlanContainer->addAll($data2);
							if($res_pc!==false)
							{
								//如果是月结客户,或者申报公司为理货公司本身，并且不能是CFS装箱业务，直接生成指令
								if(($customer_msg['paytype']==$customer_paytype['month'] or $bill ['APPLYCODE']==COMPANY_CODE) and $bill ['TRANSIT']=='CY' and $bill ['CATEGORY']=='2')
								{
									$data_i=array(
											'plan_id'=>$plan_id,
											'date'=>date('Y-m-d'),
											'status'=>$instruction_status['not_start'],
											'department_id'=>$dtd_departmentid
									);
									$Instruction=new \Common\Model\DdInstructionModel();
									$Instruction->add($data_i);
								}
								$response=array(
										'RECORDID'=>$plan_id,
										'AMOUNT'=>$amount,
										'BILLING'=>$customer_msg['rate_code'],
										'METHOD'=>$paytype,
										'NOTE'=>''
								);
								/* $response='<?xml version="1.0" encoding="UTF-8"?>\n
								 <Manifest>\n
								 <RECORDID>'.$plan_id.'</RECORDID>\n
								 <AMOUNT>'.$amount.'</AMOUNT>\n
								 <BILLING>'.$customer_msg['rate_code'].'</BILLING>\n
								 <METHOD>'.$customer_msg['paytype'].'</METHOD>\n
								 <NOTE></NOTE>\n
								</Manifest>'; */
								$res=array(
										'code'=>0,
										'msg'=>'成功',
										'content'=>$response
								);
								echo json_encode($res,JSON_UNESCAPED_UNICODE);
								exit();
							}else {
								//删除预报计划
								$DdPlan->where("id=$plan_id")->delete();
								//删除预报配货
								$planCargo->where("id=$cargoplan")->delete();
								$res=array(
										'code'=>2,
										'msg'=>'数据库连接错误'
								);
								echo json_encode($res,JSON_UNESCAPED_UNICODE);
								exit();
							}
						}
					}else {
						$res=array(
								'code'=>2,
								'msg'=>'数据库连接错误'
						);
						echo json_encode($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
				}else {
					$res=array(
							'code'=>3,
							'msg'=>'参数不正确，参数缺失'
					);
					echo json_encode($res,JSON_UNESCAPED_UNICODE);
					exit();
				}
			}else {
				$res=array(
						'code'=>4,
						'msg'=>'验签错误'
				);
				echo json_encode($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
		}else {
			$res=array(
					'code'=>3,
					'msg'=>'参数不正确，参数缺失'
			);
			echo json_encode($res,JSON_UNESCAPED_UNICODE);
			exit();
		}
	}
	
	//支付回执接口
	public function payment()
	{
		//include './Public/inc/status.config.php';
		if(I('post.requestdata') and I('post.sign'))
		{
			$dtd_departmentid=dtd_departmentid;
			$customer_paytype=json_decode(customer_paytype,true);
			$instruction_status=json_decode(instruction_status,true);
			//①验证签名
			$sign = I('post.sign');
			$requestdata = $_POST['requestdata'];
			//查询数据库进行验签
			$request=simplexml_load_string($requestdata);
			$json=json_encode($request,JSON_UNESCAPED_UNICODE);
			$request=json_decode($json,true);
			//查询条件
			$where=array(
					'orderid'=>$request['ORDERID'],
			);
			$dd_plan=D('tally_dd_plan');
			$res_p=$dd_plan->where($where)->find();
			if($res_p['id']!='')
			{
				$orderid=$res_p['orderid'];
				$orderdate=$res_p['orderdate'];
				$vslname=$res_p['vslname'];
				$voyage=$res_p['voyage'];
				$blno=$res_p['blno'];
				$paycode=$res_p['paycode'];
				$payman=$res_p['payman'];
				$amount=$res_p['amount'];
				$cargoname=$res_p['cargoname'];
				$numbersofpackages=$res_p['numbersofpackages'];
				$lcl=$res_p['lcl'];
				$consignee=$res_p['consignee'];
				$unpackagingplace=$res_p['unpackagingplace'];
				$classes=$res_p['classes'];
				$undgno=$res_p['undgno'];
				$contactuser=$res_p['contactuser'];
				$contact=$res_p['contact'];
				$note=$res_p['note'];
				$request_str = '<?xml version="1.0" encoding="UTF-8"?>
		<Manifest>
				 <ORDERID>' . $orderid . '</ORDERID>
				 <ORDER_DATE>' . $orderdate . '</ORDER_DATE>
		         <VSLNAME>' . $vslname . '</VSLNAME>
		         <VOYAGE>' . $voyage . '</VOYAGE>
  		         <BLNO>' . $blno . '</BLNO>
		         <PAYCODE>' . $paycode . '</PAYCODE>
          		 <PAYMEN>' . $payman . '</PAYMEN>
          		 <AMOUNT>' . $amount . '</AMOUNT>
          		 <PAYTYPE></PAYTYPE>
          		 <CARGONAME>' . $cargoname . '</CARGONAME>
          		 <NUMBERSOFPACKAGES>' . $numbersofpackages . '</NUMBERSOFPACKAGES>
          		 <RCVFLAG></RCVFLAG>
          		 <LCL>' . $lcl . '</LCL>
          		 <CONSIGNEE>' . $consignee . '</CONSIGNEE>
          		 <UNPACKAGINGPLACE>' . $unpackagingplace . '</UNPACKAGINGPLACE>
          		 <CLASSES>' . $classes . '</CLASSES>
          		 <UNDGNO>' . $undgno . '</UNDGNO>
          		 <CONTACTUSER>' . $contactuser . '</CONTACTUSER>
          		 <CONTACT>' . $contact . '</CONTACT>
          		 <NOTE>' . $note . '</NOTE>
         </Manifest>';
				$str = "requestdata=$request_str&key=".KEY;
				$sign_str = md5 ( $str );
				if($sign==$sign_str)
				{
					$plan_id=$res_p['id'];
					//判断委托是否已生成指令，已有指令不准进行操作
					$inum=D('tally_dd_instruction')->where("plan_id=$plan_id")->count();
					if($inum>0)
					{
						$res=array(
								'code'=>103,
								'msg'=>'该委托已存在指令，请勿重复操作！'
						);
						echo json_encode($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
					//委托计划存在，修改委托状态为有效，同步自动生成指令
					$data_p=array(
							'is_valid'=>'Y',
							'rcvflag'=>'1'
					);
					$res_c=$dd_plan->where("id=$plan_id")->save($data_p);
					if($res_c!==false)
					{
						if($res_p['transit']=='CY' and $res_p['category']=='2')
						{
							//同步自动生成指令
							$data_i=array(
									'plan_id'=>$plan_id,
									'date'=>date('Y-m-d'),
									'status'=>$instruction_status['not_start'],
									'department_id'=>$dtd_departmentid
							);
							$res_i=D('tally_dd_instruction')->add($data_i);
							if($res_i!==false)
							{
								$res=array(
										'code'=>0,
										'msg'=>'成功'
								);
								echo json_encode($res,JSON_UNESCAPED_UNICODE);
								exit();
							}else {
								$res=array(
										'code'=>2,
										'msg'=>'数据库连接错误'
								);
								echo json_encode($res,JSON_UNESCAPED_UNICODE);
								exit();
							}
						}else {
							$res=array(
									'code'=>0,
									'msg'=>'成功'
							);
							echo json_encode($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
					}else {
						$res=array(
								'code'=>2,
								'msg'=>'数据库连接错误'
						);
						echo json_encode($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
				}else {
					$res=array(
							'code'=>4,
							'msg'=>'验签错误'
					);
					echo json_encode($res,JSON_UNESCAPED_UNICODE);
					exit();
				}
			}else {
				$res=array(
						'code'=>102,
						'msg'=>'没有该条委托计划！'
				);
				echo json_encode($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
		}else {
			$res=array(
					'code'=>3,
					'msg'=>'参数不正确，参数缺失'
			);
			echo json_encode($res,JSON_UNESCAPED_UNICODE);
			exit();
		}
	}
	
}