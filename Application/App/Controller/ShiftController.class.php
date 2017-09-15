<?php
/**
 * 公用业务类接口
 */
namespace App\Controller;
use App\Common\BaseController;

class ShiftController extends BaseController
{
	/**
	 * 签到
	 * @param int $uid:用户ID
	 * @param int $department_id:部门ID
	 * @param string $date:日期，格式20160622
	 * @param int $classes:班次，1白班 2夜班
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param shift:工班信息，只有在历史工作未交班的情况下返回
	 */
	public function signIn()
	{
		if(I('post.uid') and I('post.department_id') and I('post.date') and I('post.classes'))
		{
			$uid=I('post.uid');
			$department_id=I('post.department_id');
			$date=I('post.date');
			$classes=I('post.classes');
			$shift=new \Common\Model\ShiftModel();
			$res=$shift->signIn($uid,$department_id,$date,$classes);
		}else {
			//参数不正确，参数缺失
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 判断用户是否可以接班
	 * @param int $uid:用户ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param lastshift:上一工班信息，只有在可以接班的情况下返回
	 */
	public function whetherTakeOver()
	{
		if(I('post.uid'))
		{
			$uid=I('post.uid');
			$shift=new \Common\Model\ShiftModel();
			$res=$shift->whetherTakeOver($uid);
			\Common\Common\LogController::info(json_encode($res,JSON_UNESCAPED_UNICODE));
		}else {
			//参数不正确，参数缺失
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 接班
	 * @param int $uid:用户ID
	 * @param string $shift_id:工班ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function succeed()
	{
		if(I('post.uid'))
		{
			$uid=I('post.uid');
			$shift_id=I('post.shift_id');
			$shift=new \Common\Model\ShiftModel();
			$res=$shift->succeed($uid,$shift_id);
		}else {
			//参数不正确，参数缺失
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 检验用户是否为当班理货长
	 * @param int $uid:用户ID
	 * @param string $shift_id:工班ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function isWorkMaster()
	{
		if(I('post.uid') and I('post.shift_id'))
		{
			$uid=I('post.uid');
			$shift_id=I('post.shift_id');
			$shift=new \Common\Model\ShiftModel();
			$res=$shift->isWorkMaster($uid, $shift_id);
		}else {
			//参数不正确，参数缺失
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 交班
	 * @param int $uid:用户ID
	 * @param string $note:交班信息
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function transfer()
	{
		if(I('post.uid') and I('post.note'))
		{
			$uid=I('post.uid');
			$note=I('post.note');
			//根据用户ID获取其所属工班ID
			$user=new \Common\Model\UserModel();
			$res_u=$user->is_sign($uid);
			if($res_u['code']!=0)
			{
				$res=$res_u;
			}else {
				$shift_id=$res_u['shift_id'];
				$shift=new \Common\Model\ShiftModel();
				$res=$shift->transfer($uid, $shift_id, $note);
			}
		}else {
			//参数不正确，参数缺失
			$res = array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 交接班查询
	 * @param int $uid:用户ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param content:成功时返回符合条件的记录
	 */
	public function shiftDetails()
	{
		if(I('post.uid'))
		{
			$uid = I('post.uid');
			$user = new \Common\Model\UserModel();
			$res_g = $user->where("uid=$uid")->field('shift_id')->find();
			$shift_id = $res_g['shift_id'];
			$shift=new \Common\Model\ShiftModel();
			$res_shiftts=$shift->getShiftRecord($shift_id);
			if($res_shiftts !== false)
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'content'=>$res_shiftts
				);
			}else{
				//数据库连接错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
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