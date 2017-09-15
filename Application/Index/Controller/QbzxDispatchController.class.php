<?php
/**
 * 起泊装箱
 * 派工管理
 */
namespace Index\Controller;
use Index\Common\BaseController;

class QbzxDispatchController extends BaseController
{
	//新增派工
	public function add($instruction_id)
	{
		layout(false);
		$uid=$_SESSION['uid'];
		//判断指令是否已派工
		$instruction=new \Common\Model\QbzxInstructionModel();
		$res_s=$instruction->where("id=$instruction_id")->field('status')->find();
		if($res_s['status']!='0')
		{
			echo '<script>alert("该指令已派工，无法新增派工！");top.location.reload(true);window.close();</script>';
			exit();
		}
		//判断用户是否有权限进行派工
		$dispatch=new \Common\Model\DispatchModel();
		$res=$dispatch->isPermissionsForDispatching($uid, $instruction_id);
		if($res['code']!=0)
		{
			$msg = $res['msg'];
			echo '<script>alert("'.$msg.'");top.location.reload(true);window.close();</script>';
		}else {
			if(I('post.'))
			{
				//新增
				$work_id=$res['shift_id'];
				$chiefTally=$uid;
				$business='qbzx';
				if(I('post.work')!='')
				{
					$workerlist2=I('post.work');
				}else {
					$this->error('至少选择一位理货员进行派工！');
				}
				$res_a=$dispatch->addTask($chiefTally, $work_id, $instruction_id, $business, $workerlist2);
				if($res_a!==false)
				{
					//将起泊装箱的指令状态改为已派工
					$data=array(
							'status'=>'1'
					);
					$res_i=$instruction->where("id=$instruction_id")->save($data);
					if($res_i!==false)
					{
						echo '<script>alert("派工成功!");top.location.reload(true);window.close();</script>';
					}else {
						$this->error('操作失败！');
					}
				}else {
					$this->error('操作失败！');
				}
			}else {
				$workerlist=$res['workerlist'];
				$this->assign('workerlist',$workerlist);
				$this->assign('instruction_id',$instruction_id);
				$this->display();
			}
		}
	}
	
	//修改派工
	public function edit($instruction_id,$dispatch_id)
	{
		layout(false);
		$uid=$_SESSION['uid'];
		//判断指令是否已派工
		$instruction=new \Common\Model\QbzxInstructionModel();
		$res_s=$instruction->where("id=$instruction_id")->field('status')->find();
		if($res_s['status']=='0')
		{
			echo '<script>alert("该指令尚未派工，请先派工！");top.location.reload(true);window.close();</script>';
			exit();
		}
		//判断用户是否有权限进行派工
		$dispatch=new \Common\Model\DispatchModel();
		$res=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'qbzx');
		if($res['code']!=0)
		{
			$this->error($res['msg']);
		}else {
			if(I('post.'))
			{
				$instruction_id = I('post.instruction_id');
				//修改派工
				if(I('post.work')!='')
				{
					$workerlist2=I('post.work');
				}else {
					$this->error('至少选择一位理货员进行派工！');
				}
				//获取原先派工的人员id列表
				$dispatchdetail=new \Common\Model\DispatchDetailModel();
				$uidlist = $dispatchdetail->getclerkidlist($dispatch_id);
				//判断原先派工列表跟修改后派工列表的差集
				$result1 = array_diff($uidlist,$workerlist2);
				
				//根据指令ID获取所有工作中的箱子的操作人ID
				$QbzxInstructionCtn = new \Common\Model\QbzxInstructionCtnModel();
				$ulist = $QbzxInstructionCtn->where("instruction_id='$instruction_id' and status='1'")->field('operator_id')->select();
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
					$this->error($username.'配箱已开始工作不能修改派工！');
				}
				$res_a=$dispatch->editTask($dispatch_id, $workerlist2);
				if($res_a!==false)
				{
					echo '<script>alert("修改派工成功!");top.location.reload(true);window.close();</script>';
				}else {
					$this->error('操作失败！');
				}
			}else {
				$workerlist=$res['workerlist'];
				$this->assign('workerlist',$workerlist);
				$this->assign('dispatch_id',$dispatch_id);
				$this->assign('instruction_id',$instruction_id);
				//获取派工详情
				$dispatch_detail=$dispatch->getDetail($instruction_id, 'qbzx');
				$workarr=$dispatch_detail['detail'];
				foreach($workarr as $w)
				{
					$is_dispatch[]=$w['uid'];
				}
				$this->assign('is_dispatch',$is_dispatch);
				$this->display();
			}
		}
	}
	
	//取消派工
	public function cancel()
	{
		layout(false);
		if(I('get.dispatch_id')  and I('get.instruction_id'))
		{
			$uid=$_SESSION['uid'];
			$dispatch_id=I('get.dispatch_id');
			$instruction_id=I('get.instruction_id');
			//判断工班是否已交班，已交班不允许取消派工
			$dispatch=new \Common\Model\DispatchModel();
			$res_d=$dispatch->where("id=$dispatch_id")->find();
			if($res_d!==false)
			{
				if($res_d['mark']=='1')
				{
					$this->error('该工班已交班，不准取消派工！');
				}else {
					//判断指令是否已派工
					$instruction = new \Common\Model\QbzxInstructionModel();
					$res_s=$instruction->where("id=$instruction_id")->field('status')->find();
					if($res_s['status']=='0')
					{
						$this->error('该指令尚未派工，请先派工！');
					}
					//判断用户是否有权限进行派工
					$res_u=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'qbzx');
					if($res_u['code']!=0)
					{
						//用户没有权限
						$this->error($res_u['msg']);
					}else {
						$instruction_ctn = new \Common\Model\QbzxInstructionCtnModel();
						$res_c = $instruction_ctn->where("instruction_id='$instruction_id' and status not in(-1,0)")->count();
						if($res_c > 0)
						{
							$this->error('该指令存在正在作业的箱子，无法取消派工！');
						}else {
							//指令下不存在配箱，可以直接取消
							$res_cancel=$dispatch->cancel($dispatch_id);
							if($res_cancel!==false)
							{
								//修改指令状态为未派工
								$data=array(
										'status'=>'0'
								);
								$instruction->where("id=$instruction_id")->save($data);
								$this->success('取消派工成功！');
							}else {
								$this->error('数据库连接错误');
							}
						}
					}
				}
			}else {
				$this->error('数据库连接错误');
			}
		}
	}
}
?>