<?php
/**
 * 公共管理类
 * 派工管理
 */

namespace App\Controller;
use App\Common\BaseController;

class DispatchController extends BaseController
{
	/**
	 * 派工查询
	 * @param int $department_id:部门ID        	
	 * @param char $classes:班次 1白班 2夜班
	 * @param date $date:签到日期        	
	 * @return array|boolean
	 * @param @return code:返回码
	 * @param @return msg:返回码说明
	 * @param @return list:成功时返回相应信息
	 */
	public function dispatchSearch() {
		if (I ( 'post.department_id' ) and I ( 'post.classes' ) and I ( 'post.date' )) 
		{
			$department_id = I ( 'post.department_id' );
			$classes = I ( 'post.classes' );
			$date = I ( 'post.date' );
			$where = array (
					'department_id' => $department_id,
					'classes' => $classes,
					'date' => $date 
			);
			$shift = new \Common\Model\ShiftModel ();
			$shiftMsg = $shift->where ( $where )->find ();
			if (! $shiftMsg) 
			{
				// 206 该工班不存在
				$res = array (
						'code' => $this->ERROR_CODE_SHIFT ['SHIFT_NOT_EXIST'],
						'msg' => $this->ERROR_CODE_SHIFT_ZH [$this->ERROR_CODE_SHIFT ['SHIFT_NOT_EXIST']] 
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
				exit ();
			}
			$user = new \Common\Model\UserModel ();
			$dispatch = new \Common\Model\DispatchModel ();
			
			$list = $dispatch->where ( "shift_id='" . $shiftMsg ['shift_id'] . "'" )->select ();
			if ($list !== false) 
			{
				$n = count ( $list );
				for($i = 0; $i < $n; $i ++) 
				{
					$dispatchdetail = new \Common\Model\DispatchDetailModel ();
					$detaillist = $dispatchdetail->where ( "dispatch_id='" . $list [$i] ['id'] . "'" )->field ( "clerk_id" )->select ();
					$userlist = '';
					for($j = 0; $j < count ( $detaillist ); $j ++) 
					{
						$username = $user->where ( "uid='" . $detaillist [$j] ['clerk_id'] . "'" )->field ( "user_name" )->find ();
						$userlist .= $username ['user_name'] . ",";
					}
					$list [$i] ['userlist'] = substr ( $userlist, 0, - 1 );
					$chieftally = $list [$i] ['chieftally'];
					$res_a = $user->where ( "uid='$chieftally'" )->field ( "user_name" )->find ();
					$list [$i] ['chieftally_name'] = $res_a ['user_name'];
				}
				$res = array (
						'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
						'msg' => '成功',
						'list' => $list 
				);
			} else {
				// 506 该指令尚未派工，请先派工
				$res = array (
						'code' => $this->ERROR_CODE_INSTRUCTION ['NEED_DISPATCH'],
						'msg' => $this->ERROR_CODE_INSTRUCTION_ZH [$this->ERROR_CODE_INSTRUCTION ['NEED_DISPATCH']] 
				);
				echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
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
	 * 派工详情
	 * @param varchar $business:所属业务 qbzx：起驳装箱 dd：门到门  cfs:cfs装箱
	 * @param int $instruction_id:指令ID
	 * @param @return array
	 * @param @return code:返回码
	 * @param @return msg:返回码说明
	 * @param @return dispatchMsg:成功时返回派工信息
	 * @param @return dispatchlist:成功时返回派工详情列表信息
	 */
	public function dispatchDetail()
	{
		if(I('post.business') and I ( 'post.instruction_id' ))
		{
			$business = I ( 'post.business' );
			$instruction_id = I ( 'post.instruction_id' );
				
			$where = array (
					'business' => $business,
					'instruction_id' => $instruction_id
			);
			$dispatch = new \Common\Model\DispatchModel();
			$dispatchMsg = $dispatch->where ( $where )->find ();
			$where = array (
					'uid' => $dispatchMsg ['chieftally']
			);
			//获取理货长姓名
			$user = new \Common\Model\UserModel();
			$u = $user->where ( $where )->find ();
			$dispatchMsg ['chieftally_name'] = $u ['user_name'];
			$dispatch_id = $dispatchMsg['id'];
			//获取派工详情列表
			$dispatchDetail = new \Common\Model\DispatchDetailModel();
			$dispatchlist = $dispatchDetail->where("dispatch_id='$dispatch_id'")->select();
				
			if($dispatchlist !== false)
			{
				for($i = 0; $i < count ( $dispatchlist ); $i ++) {
					$where = array (
							'uid' => $dispatchlist [$i] ['clerk_id']
					);
					$u = $user->where ( $where )->find ();
					$dispatchlist [$i] ['clerk_name'] = $u ['user_name'];
				}
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'dispatchMsg'=>$dispatchMsg,
						'dispatchlist'=>$dispatchlist
				);
			}else{
				//数据库连接错误
				$res = array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'dispatchMsg'=>'',
						'dispatchlist'=>''
				);
			}
		}else{
			//参数不正确 参数缺失
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $odlist, JSON_UNESCAPED_UNICODE );
	}
}