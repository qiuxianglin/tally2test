<?php
/**
 * 工班管理
 */
namespace Index\Controller;
use Index\Common\BaseController;

class WorkController extends BaseController
{
	//签到
	public function signin()
	{
		layout(false);
		//获取部门列表
		$department=new \Common\Model\DepartmentModel();
		$departmentList=$department->getDepartmentList();
		$this->assign('departmentList',$departmentList);
		if(I('post.'))
		{
			if(I('post.department_id') and I('post.signdate') and I('post.classes'))
			{
				$uid = $_SESSION ['uid'];
				$department_id = I ('post.department_id');
				$signdate = I ('post.signdate');
				if(is_date($signdate) == false)
				{
					$this->error("这不是正确的时间格式，请重新输入!");
				}else{
					$signdate = str_replace ('-', '', $signdate);
				}
				$classes = I ('post.classes');
				$shift=new \Common\Model\ShiftModel();
				$res=$shift->signIn($uid, $department_id, $signdate, $classes);
				if($res['code']==0)
				{
					echo '<script>alert("签到成功!");top.location.reload(true);window.close();</script>';
					exit ();
				}else {
					//引入返回码
					$error_code_shift=json_decode(error_code_shift,true);
					$error_code_shift_zh=json_decode(error_code_shift_zh,true);
					if($res['code']==$error_code_shift['HISTORY_SHIFT_NOT_EXCHANGED'])
					{
						$last_shif=$res['shift'];
						$str=$error_code_shift_zh[$error_code_shift['HISTORY_SHIFT_NOT_EXCHANGED']].'\n上一工班信息：部门：'.$last_shif['parent_department_name'].'-'.$last_shif['department_name'].'，工班日期：'.$last_shif['sign_date'].'，班次：'.$last_shif['classes_zh'].'，当班理货长：'.$last_shif['master_name'];
						echo '<script>alert("'.$str.'");top.location.reload(true);window.close();</script>';
					    exit ();
					}else {
						echo '<script>alert("'.$res['msg'].'");top.location.reload(true);window.close();</script>';
						exit ();
					}
				}
			}else {
				$this->error('部门班组、签到日期、班次不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//接班
	public function succeed()
	{
		layout(false);
		$Shift=new \Common\Model\ShiftModel();
		$uid=$_SESSION['uid'];
		$User=new \Common\Model\UserModel();
		$res_sign=$User->is_sign($uid);
		if($res_sign['code']!=0)
		{
			$this->error($res_sign['msg']);
		}else {
			$shift_id=$res_sign['shift_id'];
			$department_id=$res_sign['department_id'];
			//根据部门代码获取该部门组上一工班ID
			$res_g=$Shift->where("department_id='$department_id' and shift_id!='$shift_id'")->field('shift_id')->order('shift_id desc')->find();
			if($res_g['shift_id']!='')
			{
				$msg=$Shift->getShiftRecord($res_g['shift_id']);
				$this->assign('msg',$msg);
			}
		}
		if(I('post.'))
		{
			$hand_work_id=I('post.hand_work_id');
			$res=$Shift->succeed($uid,$hand_work_id);
			if($res['code']!=0)
			{
				$this->error($res['msg']);
			}else {
				echo '<script>alert("接班成功!");top.location.reload(true);window.close();</script>';
				exit ();
			}
		}else {
			$this->display();
		}
	}
	
	//交班
	public function transfer()
	{
		layout(false);
		if(I('post.'))
		{
			if(I('post.note'))
			{
				$note=I('post.note');
				$uid=$_SESSION['uid'];
				//根据用户ID获取其所属工班ID
				$user=new \Common\Model\UserModel();
				$res_u=$user->is_sign($uid);
				if($res_u['code']!=0)
				{
					//未签到
					$this->error($res_u['msg']);
				}else {
					$shift_id=$res_u['shift_id'];
					$shift=new \Common\Model\ShiftModel();
					$res=$shift->transfer($uid, $shift_id, $note);
					if($res['code']==0)
					{
						echo '<script>alert("交班成功!");top.location.reload(true);window.close();</script>';
						exit ();
					}else {
						$this->error($res['msg']);
					}
				}
			}else {
				$this->error('交班留言不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//工班恢复
	public function resume()
	{
		if($_SESSION ['u_group_id'] != 13)
		{
			$this->error('只有部门长可以恢复工班！');
		}
		//展示所有已交班列表，取每个部门组的最新工班
		$sql1="select * from (select * from __PREFIX__shift where mark='1' order by shift_id desc) a group by department_id";
		$res1=M()->query($sql1);
		$count=count($res1);
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page= new \Think\Page($count,$per);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->rollPage=10; // 分页栏每页显示的页数
		$Page -> setConfig('header','共%TOTAL_ROW%条');
		$Page -> setConfig('first','首页');
		$Page -> setConfig('last','共%TOTAL_PAGE%页');
		$Page -> setConfig('prev','上一页');
		$Page -> setConfig('next','下一页');
		$Page -> setConfig('link','indexpagenumb');//pagenumb 会替换成页码
		$Page -> setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 (<font color="red">'.$per.'</font> 条/页 共 %TOTAL_ROW% 条)');
		$show= $Page->show();// 分页显示输出
		$this->assign('page',$show);
			
		//列表
		$begin=($p-1)*$per;
		$sql="select * from (select * from __PREFIX__shift where mark='1' order by shift_id desc) a group by department_id limit $begin,$per";
		$list=M()->query($sql);
		$this->assign('list',$list);
		$this->display();
	}
	
	//工班恢复操作
	public function resumePro()
	{
		layout(false);
		if($_SESSION ['u_group_id'] != 13)
		{
			$this->error('只有部门长可以恢复工班！');
		}
		$shift_id=I('get.shift_id');
		$this->assign('shift_id',$shift_id);
		if(I('post.'))
		{
			if(I('post.reason')=='')
			{
				$this->error('修改原因不能为空！');
			}
			$reason=I('post.reason');
			$shift_id=I('post.shift_id');
			$Shift=new \Common\Model\ShiftModel();
			$res=$Shift->shiftResume($shift_id, $_SESSION['uid'], $reason);
			if($res['code']!=0)
			{
				$this->error($res['msg']);
			}else {
				echo '<script>alert("恢复工班成功！");top.location.reload(true);window.close();</script>';
				exit ();
			}
		}else {
			$this->display();
		}
	}
	
	//替换当班理货长
	public function replaceMaster()
	{
		if($_SESSION ['u_group_id'] != 13)
		{
			$this->error('只有部门长可以恢复工班！');
		}
		//展示所有未交班列表，取每个部门组的最新工班
		$sql="select * from (select * from __PREFIX__shift where mark='0' order by shift_id desc) a group by department_id";
		$list=M()->query($sql);
		$this->assign('list',$list);
		$this->display();
	}
	
	//替换当班理货长操作
	public function replaceMasterPro()
	{
		layout(false);
		if($_SESSION ['u_group_id'] != 13)
		{
			$this->error('只有部门长可以恢复工班！');
		}
		$shift_id=I('get.shift_id');
		$this->assign('shift_id',$shift_id);
		if(I('post.'))
		{
			//替换当班理货长
			if(I('post.operator')!='')
			{
				$operator=I('post.operator');
			}else {
				$this->error('请选择一位理货长进行替换！');
			}
			if(I('post.reason')!='' and I('post.reason')!='请填写修改原因')
			{
				$reason=I('post.reason');
			}else {
				$this->error('修改原因不能为空！');
			}
			$shift_id=I('post.shift_id');
			//修改当前工班的当班理货长
			$data=array(
					'shift_master'=>$operator
			);
			$Shift=new \Common\Model\ShiftModel();
			$res_a=$Shift->where("shift_id='$shift_id'")->save($data);
			if($res_a!==false)
			{
				//保存修改记录
				$data_c=array(
						'shift_id'=>$shift_id,
						'operator_id'=>$_SESSION['uid'],
						'reason'=>$reason,
						'replace_time'=>date('Y-m-d H:i:s')
				);
				$replace_tallymaster_record=D('replace_tallymaster_record');
				$res_i=$replace_tallymaster_record->add($data_c);
				if($res_i!==false)
				{
					echo '<script>alert("替换当班理货长成功!");top.location.reload(true);window.close();</script>';
					exit();
				}else {
					$this->error('操作失败！');
				}
			}else {
				$this->error('操作失败！');
			}
		}else {
			//获取签入当前工班的理货长列表
			$User=new \Common\Model\UserModel();
			$list=$User->where("shift_id='$shift_id' and group_id in(12,13)")->select();
			$this->assign('list',$list);
			$this->display();
		}
	}
}
?>