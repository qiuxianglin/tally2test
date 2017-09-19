<?php
/**
 * 拆箱系统-预报计划配箱
 */
namespace Index\Controller;
use Index\Common\BaseController;

class DdPlanContainerController extends BaseController
{
	//新增预报配箱
	public function add($plan_id)
	{
		layout(false);
		if(I('post.CTNNO') and I('post.CTNSIZE') and I('post.CTNTYPE') and I('post.SEALNO'))
		{
			if(I('post.CTNNO'))
			{
				$ctnno=strtoupper(I('post.CTNNO'));
				//检验箱号是否正确
				$ContainerCheck=new \Common\Model\ContainerCheckModel();
				$res_check=$ContainerCheck->checkNo($ctnno);
				if($res_check===false)
				{
					$this->error('箱号不符合国际标准！');
				}
			}else {
				$this->error('箱号不能为空！');
			}
			//判断联合国编号
			if(strlen(I('post.UNDGNO'))>9)
			{
				$this->error("联合国编号不能超过9位");
			}
			//判断危险品等级
			if(strlen( I('post.CLASSES') )>5)
			{
				$this->error("危险品等级不能超过5位！");
			}
			//判断件数为整数
			if(is_numeric(I('post.NUMBERSOFPACKAGES'))===false and is_numeric(I('post.WEIGHT'))===false and is_numeric( I('post.VOLUME') )===false )
			{
				$this->error("件数、重量、体积为整数");
			}
			//检查该箱在该指令是否已存在
			$res = $PlanContainer->field("ctnno")->where("plan_id='$plan_id' and ctnno='$ctnno'")->find();
			if($res['ctnno'] != '')
			{
				$this->error('该箱号已存在！');
			}
			$data2 = array (
					'ctnno' => $ctnno,
					'ctnsize' => I('post.CTNSIZE'),
					'ctntype' => I('post.CTNTYPE'),
					'sealno' => I('post.SEALNO'),
					'numbersofpackages' => I('post.NUMBERSOFPACKAGES'),
					'weight' => I('post.WEIGHT'),
					'volume' => I('post.VOLUME'),
					'flflag' => I('post.FLFLAG'),
					'classes' => I('post.CLASSES'),
					'undgno' => I('post.UNDGNO'),
					'plan_id' =>  I('post.plan_id')
			);
			$PlanContainer = new \Common\Model\DdPlanContainerModel();
			$res_pc=$PlanContainer->add($data2);
			if($res_pc)
			{
				echo '<script>alert("新增配箱成功!");top.location.reload(true);window.close();</script>';
			} else {
				echo '<script>alert("新增失败!");top.location.reload(true);window.close();</script>';
			}
		}else{
			//指令对应的预报计划详情
			$DdPlan=new \Common\Model\DdPlanModel();
			$msg=$DdPlan->getPlanMsg($plan_id);
			$this->assign('msg',$msg);
			$this->assign('plan_id',$plan_id);
			$this->display();
		}
	}

	//编辑预报配箱
	public function edit($id)
	{
		layout(false);
		$PlanContainer = new \Common\Model\DdPlanContainerModel();
			if(I('post.CTNNO') and I('post.CTNSIZE') and I('post.CTNTYPE') and I('post.SEALNO'))
			{
				
				$res = $PlanContainer->field('status')->where("id='$id'")->find();
				if($res['status'] != 0)
				{
					echo '<script>alert("配箱已经工作，禁止修改！");top.location.reload(true);window.close();</script>';
				}else{
					if(I('post.CTNNO'))
					{
						$ctnno=strtoupper(I('post.CTNNO'));
						//检验箱号是否正确
						$ContainerCheck=new \Common\Model\ContainerCheckModel();
						$res_check=$ContainerCheck->checkNo($ctnno);
						if($res_check===false)
						{
							$this->error('箱号不符合国际标准！');
						}
					}else {
						$this->error('箱号不能为空！');
					}
					//判断联合国编号
					if(strlen(I('post.UNDGNO'))>9)
					{
						$this->error("联合国编号不能超过9位");
					}
					//判断危险品等级
					if(strlen( I('post.CLASSES') )>5)
					{
						$this->error("危险品等级不能超过5位！");
					}
					//判断件数为整数
					if(is_numeric(I('post.NUMBERSOFPACKAGES'))===false and is_numeric(I('post.WEIGHT'))===false and is_numeric( I('post.VOLUME') )===false )
					{
						$this->error("件数、重量、体积为整数");
					}
				
					$data2 = array (
							'ctnno' => $ctnno,
							'ctnsize' => I('post.CTNSIZE'),
							'ctntype' => I('post.CTNTYPE'),
							'sealno' => I('post.SEALNO'),
							'numbersofpackages' => I('post.NUMBERSOFPACKAGES'),
							'weight' => I('post.WEIGHT'),
							'volume' => I('post.VOLUME'),
							'flflag' => I('post.FLFLAG'),
							'classes' => I('post.CLASSES'),
							'undgno' => I('post.UNDGNO'),
// 							'operator_id'   => $_SESSION['uid']
					);
					$res = $PlanContainer->where("id='".I('post.id')."'")->save($data2);
					if($res)
					{
						echo '<script>alert("修改配箱成功!");top.location.reload(true);window.close();</script>';
					} else {
						echo '<script>alert("修改失败!");top.location.reload(true);window.close();</script>';
					}
				}
			}else{
				$msg = $PlanContainer -> getContainerMsg($id);
				$this->assign('msg',$msg);
				$this->display();
			}
// 		}
	}

	//删除配箱
	public function del($id)
	{
		layout(false);
		//限制条件
		//配箱已经开始工作或者完成的情况下不允许删除
		$PlanContainer = new \Common\Model\DdPlanContainerModel();
		$res_i=$PlanContainer->where("id='$id' and status in (1,2)")->find();
		if($res_i['id']!='')
		{
			$this->error('配箱已经开始装箱作业，禁止删除!');
		}else {
			$res = $PlanContainer->where("id='$id'")->delete();
			if($res)
			{
				$this->success('删除配箱成功!');
			}else{
				$this->error('删除配箱失败！');
			}
		}
	}
}