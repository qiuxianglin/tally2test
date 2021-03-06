<?php
/**
 * CFS装箱
 * 派工管理
 */
namespace Index\Controller;
use Index\Common\BaseController;

class CfsDispatchController extends BaseController
{
	//新增派工
	public function add($instruction_id)
	{
		layout(false);
		$uid=$_SESSION['uid'];
		//判断指令是否已派工
		$cfsInstruction=new \Common\Model\CfsInstructionModel();
		$res_s=$cfsInstruction->where("id=$instruction_id")->field('status')->find();
		if($res_s['status']!='0')
		{
			echo '<script>alert("该指令已派工，无法新增派工！");top.location.reload(true);window.close();</script>';
			exit();
		}
		//判断用户是否有权限进行派工
		$dispatch=new \Common\Model\DispatchModel();
		$res=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'cfs');
		if($res['code']!=0)
		{
			$this->error($res['msg']);
		}else {
			if(I('post.'))
			{
				//新增
				$shift_id=$res['shift_id'];
				$chiefTally=$uid;
				$business='cfs';
				if(I('post.work')!='')
				{
					$workerlist2=I('post.work');
				}else {
					$this->error('至少选择一位理货员进行派工！');
				}
				$res_a=$dispatch->addTask($chiefTally, $shift_id, $instruction_id, $business, $workerlist2);
				if($res_a!==false)
				{
					$data=array(
							'status'=>'1',
					);
					$res_i=$cfsInstruction->where("id=$instruction_id")->save($data);
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
		$cfsInstruction=new \Common\Model\CfsInstructionModel();
		$res_s=$cfsInstruction->where("id=$instruction_id")->field('status')->find();
		if($res_s['status']=='0')
		{
			echo '<script>alert("该指令尚未派工，请先派工！");top.location.reload(true);window.close();</script>';
			exit();
		}
		//判断用户是否有权限进行派工
		$dispatch=new \Common\Model\DispatchModel();
		$res=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'cfs');
		if($res['code']!=0)
		{
			$this->error($res['msg']);
		}else {
			if(I('post.'))
			{
				//修改派工
				if(I('post.work')!='')
				{
					$workerlist2=I('post.work');
				}else {
					$this->error('至少选择一位理货员进行派工！');
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
				$dispatch_detail=$dispatch->getDetail($instruction_id, 'cfs');
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
	public function del()
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
			//var_dump($res_d);die();
			if($res_d!==false)
			{
				if($res_d['mark']=='1')
				{
					$this->error('该工班已交班，不准取消派工！');
				}else {
					//判断指令是否已派工
					$cfsInstruction=new \Common\Model\CfsInstructionModel();
					$res_s=$cfsInstruction->where("id=$instruction_id")->field('status')->find();
					if($res_s['status']=='0')
					{
						$this->error('该指令尚未派工，请先派工！');
					}
					//判断用户是否有权限进行派工
					$res_u=$dispatch->isPermissionsForDispatching($uid, $instruction_id,'cfs');
					if($res_u['code']!=0)
					{
						//用户没有权限
						$this->error($res_u['msg']);
					}else {
						//查询指令下的配箱
						$sql="select c.id from __PREFIX__cfs_instruction_ctn c,__PREFIX__cfs_instruction i where i.id=c.instruction_id and i.id=$instruction_id";
						$res_ctn=M()->query($sql);
						if($res_ctn!==false)
						{
							foreach ($res_ctn as $c)
							{
								$ctn_arr[]=$c['id'];
							}
							$ctn_allid=implode($ctn_arr, ',');
							if($ctn_allid!='')
							{
								//判断指令下是否存在已作业的箱子，存在不允许取消派工
								$operation = new \Common\Model\CfsOperationModel();
								$res_o=$operation->where("ctn_id in ($ctn_allid)")->select();
								if($res_o)
								{
									$this->error('该指令存在正在作业的箱子，无法取消派工！');
								}else {
									//指令下不存在作业中的配箱，可以取消
									$res_cancel=$dispatch->cancel($dispatch_id);
									if($res_cancel!==false)
									{
										//修改指令状态为未派工
										$data=array(
												'status'=>'0'
										);
										$cfsInstruction->where("id=$instruction_id")->save($data);
										$this->success('取消派工成功！');
									}else {
										$this->error('数据库连接错误');
									}
								}
							}else {
								//指令下不存在配箱，可以直接取消
								$res_cancel=$dispatch->cancel($dispatch_id);
								if($res_cancel!==false)
								{
									//修改指令状态为未派工
									$data=array(
											'status'=>'0'
									);
									$cfsInstruction->where("id=$instruction_id")->save($data);
									$this->success('取消派工成功！');
								}else {
									$this->error('数据库连接错误');
								}
							}
						}else {
							$this->error('数据库连接错误');
						}
					}
				}
			}else {
				$this->error('数据库连接错误');
			}
		}
	}
}