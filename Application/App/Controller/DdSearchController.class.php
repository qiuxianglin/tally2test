<?php

/**
 * APP接口
 * 预报计划查询接口
 * @author 殷根朋  2016-7-11
 */

namespace App\Controller;
use App\Common\BaseController;

header ( "Access-Control-Allow-Origin: *" );

class DdSearchController extends BaseController{
	/**
	 * 预计划查询
	 * @param unpackagingplace 拆箱地点
	 * @param orderdate 委托时间
	 * @param applyname 申报公司
	 * @param vslname   中文船名
	 * @param voyage    船次
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回查询结果列表
	 */	
	public function plan(){
// 		$where="is_valid ='Y'";
		$where = "1";
		if(I('post.unpackagingplace')){
			$unpackagingplace=I('post.unpackagingplace');
			$where.=" and unpackagingplace='$unpackagingplace'";
		}
		if(I('post.orderdate')){
			$orderdate=I('post.orderdate');
			$where.=" and orderdate like '%$orderdate%'";
		}
		if(I('post.applyname')){
			$applyname=I('post.applyname');
			$where.=" and applyname='$applyname'";
		}
		if(I('post.vslname')){
			$vslname=I('post.vslname');
			$where.=" and vslname='$vslname'";
		}
		if(I('post.voyage')){
			$voyage=I('post.voyage');
			$where.=" and voyage='$voyage'";
		}
		$plan = new \Common\Model\DdPlanModel();
		$list = $plan->where($where)->order("id desc")->limit(0,50)->select();
		//查询委托方简称
		$customer=new \Common\Model\CustomerModel();
		$num=count($list);
		for($i=0;$i<$num;$i++)
		{
			$customer_code=$list[$i]['paycode'];
			$res_c=$customer->where("customer_code='$customer_code'")->field('customer_shortname')->find();
			if($res_c['customer_shortname'])
			{
				$list[$i]['customer_shortname']= $res_c['customer_shortname'];
			}else {
				$list[$i]['customer_shortname']= '';
			}
		}
		
		if($list!==false){
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$list
			);
		}else{
			//数据库连接错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list'=>'',
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 指令查询
	 * @param unpackagingplace 拆箱地点
	 * @param status  作业状态
	 * @param date    指令日期
	 * @param vslname 中文船名
	 * @param voyage  船次
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param result:成功时返回指令查询结果列表
	 */
	public function instruction()
	{
		$where = '1';
		if(I('post.unpackagingplace'))
		{
			$unpackagingplace=I('post.unpackagingplace');
			$where.=" and p.unpackagingplace='$unpackagingplace'";
		}
		if(I('post.status')!=='')
		{
			$status=I('post.status');
			$where.=" and i.status='$status'";
		}
		if(I('post.date'))
		{
			$date=I('post.date');
			$where.=" and i.date='$date'";
		}
		if(I('post.vslname'))
		{
			$vslname=I('post.vslname');
			$where.=" and p.vslname='$vslname'";
		}
		if(I('post.voyage'))
		{
			$voyage=I('post.voyage');
			$where.=" and p.voyage='$voyage'";
		}
		$sql="select p.*,i.* from __PREFIX__dd_plan p,__PREFIX__dd_instruction i where p.id=i.plan_id and p.is_valid='Y' $where order by i.id desc limit 0,50";
		$list= M()->query($sql);
		
		if($list!==false){
			//新增状态对应的文字描述
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				//拆箱方式
				$operate_contanier_method_d = json_decode ( operate_contanier_method_d, true );
				$operating_type = $operate_contanier_method_d[$list[$i]['operating_type']];
				$list[$i]['operating_type_zh'] = $operating_type;

				// 指令状态
				$instruction_status_d = json_decode ( instruction_status_d, true );
				$status_zh = $instruction_status_d [$list [$i] ['status']];
				$list [$i] ['status_zh'] = $status_zh;
			}
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$list,
			);
		}else{
			//数据库连接错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list'=>'',
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 派工查询
	 * @param int $department_id:部门组ID
	 * @param char $classes:班次 1白班 2夜班
	 * @param string $date:工班日期   格式：2016-07-22
	 * @param string $business:业务系统   qbzx 起泊装箱 dd 门到门拆箱(默认) cfs CFS装箱
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param list:成功时返回派工记录
	 */
	public function dispatching()
	{
		if(I('post.department_id') and I('post.classes') and I('post.date'))
		{
			$department_id=I('post.department_id');
			$classes=I('post.classes');
			$date=I('post.date');
			//业务系统
			if(I('post.business'))
			{
				$business=I('post.business');
			}else {
				$business='dd';
			}
			
			$department = new \Common\Model\DepartmentModel();
			$dispatch = new \Common\Model\DispatchModel();
			$user = new \Common\Model\UserModel();
			//根据部门组ID获取二级部门代码
			$res_d = $department->where("id=$department_id")->field('department_code')->find();
			if($res_d['department_code']!='')
			{
				$department_code=$res_d['department_code'];
				$where=" shift_id like '%$department_code%' and business='$business'";
				$res_o=$dispatch->where($where)->select();
				if($res_o!==false)
				{
					$list=array();
					$i=0;
					foreach ($res_o as $repair)
					{
						$shift_classes=substr($repair['shift_id'], -1);
						//筛选出班次正确的
						if($shift_classes == $classes)
						{
							//工班ID
							$list[$i]['shift_id']=$repair['shift_id'];
							//派工时间
							$list[$i]['dispatching_time']= $repair['dispatch_time'];
							//指令ID
							$list[$i]['instruction_id']=$orderrepair['instruction'];
							//当班理货长
							$chieftally_id=$repair['chieftally'];
							$uMsg=$user->where("uid=$chieftally_id")->field('user_name')->find();
							$list[$i]['chieftally_name']=$uMsg['user_name'];
							//理货员
							//根据派工ID获取理货员
							$dispatch_id=$repair['id'];
							$sql="select u.user_name from __PREFIX__user u,__PREFIX__dispatch_detail d where d.dispatch_id=$dispatch_id and d.clerk_id=u.uid";
							$res_u=M()->query($sql);
							foreach ($res_u as $u)
							{
								$clerk_name .= $u['user_name'].' ';
							}
							if($clerk_name)
							{
								$list[$i]['clerk_name']=$clerk_name;
							}else {
								$list[$i]['clerk_name']='';
							}
							$clerk_name='';
							$i++;
						}
					}
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'list'=>$list
					);
				}else {
					//数据库连接错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
							'list'=>''
					);
				}
			}else {
				//不存在该部门组!
				$res=array(
						'code'=>301,
						'msg'=>'不存在该部门组!'
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
	
	/**
	 * 作业查询
	 * @param string $ctnno:箱号
	 * @param string $vslname:中文船名
	 * @param string $voyage:航次
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param ctn_msg:成功时返回符合条件的箱信息
	 * @return @param operation_msg:成功时返回符合条件的箱的作业信息
	 * @return @param list:成功时返回符合条件的箱的关列表
	 */
	public function operation()
	{
		if(I('post.ctnno') and I('post.vslname') and I('post.voyage'))
		{
			$ctnno=I('post.ctnno');
			$vslname=I('post.vslname');
			$voyage=I('post.voyage');
			$sql = "select c.* from __PREFIX__dd_plan p,__PREFIX__dd_plan_container c where p.vslname='$vslname' and p.voyage='$voyage' and c.ctnno='$ctnno' and c.plan_id=p.id";
			$res_c=M()->query($sql);
			if($res_c)
			{
				$ctn_msg=$res_c[0];
				$ctn_id = $res_c[0]['id'];
				//根据操作员ID获取操作员详细信息
				$operator_id=$res_c[0]['operator'];
				if($operator_id!='')
				{
					$user=new \Common\Model\UserModel();
					$usermsg=$user->getUserMsg($operator_id);
					$ctn_msg['operator_name']=$usermsg['user_name'];
					$ctn_msg['operator_staffno']=$usermsg['staffno'];
				}else {
					$ctn_msg['operator_name']='';
					$ctn_msg['operator_staffno']='';
				}
				//获取作业信息
				$operation=new \Common\Model\DdOperationModel();
				$operation_msg = $operation->where("ctn_id=$ctn_id")->find();
				if($operation_msg['id']!='')
				{
					$operation_id=$operation_msg['id'];
					//获取关列表
					$level=new \Common\Model\DdOperationLevelModel();
					$list=$level->where("operation_id=$operation_id")->field('num,damage_num,level_num')->select();
					if($operation_msg !== false and $list !== false)
					{
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'成功',
								'ctn_msg'=>$ctn_msg,
								'operation_msg'=>$operation_msg,
								'list'=>$list
						);
					}else {
						//数据库连接错误
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}else {
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'ctn_msg'=>$ctn_msg,
							'operation_msg'=>'',
							'list'=>''
					);
				}
			}else{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'ctn_msg'=>'',
						'operation_msg'=>'',
						'list'=>''
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
	
	/**
	 * 交接班查询
	 * @param int $uid:用户ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param content:成功时返回符合条件的记录
	 */
	public function shift()
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
	
	/**
	 * 单证查询
	 * @param string $ctn_no:箱号
	 * @param int $ship_name:船名
	 * @param string $vargo:航次
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param content:成功时返回符合条件的单证列表
	 */
	public function document()
	{
		$where = '1';
		if(I('post.ctn_no'))
		{
			$ctn_no=I('post.ctn_no');
			$where.=" and p.ctn_no = '$ctn_no'";
		}
		if(I('post.ship_name'))
		{
			$ship_name = I('post.ship_name');
			$where.=" and p.ship_name = '$ship_name'";
		}
		if(I('post.vargo'))
		{
			$vargo=I('post.vargo');
			$where.=" and p.vargo = '$vargo'";
		}
		
		$prove = new \Common\Model\DdProveModel();
		$list= $prove->where($where)->order("id desc")->limit(0,50)->select();
				
		if($list!==false)
		{
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$list
			);
		}else {
			//数据库连接错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list'=>''
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取单证详情
	 * @param int $id:单证ID        	
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param content:成功时返回符合条件的单证记录
	 */
	public function getDocumentMsg() 
	{
		if (I ( 'post.id' )) 
		{
			$id = I ( 'post.id' );
			$prove = new \Common\Model\DdProveModel();
			$res_c = $prove->where("id='$id'")->find();
			if ($res_c !== false) 
			{
				$content=$res_c [0];
				$content['content']=json_decode($res_c [0]['content']);
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg' => '成功',
						'content' => $content 
				);
			} else {
				//数据库连接错误
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
				)
				;
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
}

