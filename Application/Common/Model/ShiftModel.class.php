<?php

/**
 * 公用业务类
 * 工班管理类
 */
namespace Common\Model;

use Think\Model;

class ShiftModel extends Model {
	public $ERROR_CODE_COMMON = array (); // 公共返回码
	public $ERROR_CODE_COMMON_ZH = array (); // 公共返回码中文描述
	public $ERROR_CODE_SHIFT = array (); // 工班管理返回码
	public $ERROR_CODE_SHIFT_ZH = array (); // 工班管理返回码中文描述
	public $ERROR_CODE_USER = array (); // 工班管理返回码
	public $ERROR_CODE_USER_ZH = array (); // 工班管理返回码中文描述

	// 初始化
	protected function _initialize() {
		$this->ERROR_CODE_COMMON = json_decode ( error_code_common, true );
		$this->ERROR_CODE_COMMON_ZH = json_decode ( error_code_common_zh, true );
		$this->ERROR_CODE_SHIFT = json_decode ( error_code_shift, true );
		$this->ERROR_CODE_SHIFT_ZH = json_decode ( error_code_shift_zh, true );
		$this->ERROR_CODE_USER = json_decode ( error_code_user, true );
		$this->ERROR_CODE_USER_ZH = json_decode ( error_code_user_zh, true );
	}

	// 验证规则
	protected $_validate = array (
			array (
					'shift_id',
					'require',
					'工班ID不能为空',
					self::EXISTS_VALIDATE 
			), // 存在即验证，不能为空
			array (
					'shift_id',
					'1,20',
					'工班ID不超过20个字符',
					self::EXISTS_VALIDATE,
					'length' 
			), // 存在即验证，长度不能超过20个字符
			array (
					'shift_master',
					'is_positive_int',
					'当班理货长不存在',
					self::VALUE_VALIDATE,
					'function' 
			), // 值不为空的时候验证，必须为正整数
			array (
					'department_id',
					'require',
					'部门不能为空',
					self::EXISTS_VALIDATE 
			), // 存在即验证,不能为空
			array (
					'department_id',
					'is_positive_int',
					'所属部门不存在',
					self::EXISTS_VALIDATE,
					'function' 
			), // 存在即验证，必须为正整数
			array (
					'classes',
					array (
							'1',
							'2' 
					),
					'请选择正确的白/夜班',
					self::VALUE_VALIDATE,
					'in' 
			), // 值不为空的时候验证，只能是 1白班 2夜班
			array (
					'date',
					'is_date',
					'签到日期不是正确的时间格式',
					self::VALUE_VALIDATE,
					'function' 
			), // 值不为空的时候验证，必须为正确的时间格式
			array (
					'begin_time',
					'is_datetime',
					'签到时间不是正确的时间格式',
					self::VALUE_VALIDATE,
					'function' 
			), // 值不为空的时候验证，必须为正确的时间格式
			array (
					'end_time',
					'is_datetime',
					'交班时间不是正确的时间格式',
					self::VALUE_VALIDATE,
					'function' 
			), // 值不为空的时候验证，必须为正确的时间格式
			array (
					'mark',
					array (
							'0',
							'1' 
					),
					'交班标志不正确',
					self::VALUE_VALIDATE,
					'in' 
			) 
	) // 值不为空的时候验证，只能是 0未交班 1已交班
;
	
	/**
	 * 签到
	 * 签到原则：
	 * ①用户合法、部门存在
	 * ②不准签入比今天还新的班次、当天的班次在不到17点时不准签入夜班
	 * ③没有历史工班记录,直接记录签到信息
	 * ④存在历史记录，如果历史工班未交班，只准签入未交班的历史工班；如果历史工班已交班，不能签入以前工班，只能签入更新的同部门班次
	 * 
	 * @param int $uid:用户ID        	
	 * @param int $department_id:部门ID        	
	 * @param string $date:日期，格式20160622        	
	 * @param int $classes:班次，1白班
	 *        	2夜班
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param shift:工班信息，只有在历史工作未交班的情况下返回
	 */
	public function signIn($uid, $department_id, $date, $classes) {
		// \Common\Common\Log::info(123);
		// ①比对日期，不准签入比今天还新的班次
		if (strtotime ( $date ) > time ()) {
			$res = array (
					'code' => $this->ERROR_CODE_SHIFT ['SIGN_DATE_INVALID'],
					'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SIGN_DATE_INVALID']],
					'shift' => '' 
			);
		} else {
			// 获取当前小时
			$hour = date ( "G" );
			if ($classes == 2 && $date == date ( 'Ymd', time () ) && $hour < 17) {
				// ②比对日期，当天的班次在不到17点时不准签入夜班
				$res = array (
						'code' => $this->ERROR_CODE_SHIFT ['NOT_NIGHTSHIF_TIME'],
						'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['NOT_NIGHTSHIF_TIME']],
						'shift' => '' 
				);
			} else {
				// ③检验用户有效性
				$user = new \Common\Model\UserModel ();
				$res_u = $user->is_valid ( $uid );
				if ($res_u ['code'] != 0) {
					// 用户不合法，返回码采用和检验用户的返回码一致
					$res = $res_u;
				} else {
					// ④检验二级部门是否存在
					$department = new \Common\Model\DepartmentModel ();
					$res_de = $department->is_exist_subdepartment ( $department_id );
					if ($res_de ['code'] != 0) {
						// 二级部门不存在，返回码采用和检验部门的返回码一致
						$res = $res_de;
						$res ['shift'] = '';
						return $res;
					}
					// ⑤检验历史工班是否交班
					// 根据部门ID检索该部门最新的工班记录
					$res_g = $this->where ( "department_id='$department_id'" )->order ( 'shift_id desc' )->find ();
					if ($res_g != '') {
						// 最新的工班是否交班标志
						$mark = $res_g ['mark'];
						$shift_id = $res_g ['shift_id'];
						// 最新的工班日期
						$sign_date = substr ( $shift_id, - 9, 8 );
						// 最新的工班班次
						$sign_classes = substr ( $shift_id, - 1 );
						if ($mark == '0') {
							if ($sign_date == $date and $sign_classes == $classes) {
								// 历史工班未交班，只能签入未交班的同一个班次
								// 获取部门编码
								$res_d = $department->getDepartmentMsg ( $department_id );
								$user_shift_id = $res_d ['department_code'] . $date . $classes;
								// 记录用户签到班次
								$data2 = array (
										'shift_id' => $user_shift_id 
								);
								$res_sign_u = $user->where ( "uid=$uid" )->save ( $data2 );
								if ($res_sign_u !== false) {
									// 签到成功
									$res = array (
											'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
											'msg' => '签到成功',
											'shift' => '' 
									);
								}
							} else {
								// 历史工班未交班，不能签入同部门组其它班次
								// 返回未交班的班次信息，便于工作人员联系当班理货长处理
								$res_shift = $this->getShiftMsg ( $shift_id );
								$res = array (
										'code' => $this->ERROR_CODE_SHIFT ['HISTORY_SHIFT_NOT_EXCHANGED'],
										'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['HISTORY_SHIFT_NOT_EXCHANGED']],
										'shift' => $res_shift 
								);
							}
						} else {
							// 历史工班已交班
							if ($sign_date > $date) {
								// 不能签入以前工班
								$res = array (
										'code' => $this->ERROR_CODE_SHIFT ['NOT_LAST_SHIFT'],
										'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['NOT_LAST_SHIFT']],
										'shift' => '' 
								);
							} else {
								if ($sign_date == $date and $classes <= $sign_classes) {
									if ($classes < $sign_classes) {
										// 不能签入以前工班
										$res = array (
												'code' => $this->ERROR_CODE_SHIFT ['NOT_LAST_SHIFT'],
												'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['NOT_LAST_SHIFT']],
												'shift' => '' 
										);
									} else {
										// 不能签入已交班工班
										$res = array (
												'code' => $this->ERROR_CODE_SHIFT ['SHIFT_EXCHANGED'],
												'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SHIFT_EXCHANGED']],
												'shift' => '' 
										);
									}
								} else {
									// 检验通过，记录新的工班签到信息
									// 获取部门编码
									$department = new \Common\Model\DepartmentModel ();
									$res_d = $department->getDepartmentMsg ( $department_id );
									$shift_id = $res_d ['department_code'] . $date . $classes;
									$data = array (
											'shift_id' => $shift_id,
											'department_id' => $department_id,
											'classes' => $classes,
											'date' => date ( 'Y-m-d' ),
											'begin_time' => date ( 'Y-m-d H:i:s' ),
											'mark' => '0' 
									);
									// 记录班次签到信息
									$res_sign = $this->add ( $data );
									// 记录用户签到班次
									$data2 = array (
											'shift_id' => $shift_id 
									);
									$res_sign_u = $user->where ( "uid=$uid" )->save ( $data2 );
									if ($res_sign !== false and $res_sign_u !== false) {
										// 签到成功
										$res = array (
												'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
												'msg' => '签到成功',
												'shift' => '' 
										);
									}
								}
							}
						}
					} else {
						// 没有历史工班记录,直接记录签到信息
						// 获取部门编码
						$department = new \Common\Model\DepartmentModel ();
						$res_d = $department->getDepartmentMsg ( $department_id );
						$shift_id = $res_d ['department_code'] . $date . $classes;
						$data = array (
								'shift_id' => $shift_id,
								'department_id' => $department_id,
								'classes' => $classes,
								'date' => date ( 'Y-m-d' ),
								'begin_time' => date ( 'Y-m-d H:i:s' ),
								'mark' => '0' 
						);
						// 记录班次签到信息
						$res_sign = $this->add ( $data );
						// 记录用户签到班次
						$data2 = array (
								'shift_id' => $shift_id 
						);
						$res_sign_u = $user->where ( "uid=$uid" )->save ( $data2 );
						if ($res_sign !== false and $res_sign_u !== false) {
							// 签到成功
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
									'msg' => '签到成功',
									'shift' => '' 
							);
						}
					}
				}
			}
		}
		return $res;
	}
	
	/**
	 * 接班
	 * 接班原则：
	 * ①用户合法、用户已签到
	 * ②用户当前所在工班日期不能小于接班工班
	 * ③用户所在工班组要有工班记录
	 * ④用户所在工班不能已有其他理货长
	 * ⑤用户为理货长或部门长
	 * ⑥如果工班是首次开工作业，直接记录理货长为签到班组的当班理货长，添加一条新的交接班记录；如果存在历史工班，核对要接班工班和部门组的最新已交班工班相同后，开设新工班，保存交接班记录
	 * 
	 * @param int $uid:用户ID        	
	 * @param string $shift_id:工班ID        	
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function succeed($uid, $shift_id = '') {
		// ①检验用户有效性
		$user = new \Common\Model\UserModel ();
		$res_u = $user->is_valid ( $uid );
		if ($res_u ['code'] != 0) {
			// 用户不合法，返回码采用和检验用户的返回码一致
			$res = $res_u;
		} else {
			// ②判断用户是否已签到
			// 未签到不准接班,已签到获取其签到部门工班组
			$res_s = $user->is_sign ( $uid );
			if ($res_s ['code'] != 0) {
				// 未签到，返回码采用和判断用户是否签到的返回码一致
				$res = $res_s;
			} else {
				// 已签到，获取用户最新的签到工班组ID
				$user_shift_id = $res_s ['shift_id'];
				// 判断用户当前工班日期不能小于接班工班
				$user_sign_date = substr ( $user_shift_id, - 9, 8 );
				$work_sign_date = substr ( $shift_id, - 9, 8 );
				if (strtotime ( $user_sign_date ) <= strtotime ( $work_sign_date )) {
					// 要接班工班应早于用户所在新工班
					if (strtotime ( $user_sign_date ) < strtotime ( $work_sign_date )) {
						// 要接班工班日期大于用户所在新工班日期，不准接班
						// 您当前所在工班日期小于要接班工班！
						$res = array (
								'code' => $this->ERROR_CODE_SHIFT ['SIGN_MSG_INVALID'],
								'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SIGN_MSG_INVALID']] 
						);
						return $res;
					} else {
						if (strtotime ( $user_sign_date ) == strtotime ( $work_sign_date )) {
							// 要接班工班日期等于用户所在新工班日期，处于同一天的白班/夜班
							$user_sign_classes = substr ( $user_shift_id, - 1, 1 );
							$work_sign_classes = substr ( $shift_id, - 1, 1 );
							if ($user_sign_classes == '2' and $work_sign_classes == '1') {
								// 同一天情况下
								// 只有用户所在新工班是夜班， 要接班的工班是白班的情况下，才准许接班
							} else {
								// 您当前所在工班日期小于要接班工班！
								$res = array (
										'code' => $this->ERROR_CODE_SHIFT ['SIGN_MSG_INVALID'],
										'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SIGN_MSG_INVALID']] 
								);
								return $res;
							}
						}
					}
				}
				
				// ③判断用户所在的最新工班组是否已有其他理货长
				$res_g = $this->where ( "shift_id='$user_shift_id'" )->field ( 'shift_master,department_id,mark' )->find ();
				if ($res_g) {
					$department_id = $res_g ['department_id']; // 工班所属部门ID
					if ($res_g ['shift_master'] != '' and $res_g ['shift_master'] != $uid) {
						// 该用户不是当前工班理货长
						$res = array (
								'code' => $this->ERROR_CODE_SHIFT ['NOT_ONDUTY_CHIEFTALLY'],
								'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['NOT_ONDUTY_CHIEFTALLY']] 
						);
					} else {
						// ④判断用户是否为理货长或部门长
						// 理货员没有权限接班
						$res_auth = $user->is_chiefTally ( $uid );
						if ($res_auth ['code'] != 0) {
							// 不是理货长，返回码采用和判断用户是否为理货长的返回码一致
							$res = $res_auth;
						} else {
							// ⑤如果工班是首次开工作业
							// 直接记录理货长为签到班组的当班理货长，添加一条新的交接班记录
							$department_code = substr ( $user_shift_id, 0, - 9 );
							$res_f = $this->where ( "shift_id!='$user_shift_id' and department_id='$department_id'" )->field ( 'shift_id' )->order ( 'shift_id desc' )->find ();
							if ($res_f ['shift_id'] == '' and $shift_id == '') {
								// 没有更早的工班
								$data = array (
										'shift_master' => $uid 
								);
								$res_add = $this->where ( "shift_id='$user_shift_id'" )->save ( $data );
								if ($res_add !== false) {
									// 添加一条新的接班记录
									$data2 = array (
											'carryon_id' => $user_shift_id,
											'user_carryon_id' => $uid,
											'carryon_time' => date ( 'Y-m-d H:i:s' ) 
									);
									$ShiftDetail = new \Common\Model\ShiftDetailModel ();
									$res_tf = $ShiftDetail->add ( $data2 );
									if ($res_tf !== false) {
										$res = array (
												'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
												'msg' => '接班成功！' 
										);
									} else {
										$res = array (
												'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
												'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
										);
									}
								} else {
									$res = array (
											'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
											'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
									);
								}
							} else {
								// ⑥不是首次开工，存在历史工班
								// 判断该部门组的最新已交班工班是否和要接班的工班ID相同
								if ($shift_id == $res_f ['shift_id']) {
									// 设置理货长为签到班组的当班理货长
									$data = array (
											'shift_master' => $uid 
									);
									$res_add = $this->where ( "shift_id='$user_shift_id'" )->save ( $data );
									if ($res_add !== false) {
										// 修改交接班记录，查找交班工班ID为要接班的工班ID
										$data2 = array (
												'carryon_id' => $user_shift_id,
												'user_carryon_id' => $uid,
												'carryon_time' => date ( 'Y-m-d H:i:s' ) 
										);
										$ShiftDetail = new \Common\Model\ShiftDetailModel ();
										$res_tf = $ShiftDetail->where ( "exchanged_id='$shift_id'" )->save ( $data2 );
										if ($res_tf !== false) {
											$res = array (
													'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
													'msg' => '接班成功！' 
											);
										} else {
											$res = array (
													'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
													'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
											);
										}
									} else {
										$res = array (
												'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
												'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
										);
									}
								} else {
									// 该部门组的最新已交班工班和用户要接班的工班ID不同
									$res = array (
											'code' => $this->ERROR_CODE_SHIFT ['NOT_LAST_SHIFT'],
											'msg' => '要接班的工班不是该部门组最新的交班工班！' 
									);
								}
							}
						}
					}
				} else {
					// 用户所在的工班组不存在
					$res = array (
							'code' => $this->ERROR_CODE_SHIFT ['SHIFT_NOT_EXIST'],
							'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SHIFT_NOT_EXIST']] 
					);
				}
			}
		}
		return $res;
	}
	
	/**
	 * 判断用户是否可以接班
	 * 
	 * @param int $uid:用户ID        	
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param lastshift:上一工班信息，只有在可以接班的情况下返回
	 */
	public function whetherTakeOver($uid) {
		$user = new \Common\Model\UserModel ();
		// 检验用户是否合法
		$res_u = $user->is_valid ( $uid );
		if ($res_u ['code'] != 0) {
			// 用户不合法，返回码采用和检验用户的返回码一致
			$res = $res_u;
		} else {
			// 用户合法
			// ②检验用户是否是理货长身份
			$res_u2 = $user->is_chiefTally ( $uid );
			\Common\Common\LogController::info(json_encode($res_u2));
			if ($res_u2 ['code'] != 0) {
				// 用户不是理货长身份，返回码采用和检验用户的返回码一致
				$res = $res_u2;
			} else {
				// 用户是理货长身份
				// ③检验用户是否签到
				$res_u3 = $user->is_sign ( $uid );
				\Common\Common\LogController::info(json_encode($res_u3));
				if ($res_u3 ['code'] != 0) {
					// 用户未签到，返回码采用和检验用户的返回码一致
					$res = $res_u3;
				} else {
					// 用户已签到
					$userMsg = $user->getUserMsg ( $uid );
					$shift_id = $userMsg ['shift_id'];
					// ④判断用户签到班组是否存在其他当班理货长
					$msg = $this->where ( "shift_id='$shift_id'" )->find ();
					\Common\Common\LogController::info(json_encode($msg));
					if ($msg ['shift_master'] != '') {
						// 该工班已有理货长
						$res = array (
								'code' => $this->ERROR_CODE_SHIFT ['SHIFT_EXIST_CHIEFTALLY'],
								'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SHIFT_EXIST_CHIEFTALLY']] 
						);
					} else {
						// 工班尚无理货长，可以接班
						// 同步返回该部门组上一工班信息
						$department_id = $msg ['department_id']; // 工班所属部门ID
						$res_l = $this->where ( "department_id='$department_id' and shift_id!='$shift_id'" )->order ( 'shift_id desc' )->find ();
						if ($res_l) {
							$last_shift_id = $res_l ['shift_id'];
							$lastshift = $this->getShiftMsg ( $last_shift_id );
							// 获取交接班记录
							$record = $this->getShiftRecord ( $last_shift_id );
							$lastshift ['transfer_time'] = $record ['exchanged_time'];
							$lastshift ['note'] = $record ['note'];
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
									'msg' => '可以接班！',
									'lastshift' => $lastshift 
							);
						} else {
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
									'msg' => '可以接班！',
									'lastshift' => '' 
							);
						}
					}
				}
			}
		}
		return $res;
	}
	
	/**
	 * 交班
	 * 
	 * @param int $uid:用户ID        	
	 * @param string $shift_id:工班ID        	
	 * @param string $note:交班信息        	
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function transfer($uid, $shift_id, $note) {
		// ①判断该工班是否已交班
		$res_s = $this->is_succeed ( $shift_id );
		if ($res_s ['code'] != 0) {
			// 工班已交班，返回码和判断工班是否交班采用的一致
			$res = $res_s;
		} else {
			// 工班未交班
			// ②判断用户是否为当班理货长
			$res_m = $this->isWorkMaster ( $uid, $shift_id );
			if ($res_m ['code'] != 0) {
				// 不是当班理货长，返回码和检验用户是否为当班理货长采用的一致
				$res = $res_m;
			} else {
				// ③用户为当班理货长，并且工班未交班
				//判断该工班下面的指令箱是否暂停作业
				$res_a = $this->isstop($uid, $shift_id );
				if($res_a['code'] != 0)
				{
					$res = $res_a;
				}else{
					//判断该工班下面的指令箱已铅封的是否审核
// 					$res_o = $this->isexamine( $uid, $shift_id );
// 					if($res_o['code'] != 0){
// 						$res = $res_o;
// 					}else{
						// 修改工班状态为已交班
						$data = array (
								'mark' => '1',
								'end_time' => date ( 'Y-m-d H:i:s' )
						);
						$res_g = $this->where ( "shift_id='$shift_id'" )->save ( $data );
						if ($res_g != false) {
							// 新增一条交班记录
							$data2 = array (
									'exchanged_id' => $shift_id,
									'user_exchanged_id' => $uid,
									'exchanged_time' => date ( 'Y-m-d H:i:s' ),
									'note' => $note
							);
							$ShiftDetail = new \Common\Model\ShiftDetailModel ();
							$res_tf = $ShiftDetail->add ( $data2 );
							// 工班的状态已改为交班，已新增一条交班记录，便于下次同部门组工班来接班
							if ($res_tf !== false) {
								// ①修改派工状态为已交班
								$Dispatch = new \Common\Model\DispatchModel ();
								$data_r = array (
										'mark' => '1'
								);
								$Dispatch->where ( "shift_id='$shift_id'" )->save ( $data_r );
								// 根据工班号从派工单获取业务系统与指令
								$res_repair = $Dispatch->where ( "shift_id='$shift_id'" )->select ();
								// 指令状态
								$instruction_status = json_decode ( instruction_status, true );
								// 已完成的指令
								$finished_instruction_status = $instruction_status ['finish'];
								// 修改指令状态为未派工，以方便下一工班派工--除了已完成的指令（2为已完成）
								$data_i = array (
										'status' => $instruction_status ['not_start']
								);
								// 箱状态
								$ctn_status = json_decode ( ctn_status, true );
								foreach ( $res_repair as $r ) {
									// 指令ID
									$instruction_id = $r ['instruction_id'];
									// 区分业务系统
									switch ($r ['business']) {
										case 'qbzx' :
												
											// ②修改指令状态为未派工，以方便下一工班派工--除了已完成的指令（2为已完成）
											$QbzxInstruction = new \Common\Model\QbzxInstructionModel ();
											$QbzxInstruction->where ( "id='$instruction_id' and status!='$finished_instruction_status'" )->save ( $data_i );
											// ③修改指令下箱状态为未开始
											$where = array (
													'instruction_id' => $instruction_id,
													'status' => $ctn_status ['workin']
											) // 只修改工作中的箱子
											;
											$data_c = array (
													'status' => $ctn_status ['nostart'], // 修改箱状态为未开始
													'operator_id' => null
											) // 置空操作人
											;
											$QbzxInstructionCtn = new \Common\Model\QbzxInstructionCtnModel ();
											$QbzxInstructionCtn->where ( $where )->save ( $data_c );
											// ④修改作业表的可修改权限为3，即只有部门长可以修改
											// 对该指令下的所有配箱进行权限修改
											$ctnList = $QbzxInstructionCtn->where ( "instruction_id='$instruction_id'" )->field ( 'id' )->select ();
											foreach ( $ctnList as $cl ) {
												$ctn_allid .= $cl ['id'] . ',';
											}
											if ($ctn_allid) {
												$ctn_allid = substr ( $ctn_allid, 0, - 1 );
												$QbzxOperation = new \Common\Model\QbzxOperationModel ();
												$data_auth = array (
														'per_no' => '3'
												);
												$QbzxOperation->where ( "ctn_id in ($ctn_allid)" )->save ( $data_auth );
											}
											break;
										case 'dd' :
												
											// ②修改指令状态为未派工，以方便下一工班派工--除了已完成的指令（2为已完成）
											$DdInstruction = new \Common\Model\DdInstructionModel ();
											$DdInstruction->where ( "id='$instruction_id' and status!='$finished_instruction_status'" )->save ( $data_i );
											// 根据指令ID获取预报计划ID，进而获取配箱
											$res_p = $DdInstruction->where ( "id='$instruction_id'" )->field ( 'plan_id' )->find ();
											if ($res_p ['plan_id']) {
												$plan_id = $res_p ['plan_id'];
												// ③修改指令下箱状态为未开始
												$where = array (
														'plan_id' => $plan_id,
														'status' => $ctn_status ['workin']
												) // 只修改工作中的箱子
												;
												$data_c = array (
														'status' => $ctn_status ['nostart'], // 修改箱状态为未开始
														'operator_id' => null
												);
												$DdPlanContainer = new \Common\Model\DdPlanContainerModel ();
												$DdPlanContainer->where ( $where )->save ( $data_c );
											}
											break;
										case 'cfs' :
												
											// ②修改指令状态为未派工，以方便下一工班派工--除了已完成的指令（2为已完成）
											$CfsInstruction = new \Common\Model\CfsInstructionModel ();
											$CfsInstruction->where ( "id='$instruction_id' and status!='$finished_instruction_status'" )->save ( $data_i );
											// ③修改指令下箱状态为未开始--除了已铅封的箱子不修改（2为已铅封）
											$where = array (
													'instruction_id' => $instruction_id,
													'status' => $ctn_status ['workin']
											) // 只修改工作中的箱子
											;
											$data_c = array (
													'status' => $ctn_status ['nostart'], // 修改箱状态为未开始
													'operator_id' => null
											);
											$CfsInstructionCtn = new \Common\Model\CfsInstructionCtnModel ();
											$CfsInstructionCtn->where ( $where )->save ( $data_c );
											break;
									}
								}
								$res = array (
										'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
										'msg' => '交班成功！'
								);
							} else {
								// 数据库操作错误
								$res = array (
										'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
										'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
								);
							}
						} else {
							// 数据库操作错误
							$res = array (
									'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
									'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']]
							);
						}	
// 					}
				}
			}
		}
		return $res;
	}
	
	/**
	 * 获取班次信息
	 * 
	 * @param string $id
	 *        	签到班次ID
	 * @return array|false
	 */
	public function getShiftMsg($id) {
		$res = $this->where ( "shift_id='$id'" )->find ();
		if ($res) {
			// 班次中文描述
			if ($res ['classes'] == '1') {
				$res ['classes_zh'] = '白班';
			} else {
				$res ['classes_zh'] = '夜班';
			}
			// 工班是否交班标志
			$mark = $res ['mark'];
			$shift_id = $res ['shift_id'];
			// 工班日期
			$res ['sign_date'] = substr ( $shift_id, - 9, 8 );
			// 当班理货长
			$master = $res ['shift_master'];
			if ($master != '') {
				$user = new \Common\Model\UserModel ();
				$res_m = $user->getUserMsg ( $master );
				$res ['master_staffno'] = $res_m ['staffno'];
				$res ['master_name'] = $res_m ['user_name'];
			} else {
				$res ['master_name'] = '暂无理货长';
				$res ['master_staffno'] = '';
			}
			// 获取部门名称
			$department = new \Common\Model\DepartmentModel ();
			$res_d = $department->getDepartmentMsg ( $res ['department_id'] );
			$res ['parent_department_name'] = $res_d ['parent_department_name'];
			$res ['department_name'] = $res_d ['department_name'];
			return $res;
		} else {
			return false;
		}
	}
	
	/**
	 * 检验用户是否为当班理货长
	 * 
	 * @param int $uid:用户ID        	
	 * @param string $shift_id:工班ID        	
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function isWorkMaster($uid, $shift_id) {
		// ①检验用户有效性
		$user = new \Common\Model\UserModel ();
		$res_u = $user->is_valid ( $uid );
		if ($res_u ['code'] != 0) {
			// 用户不合法，返回码采用和检验用户的返回码一致
			$res = $res_u;
		} else {
			// ②用户合法
			// 检查工班是否存在
			$res_w = $this->where ( "shift_id='$shift_id'" )->field ( 'shift_id,shift_master' )->find ();
			if ($res_w) {
				if ($res_w ['shift_master'] != '') {
					if ($res_w ['shift_master'] == $uid) {
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
								'msg' => '该用户是当班理货长！' 
						);
					} else {
						// 该用户不是当前工班理货长
						$res = array (
								'code' => $this->ERROR_CODE_SHIFT ['NOT_ONDUTY_CHIEFTALLY'],
								'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['NOT_ONDUTY_CHIEFTALLY']] 
						);
					}
				} else {
					// 该工班尚无理货长！
					$res = array (
							'code' => $this->ERROR_CODE_SHIFT ['SHIFT_NEED_CHIEFTALLY'],
							'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SHIFT_NEED_CHIEFTALLY']] 
					);
				}
			} else {
				// 该工班不存在！
				$res = array (
						'code' => $this->ERROR_CODE_SHIFT ['SHIFT_NOT_EXIST'],
						'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SHIFT_NOT_EXIST']] 
				);
			}
		}
		return $res;
	}
	
	/**
	 * 判断工班是否交班
	 * 
	 * @param string $shift_id:工班ID        	
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function is_succeed($shift_id) {
		$res_w = $this->where ( "shift_id='$shift_id'" )->field ( 'mark' )->find ();
		if ($res_w) {
			if ($res_w ['mark'] == '0') {
				// 该工班尚未交班
				$res = array (
						'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
						'msg' => '该工班尚未交班！' 
				);
			} else {
				// 该工班已交班
				$res = array (
						'code' => $this->ERROR_CODE_SHIFT ['SHIFT_EXCHANGED'],
						'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SHIFT_EXCHANGED']] 
				);
			}
		} else {
			// 该工班不存在
			$res = array (
					'code' => $this->ERROR_CODE_SHIFT ['SHIFT_NOT_EXIST'],
					'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SHIFT_NOT_EXIST']] 
			);
		}
		return $res;
	}
	
	/**
	 * 获取交接班记录
	 * 
	 * @param string $shift_id:交班工班ID        	
	 * @return array
	 */
	public function getShiftRecord($shift_id) {
		$ShiftDetail = new \Common\Model\ShiftDetailModel ();
		$msg = $ShiftDetail->where ( "exchanged_id='$shift_id' or carryon_id='$shift_id'" )->order ( 'id desc' )->find ();
		if ($msg !== false) {
			// 交班工班ID
			if ($msg ['exchanged_id'] != '') {
				$succeed_content = $this->getShiftMsg ( $msg ['exchanged_id'] );
				$msg ['hand_master'] = $succeed_content ['master_name'];
				$msg ['hand_department'] = $succeed_content ['parent_department_name'] . '-' . $succeed_content ['department_name'];
				$msg ['hand_date'] = $succeed_content ['sign_date'];
				$msg ['hand_classes'] = $succeed_content ['classes_zh'];
			} else {
				$msg ['hand_master'] = '';
				$msg ['hand_department'] = '';
				$msg ['hand_date'] = '';
				$msg ['hand_classes'] = '';
			}
			// 接班工班ID
			if ($msg ['carryon_id'] != '') {
				$succeed_content = $this->getShiftMsg ( $msg ['carryon_id'] );
				$msg ['succeed_master'] = $succeed_content ['master_name'];
				$msg ['succeed_department'] = $succeed_content ['parent_department_name'] . '-' . $succeed_content ['department_name'];
				$msg ['succeed_date'] = $succeed_content ['sign_date'];
				$msg ['succeed_classes'] = $succeed_content ['classes_zh'];
			} else {
				$msg ['succeed_master'] = '';
				$msg ['succeed_department'] = '';
				$msg ['succeed_date'] = '';
				$msg ['succeed_classes'] = '';
			}
			return $msg;
		} else {
			return false;
		}
	}
	
	/**
	 * 工班恢复
	 * 
	 * @param string $shift_id:工班ID        	
	 * @param int $uid:操作部门长ID        	
	 * @param string $reason:修改原因        	
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function shiftResume($shift_id, $uid, $reason) {
		// 判断用户是否为部门长
		$User = new \Common\Model\UserModel ();
		// 检验用户是否合法
		$res_u = $User->is_valid ( $uid );
		if ($res_u ['code'] != 0) {
			// 用户不合法，返回码采用和检验用户的返回码一致
			$res = $res_u;
		} else {
			// 用户合法
			$userMsg = $User->getUserMsg ( $uid );
			if ($userMsg ['group_id'] == 13) {
				// 用户身份检验通过，进行工班恢复
				// ①删除交接班记录
				$ShiftDetail = new \Common\Model\ShiftDetailModel ();
				$res_d = $ShiftDetail->where ( "exchanged_id='$shift_id'" )->delete ();
				if ($res_d !== false) {
					// ②置空当前工班结束时间，修改工班状态为未交班
					$data = array (
							'end_time' => null,
							'mark' => '0' 
					);
					$res_g = $this->where ( "shift_id='$shift_id'" )->save ( $data );
					if ($res_g !== false) {
						// ③修改派工状态为未交班
						$Dispatch = new \Common\Model\DispatchModel ();
						$data_r = array (
								'mark' => '0' 
						);
						$Dispatch->where ( "shift_id='$shift_id'" )->save ( $data_r );
						// 根据工班号从派工单获取业务系统与指令
						$res_repair = $Dispatch->where ( "shift_id='$shift_id'" )->select ();
						// ④修改指令状态为已派工--除了已完成的指令（2为已完成）
						$instruction_status = json_decode ( instruction_status, true );
						// 未派工指令（因交班时所有未结束工班都被恢复为未派工，所以这里需要变回来）
						$instruction_status_nostart = $instruction_status ['not_start'];
						$data_i = array (
								'status' => $instruction_status ['start'] 
						) // 已派工
;
						foreach ( $res_repair as $r ) {
							$instruction_id = $r ['instruction_id'];
							// 区分业务系统
							switch ($r ['business']) {
								case 'qbzx' :
									
									// ④修改指令状态为已派工--除了已完成的指令（2为已完成）
									$QbzxInstruction = new \Common\Model\QbzxInstructionModel ();
									$QbzxInstruction->where ( "id='$instruction_id' and status='$instruction_status_nostart'" )->save ( $data_i );
									// ⑤修改作业表的可修改权限为1
									// 对该指令下的所有配箱进行权限修改
									$QbzxInstructionCtn = new \Common\Model\QbzxInstructionCtnModel ();
									$ctnList = $QbzxInstructionCtn->where ( "instruction_id='$instruction_id'" )->field ( 'id' )->select ();
									foreach ( $ctnList as $cl ) {
										$ctn_allid .= $cl ['id'] . ',';
									}
									if ($ctn_allid) {
										$ctn_allid = substr ( $ctn_allid, 0, - 1 );
										$QbzxOperation = new \Common\Model\QbzxOperationModel ();
										$data_auth = array (
												'per_no' => '1' 
										);
										$QbzxOperation->where ( "ctn_id in ($ctn_allid)" )->save ( $data_auth );
									}
									break;
								case 'dd' :
									// ④修改指令状态为已派工--除了已完成的指令（2为已完成）
									$DdInstruction = new \Common\Model\DdInstructionModel ();
									$DdInstruction->where ( "id=$instruction_id and status='$instruction_status_nostart'" )->save ( $data_i );
									break;
								case 'cfs' :
									// ④修改指令状态为已派工--除了已完成的指令（2为已完成）
									$CfsInstruction = new \Common\Model\CfsInstructionModel ();
									$CfsInstruction->where ( "id=$instruction_id and status='$instruction_status_nostart'" )->save ( $data_i );
									break;
							}
						}
						// 保存修改记录
						$data_c = array (
								'shift_id' => $shift_id,
								'operator_id' => $uid,
								'reason' => $reason,
								'resume_time' => date ( 'Y-m-d H:i:s' ) 
						);
						$shift_amendment_record = D ( 'shift_amendment_record' );
						$shift_amendment_record->add ( $data_c );
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
								'msg' => '成功！' 
						);
					} else {
						// 数据库操作失败
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
						);
					}
				} else {
					// 数据库操作失败
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
							'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
					);
				}
			} else {
				// 该用户不是部门长
				$res = array (
						'code' => $this->ERROR_CODE_USER ['NEED_PERMISSION__DEPARTMENTHEAD'],
						'msg' => $this->ERROR_CODE_USER_ZH [$this->ERROR_CODE_USER ['NEED_PERMISSION__DEPARTMENTHEAD']] 
				);
			}
		}
		return $res;
	}
	
	/**
	 * 根据用户ID、工班ID获取该工班下面所有的指令配箱数、预报配箱数、已完成箱数，未完成箱数
	 * 
	 * @param int $uid:用户ID
	 * @param string $shift_id:工班ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function gettrans($uid, $shift_id) {
		// ①判断该工班是否已交班
		$res_s = $this->is_succeed ( $shift_id );
		if ($res_s ['code'] != 0) {
			// 工班已交班，返回码和判断工班是否交班采用的一致
			$res = $res_s;
		} else {
			// 工班未交班
			// ②判断用户是否为当班理货长
			$res_m = $this->isWorkMaster ( $uid, $shift_id );
			if ($res_m ['code'] != 0) {
				// 不是当班理货长，返回码和检验用户是否为当班理货长采用的一致
				$res = $res_m;
			} else {
				// ③用户为当班理货长，并且工班未交班
				// 获取工班所属部门ID
				$msg = $this->where ( "shift_id='$shift_id'" )->find ();
				$department_id = $msg ['department_id']; // 工班所属部门ID
				$res_l = $this->where ( "department_id='$department_id' and shift_id!='$shift_id'" )->order ( 'shift_id desc' )->find ();
				if ($res_l) {
					$last_shift_id = $res_l ['shift_id'];
					$lastshift = $this->getShiftMsg ( $last_shift_id );
					// 获取交接班记录
					$record = $this->getShiftRecord ( $last_shift_id );
					$lastshift_note = explode('---', $record ['note']);
				} else {
					$lastshift_note = '';
				}
				// 根据工班号从派工单获取业务系统与指令
				$Dispatch = new \Common\Model\DispatchModel();
				$res_repair = $Dispatch->field('instruction_id,business')->where ( "shift_id='$shift_id'" )->select ();
				foreach ( $res_repair as $key => $r ) {
					// 指令ID
					$instruction_id = $r['instruction_id'];
					// 区分业务系统
					switch ($r['business']) {
						case 'qbzx' :
							// 获取所有指令下面的配箱及配箱装箱、状态
							$QbzxInstructionCtn = new \Common\Model\QbzxInstructionCtnModel();
							$ctnList = $QbzxInstructionCtn->where ( "instruction_id='$instruction_id'" )->field ( 'id,status' )->select ();
							//判断指令下的工作中的配箱是否暂停作业
							foreach($ctnList as $v)
							{
								if($v['status'] == 1)
								{
									$operation = new \Common\Model\QbzxOperationModel();
									$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop')->find();
									if($res['is_stop'] != 'Y')
									{
										$res = array(
												'code'  =>  "202",
												'msg'   =>  "该工班还有工作的箱子未暂停作业"
										);
										return $res;
										exit;
									}
								}
							}
							$res_repair[$key]['instruction_ctn_count'] = count($ctnList);
							//获取指令箱已完成数量
							$finishnum = 0;
							$weinum = 0;
							$cansun = 0;
							foreach($ctnList as $k){
								static $finishnum=0;
								static $weinum=0;
								static $cansun=0;
								if($k['status'] == 2)
								{
									$finishnum++;
								}elseif($k['status']==0 || $k['status']==1){
									$weinum++;
								}elseif($k['status'] == '-1'){
									$cansun++;
								}
							}
							$res_repair[$key]['finishnum'] = $finishnum;
							$res_repair[$key]['weinum'] = $weinum;
							$res_repair[$key]['cansun'] = $cansun;
							//根据指令ID获取预报计划ID 获取预报计划下面箱子的总数
							$instruction = new \Common\Model\QbzxInstructionModel();
							$plan_id = $instruction->field('plan_id')->where("id='$instruction_id'")->find();
							$plan = new \Common\Model\QbzxPlanModel();
							$plan_id = $plan_id['plan_id'];
							$planctn = $plan->field('total_ctn')->where("id='$plan_id'")->find();
							$plancount = $planctn['total_ctn'];
							$res_repair[$key]['plan_ctn_count'] = $plancount;
							break;
						case 'dd' :
							//根据指令ID获取预报的ID
							$instruction = new \Common\Model\DdInstructionModel();
							$planid = $instruction->field('plan_id')->where("id='$instruction_id'")->find();
							// 获取预报下面的箱总数
							$DdPlanContainer = new \Common\Model\DdPlanContainerModel ();
							$plan_id = $plan_id['plan_id'];
							$dd_ctn = $DdPlanContainer->where('id,status')->where ( "plan_id=$plan_id" )->select();
							//判断指令下的工作中的配箱是否暂停作业
							foreach($dd_ctn as $v)
							{
								if($v['status'] == 1)
								{
									$operation = new \Common\Model\DdOperationModel();
									$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop')->find();									$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop')->find();
									if($res['is_stop'] != 'Y')
									{
										$res = array(
												'code'  =>  "202",
												'msg'   =>  "该工班还有工作的箱子未暂停作业"
										);
										return $res;
										exit;
									}
								}
							}
							//总箱数
							$res_repair[$key]['instruction_ctn_count'] = count($dd_ctn);
							//已完成、未完成，相残损
							$finishnum = 0;
							$weinum = 0;
							$cansun = 0;
							foreach($dd_ctn as $k){
								static $finishnum=0;
								static $weinum=0;
								static $cansun=0;
								if($k['status'] == 2)
								{
									$finishnum++;
								}elseif($k['status']==0 || $k['status']==1){
									$weinum++;
								}elseif($k['status'] == '-1'){
									$cansun++;
								}
							}
							$res_repair[$key]['finishnum'] = $finishnum;
							$res_repair[$key]['weinum'] = $weinum;
							$res_repair[$key]['cansun'] = $cansun;
							break;
						case 'cfs' :
							// ④修改指令状态为已派工--除了已完成的指令（2为已完成）
							$CfsInstruction = new \Common\Model\CfsInstructionCtnModel();
							$instruction_ctn = $CfsInstruction->where ( "id=$instruction_id" )->select();
							//判断指令下的工作中的配箱是否暂停作业
							foreach($instruction_ctn as $v)
							{
								if($v['status'] == 1)
								{
									$operation = new \Common\Model\CfsOperationModel();
									$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop')->find();									$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop')->find();
									if($res['is_stop'] != 'Y')
									{
										$res = array(
												'code'  =>  "202",
												'msg'   =>  "该工班还有工作的箱子未暂停作业"
										);
										return $res;
										exit;
									}
								}
							}
							$res_repair[$key]['instruction_ctn_count'] = count($instruction_ctn);

							$finishnum = 0;
							$weinum = 0;
							$cansun = 0;
							foreach($instruction_ctn as $k){
								static $finishnum=0;
								static $weinum=0;
								static $cansun=0;
								if($k['status'] == 2)
								{
									$finishnum++;
								}elseif($k['status']==0 || $k['status']==1){
									$weinum++;
								}elseif($k['status'] == '-1'){
									$cansun++;
								}
							}
							$res_repair[$key]['finishnum'] = $finishnum;
							$res_repair[$key]['weinum'] = $weinum;
							$res_repair[$key]['cansun'] = $cansun;
							break;
					}
				}
				$res = array (
						'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
						'msg'  => '成功！',
						'note' => $lastshift_note,
						'res_repair' => $res_repair   //成功返回数据
				);
			}
		}
		return $res;
	}
	
	/**
	 * 根据用户ID、工班组ID获取当前工班下面的所有起驳指令
	 * @param int $uid:用户ID
	 * @param string $shift_id:工班ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function get_instruction($uid, $shift_id) {
		// ②判断用户是否为当班理货长
		$res_m = $this->isWorkMaster ( $uid, $shift_id );
		if ($res_m ['code'] != 0) {
			// 不是当班理货长，返回码和检验用户是否为当班理货长采用的一致
			$res = $res_m;
		} else {
			// 根据工班号从派工单获取业务系统与指令
			$Dispatch = new \Common\Model\DispatchModel();
			$res_repair = $Dispatch->field('instruction_id')->where ( "shift_id='$shift_id' and business='qbzx'" )->select ();
			//判断派工是否存在，不存在提示尚未派工
			if($res_repair)
			{
// 				$instructionidlist = array_column($res_repair, 'instruction_id');
				foreach($res_repair as $vo)
				{
					$instructionidlist[] = $vo['instruction_id'];
				}
				$res = array (
						'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
						'msg'  => '成功！',
						'res' => $instructionidlist   //成功返回数据
				);
			}else{
				$res = array(
						'code'  =>   1014,
						'msg'   =>   '工班尚未派工'
				);
			}
		}
		return $res;
	}
	
	/**
	 * 判断该工班下面工作的箱是否暂停作业
	 *
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function isstop($uid, $shift_id)
	{
		// ①判断该工班是否已交班
		$res_s = $this->is_succeed ( $shift_id );
		if ($res_s ['code'] != 0) {
			// 工班已交班，返回码和判断工班是否交班采用的一致
			$res = $res_s;
		} else {
			// 工班未交班
			// ②判断用户是否为当班理货长
			$res_m = $this->isWorkMaster ( $uid, $shift_id );
			if ($res_m ['code'] != 0) {
				// 不是当班理货长，返回码和检验用户是否为当班理货长采用的一致
				$res = $res_m;
			} else {
				// ③用户为当班理货长，并且工班未交班
				// 根据工班号从派工单获取业务系统与指令
				$Dispatch = new \Common\Model\DispatchModel();
				$res_repair = $Dispatch->field('instruction_id,business')->where ( "shift_id='$shift_id'" )->select ();
				foreach ( $res_repair as $key => $r ) {
					// 指令ID
					$instruction_id = $r['instruction_id'];
					// 区分业务系统
					switch ($r['business']) {
						case 'qbzx' :
							// 获取所有指令下面的配箱及配箱装箱、状态
							$QbzxInstructionCtn = new \Common\Model\QbzxInstructionCtnModel();
							$ctnList = $QbzxInstructionCtn->where ( "instruction_id='$instruction_id'" )->field ( 'id,status' )->select ();
							//判断指令下的工作中的配箱是否暂停作业
							foreach($ctnList as $v)
							{
								if($v['status'] == 1)
								{
									$operation = new \Common\Model\QbzxOperationModel();
									$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop,step')->find();
									
									if($res_r['is_stop'] != 'Y' and $res_r['step'] != '5' and $res_r['step'] != '0')
									{
										$res = array(
												'code'  =>  "211",
												'msg'   =>  "该工班（起驳）还有工作的箱子未暂停作业"
										);
										return $res;
										exit;
									}
								}
							}
							break;
						case 'cfs' :
							// ④修改指令状态为已派工--除了已完成的指令（2为已完成）
							$CfsInstruction = new \Common\Model\CfsInstructionCtnModel();
							$instruction_ctn = $CfsInstruction->where ( "id=$instruction_id" )->select();
							//判断指令下的工作中的配箱是否暂停作业
							foreach($instruction_ctn as $v)
							{
								if($v['status'] == 1)
								{
									$operation = new \Common\Model\CfsOperationModel();
									$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop')->find();									$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop')->find();
									if($res_r['is_stop'] != 'Y')
									{
										$res = array(
												'code'  =>  "211",
												'msg'   =>  "该工班（cfs）还有工作的箱子未暂停作业"
										);
										return $res;
										exit;
									}
								}
							}
							break;
						case 'dd':
							break;
							
					}
				}
				$res = array (
						'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
						'msg'  => '成功！'
				);
			}
		}
		return $res;
	}
	
	//判断该工班下面的箱已铅封未审核
// 	public function isexamine( $uid, $shift_id )
// 	{
// 		// 根据工班号从派工单获取业务系统与指令
// 		$Dispatch = new \Common\Model\DispatchModel();
// 		$res_repair = $Dispatch->field('instruction_id,business')->where ( "shift_id='$shift_id'" )->select ();
// 		foreach ( $res_repair as $key => $r ) {
// 			// 指令ID
// 			$instruction_id = $r['instruction_id'];
// 			// 区分业务系统
// 			switch ($r['business']) {
// 				case 'qbzx' :
// 					// 获取所有指令下面的配箱及配箱装箱、状态
// 					$QbzxInstructionCtn = new \Common\Model\QbzxInstructionCtnModel();
// 					$ctnList = $QbzxInstructionCtn->where ( "instruction_id='$instruction_id'" )->field ( 'id,status' )->select ();
// 					//判断指令下的已铅封的配箱是否审核作业
// 					foreach($ctnList as $v)
// 					{
// 						if($v['status'] == 2)
// 						{
// 							$operation = new \Common\Model\QbzxOperationModel();
// 							$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop,step,operation_examine')->find();
								
// 							if($res_r['is_stop'] != 'Y' and $res_r['step'] != '5' and $res_r['step'] != '0')
// 							{
// 								$res = array(
// 										'code'  =>  "211",
// 										'msg'   =>  "该工班还有起驳已铅封未审核的箱子"
// 								);
// 								return $res;
// 								exit;
// 							}
// 						}
// 					}
// 					break;
// 				case 'cfs' :
// 					// ④修改指令状态为已派工--除了已完成的指令（2为已完成）
// 					$CfsInstruction = new \Common\Model\CfsInstructionCtnModel();
// 					$instruction_ctn = $CfsInstruction->where ( "id=$instruction_id" )->select();
// 					//判断指令下的工作中的配箱是否暂停作业
// 					foreach($instruction_ctn as $v)
// 					{
// 						if($v['status'] == 2)
// 						{
// 							$operation = new \Common\Model\CfsOperationModel();
// 							$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop')->find();									$res_r = $operation->where("ctn_id='".$v['id']."'")->field('is_stop')->find();
// 							if($res_r['is_stop'] != 'Y')
// 							{
// 								$res = array(
// 										'code'  =>  "211",
// 										'msg'   =>  "该工班还有cfs已铅封未审核的箱子"
// 								);
// 								return $res;
// 								exit;
// 							}
// 						}
// 					}
// 					break;
// 				case 'dd':
// 					break;
						
// 			}
// 		}
// 	}
}
?>