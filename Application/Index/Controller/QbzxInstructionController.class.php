<?php
/**
 * 起泊装箱-指令管理
 */
namespace Index\Controller;
use Index\Common\BaseController;

class QbzxInstructionController extends BaseController
{
	//预报计划列表
	public function index()
	{
		$plan=new \Common\Model\QbzxPlanModel();
		$where="1";
		$count=$plan->where($where)->count();
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
		$sql="select p.*,s.ship_name,l.location_name,c.customer_shortname as customer,c.customer_name from __PREFIX__qbzx_plan p,__PREFIX__ship s,__PREFIX__location l,__PREFIX__customer c where p.id!='' and p.ship_id=s.id and p.location_id=l.id and p.entrust_company=c.id order by p.id desc limit $begin,$per";
		$list=M()->query($sql);
		$num=count($list);
		$instruction=new \Common\Model\QbzxInstructionModel();
		for($i = 0; $i < $num; $i ++) 
		{
			//已配箱数
			$sql = "select count(c.id) as ctn_num from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_instruction_ctn c where i.plan_id='" . $list [$i] ['id'] . "' and c.instruction_id=i.id and c.status!='-1'";
			$res = M ()->query ( $sql );
			$list [$i] ['has_container_num'] = $res [0] ['ctn_num'];
			//预报下最新的指令信息
			$ins = $instruction->where("plan_id=" . $list [$i] ['id'])->order ( 'id desc' )->find ();
			if ($ins ['location_id'] == null) 
			{
				$list [$i] ['location_name'] = '';
			} else {
				$location = new \Common\Model\LocationModel();
				$res_l = $location->where ( "id=" . $ins ['location_id'] )->find ();
				$list [$i] ['location_name'] = $res_l ['location_name'];
			}
			//指令日期
			$list [$i] ['instruction_date'] = $ins ['ordertime'];
			//装箱方式
			$list [$i] ['operation_method'] = $ins ['loadingtype'];
			//指令条数
			$list [$i] ['ins_count']=$instruction->where("plan_id=" . $list [$i] ['id'] )->count();
		}
		$this->assign('list',$list);
		$this->display();
	}
	
	//新增指令
	public function add($plan_id)
	{
		layout(false);
		$this->assign('plan_id',$plan_id);
		//根据计划ID获取预报计划信息
		$plan=new \Common\Model\QbzxPlanModel();
		$planMsg=$plan->getPlanMsg($plan_id);
		$this->assign('planMsg',$planMsg);
		//作业场地列表
		$location=new \Common\Model\LocationModel();
		$locationlist=$location->getLocationList();
		$this->assign('locationlist',$locationlist);
		//用户信息
		$user=new \Common\Model\UserModel();
		$uid=$_SESSION['uid'];
		$userMsg=$user->getUserDetail($uid);
		if($userMsg['shift']['department_id'] == '')
		{
			$this->error('尚未签入正常工班');
		}
		$this->assign('userMsg',$userMsg);
		if(I('post.'))
		{
			if(I('post.location_name'))
			{
				$location_name=I('post.location_name');
			}else {
				$this->error('装箱场地不能为空！');
			}
			//检查是否含有特殊字符
			$data_l = filterString($location_name);
			if($data_l == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			
			//检验理货地址名称是否正确
			$res_l=$location->where("location_name='$location_name'")->field('id')->find();
			if($res_l['id']=='')
			{
				$this->error('理货地点不存在！');
			}else {
				$location_id = $res_l['id'];
			}
			$data=array(
					'location_id'=>$location_id,
					'loadingtype'=>I('post.loadingtype'),
					'plan_id'=>$plan_id,
					'department_id'=>$userMsg['shift']['department_id'],
					'ordertime'=>date('Y-m-d'),
					'last_operator'=>$_SESSION['uid'],
			);
			$instruction=new \Common\Model\QbzxInstructionModel();
			$res=$instruction->add($data);
			if ($res !== false) 
			{
				//如果该工班是部门组历史第一个工班，并且没有当班理货长，记录当前操作理货长为当班理货长
				$shift_id=$userMsg['shift_id'];
				$shiftdetail = new \Common\Model\ShiftDetailModel();
				$res_gd=$shiftdetail->where("exchanged_id='$shift_id' or carryon_id='$shift_id'")->field('id')->find();
				if($res_gd['id']=='' and $userMsg['shift']['master']['staffno']=='')
				{
					//不存在交接班记录，为首次开班作业
					//修改该工班的当班理货长为当前生成指令的理货长
					$data_m=array(
							'shift_master'=>$uid
					);
					$shift = new \Common\Model\ShiftModel();
					$shift->where("shift_id='$shift_id'")->save($data_m);
				}
				echo '<script>alert("新增作业指令成功!");top.location.reload(true);window.close();</script>';
			} else {
				echo '<script>alert("操作失败！");top.location.reload(true);window.close();</script>';
			}
		}else {
			$this->display();
		}
	}
	
	//编辑指令
	public function edit($id)
	{
		layout(false);
		$instruction=new \Common\Model\QbzxInstructionModel();
		if(I('post.'))
		{
			//检验理货地址代码是否正确
			$location_name = I('post.location_name');
			//检查是否含有特殊字符
			$data_l = filterString($location_name);
			if($data_l == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			$location=new \Common\Model\LocationModel();
			$res_l=$location->where("location_name='$location_name'")->field('id')->find();
			if($res_l['id']=='')
			{
				$this->error('作业场地不存在！');
			}else {
				$location_id = $res_l['id'];
			}
			$loadingtype = I ( 'post.loadingtype' );
			$data=array(
					'location_id'=>$location_id,
					'loadingtype'=>$loadingtype,
					'last_operator'=>$_SESSION['uid'],
			);
			if(!$instruction->create($data))
			{
				//对data数据进行验证
				$this->error($instruction->getError());
			}else{
				//验证通过 可以对数据进行处理
				$res=$instruction->where("id=$id")->save($data);
				if($res!==false)
				{
					$this->success('修改指令成功！');
				}else {
					$this->error('操作失败！');
				}
			}
		}
	}
	
	//删除指令
	public function del()
	{
		layout(false);
		$instruction = new \Common\Model\QbzxInstructionModel();
		$dispatch = new \Common\Model\DispatchModel();
		$instruction_id=I('get.instruction_id');
		
		// 检查指令是否已派工，已派工的指令不准删除
		$res_i = $dispatch->where ("instruction_id=$instruction_id and business='qbzx'")->find();
		if ($res_i) 
		{
			$this->error('该指令已派工，禁止删除');
		}else {
			// 根据指令删除起驳装箱作业指令
			$res = $instruction->where ( "id=$instruction_id" )->delete ();
            //删除指令下配箱
			$container = new \Common\Model\QbzxInstructionCtnModel();
			$container->where("instruction_id='$instruction_id'")->delete();
			if ($res!==false)
			{
				$this->success('删除指令成功！');
			} else {
				$this->error('删除指令失败！');
			}
		}
	}
	
	//查看预报计划下的指令列表
	public function listins($plan_id)
	{
		$this->assign('plan_id',$plan_id);
		//根据计划ID获取预报计划信息
		$plan=new \Common\Model\QbzxPlanModel();
		$planMsg=$plan->getPlanMsg($plan_id);
		$this->assign('planMsg',$planMsg);
		//已配箱数
		$sql = "select count(c.id) as ctn_num from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_instruction_ctn c where i.plan_id='$plan_id' and c.instruction_id=i.id and c.status!='-1'";
		$res = M ()->query ( $sql );
		$has_container_num = $res [0] ['ctn_num'];
		$this->assign('has_container_num',$has_container_num);
		//根据预报计划ID获取指令列表
		$instruction=new \Common\Model\QbzxInstructionModel();
		$list=$instruction->getInstructionList($plan_id);
		$this->assign('list',$list);
		$this->display();
	}
}
?>