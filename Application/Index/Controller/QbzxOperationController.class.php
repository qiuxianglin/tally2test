<?php
/**
 * 起泊装箱
 * 作业管理
 */
namespace Index\Controller;
use Index\Common\BaseController;

class QbzxOperationController extends BaseController
{
	public function index($ctn_id)
	{
		$this->assign('ctn_id',$ctn_id);
		//根据箱ID获取箱详情
		$container=new \Common\Model\QbzxInstructionCtnModel();
		$ctnMsg=$container->getContainerMsg($ctn_id);
		$this->assign('ctnMsg',$ctnMsg);
		//根据箱ID获取作业详情
		$operation=new \Common\Model\QbzxOperationModel();
		$msg=$operation->getOperationMsg($ctn_id);
		$this->assign('msg',$msg);

		//根据作业ID获取关列表
		$res_o = $operation->where("ctn_id='$ctn_id'")->field('id')->find();
		$operation_id = $res_o['id'];
		$level=new \Common\Model\QbzxOperationLevelModel();
		$levelList=$level->getLevelList($operation_id);
		$this->assign('levelList',$levelList);
		$this->display();
	}
	
	//替换理货员
	public function replaceTallyClerk($instruction_id,$ctn_id)
	{
		layout(false);
		$uid=$_SESSION['uid'];
		//判断用户是否有权限进行派工
		$dispatch=new \Common\Model\DispatchModel();
		$res=$dispatch->isPermissionsForDispatching($uid, $instruction_id);
		if($res['code']!=0)
		{
			$this->error($res['msg']);
		}else {
			if(I('post.'))
			{			
				//替换理货员
				if(I('post.operator')!='')
				{
					$operator=I('post.operator');
				}else {
					$this->error('请选择一位理货员进行替换！');
				}
				if(I('post.reason')!='' and I('post.reason')!='请填写修改原因')
				{
					$reason=I('post.reason');
				}else {
					$this->error('修改原因不能为空！');
				}
				$ctn_id=I('post.ctn_id');
				$uid = I('post.operator');
				$operation_id = I('post.operation_id');
				$instruction_id = I('post.instruction_id');
				//指令ID、shift_id获取派工ID
				$where = "instruction_id='$instruction_id' and shift_id='".$res['shift_id']."'";
				$dispatchid = $dispatch->where($where)->field('id')->find();
				//1:替换的理货员是否派工
				//获取原先派工的人员id列表
				$dispatchdetail=new \Common\Model\DispatchDetailModel();
				$uidlist = $dispatchdetail->getclerkidlist($dispatchid['id']);
				if(!in_array($uid,$uidlist)){
					$data = array(
							'dispatch_id' => $dispatchid['id'],
							'clerk_id'    => $uid,
							'dispatch_time'=> date('Y-m-d H:i:s')
					);
					$res = $dispatchdetail->add($data);
					if($res == false){
						$this->error('添加派工详情失败！');
					}
				}
				//修改指令配箱的理货员
				$data_a = array(
						'operator_id' => $uid
				);
				//修改指令配箱的操作人
				$QbzxInstructionCtn = new \Common\Model\QbzxInstructionCtnModel();
				$res_a = $QbzxInstructionCtn->where("id = '$ctn_id'")->save($data_a);
				if($res_a!==false){
					//修改作业表的操作人
					$operation = new \Common\Model\QbzxOperationModel();
					$res_b=$operation->where("id=$operation_id")->save($data_a);
					if($res_b!==false)
					{
						//保存修改记录
						$data_c=array(
								'ctn_id'=>$ctn_id,
								'operator_id'=>$uid,
								'reason'=>$reason,
								'replace_time'=>date('Y-m-d H:i:s'),
								'business'=>'qbzx'
						);
						$res_i=D('replace_clerk_record')->add($data_c);
						if($res_i!==false)
						{
							echo '<script>alert("替换理货员成功!");top.location.reload(true);window.close();</script>';
							exit();
						}else {
							$this->error('保存修改记录操作失败！');
						}
					}else {
						$this->error('修改作业表操作人失败！');
					}
				}else{
					$this->error('修改指令配箱操作人失败');
				}
			}else {
				$workerlist=$res['workerlist'];
				//得到作业ID
				$operation = new \Common\Model\QbzxOperationModel();
				$res_o = $operation->where("ctn_id='$ctn_id'")->field('id')->find();
				$operation_id=$res_o['id'];
				$this->assign('workerlist',$workerlist);
				$this->assign('instruction_id',$instruction_id);
				$this->assign('operation_id',$operation_id);
				$this->assign('ctn_id',$ctn_id);
				$this->display();
			}
		}
	}
	
	//修改铅封号
	public function editSealno($operation_id)
	{
		layout ( false );
		$this->assign ( 'operation_id', $operation_id );
		$uid = $_SESSION ['uid'];
		$where = array (
				'uid' => $uid
		);
		$userinfo = new \Common\Model\UserModel();
		$user= $userinfo->where ( $where )->find ();
		//签到工班ID
		$shift_id=$user ['shift_id'];
		//判断用户是否为当班理货长
		$common=new \Common\Model\ShiftModel();
		$res_m=$common->isWorkMaster($uid, $shift_id);
		if($res_m['code']!=0)
		{
			$this->error('非当班理货长不得操作!');
		}
		$operation = new \Common\Model\QbzxOperationModel();
		$msg = $operation->where("id='$operation_id'")->find();
		$this->assign ( 'sealno', $msg['sealno'] );
		if(I('post.'))
		{
			if(I('post.sealno') and I('post.remark'))
			{
				$sealno=I('post.sealno');
				$remark=I('post.remark');
				//工作中，不允许修改
				$container = new \Common\Model\QbzxInstructionCtnModel();
				$ctn_id = $msg['ctn_id'];
				$res_c = $container->where ( "id='$ctn_id'")->find ();
				if ($res_c ['status'] != 2)
				{
					$this->error('工作中，不允许修改!');
				}
				//作业ID对应的箱ID
				$ctn_id=$res_c ['id'];

				// 作业状态为已交班，非部门长不允许修改
				$res_o = $operation->where("id=$operation_id")->find ();
				if ($res_o ['per_no'] == 3)
				{
					$u = $userinfo->where("uid=$uid")->find();
					if ($u ['position'] == '理货长' || $u ['position'] == '理货员')
					{
						$this->error('非部门长不允许修改!');
					}
				}
				$seal = $res_o ['sealno'];
				if ($sealno != $seal) 
				{
					//修改铅封号
					$data_s=array(
							'sealno'=>$sealno
					);
					$res_s = $operation->where("id=$operation_id")->save($data_s);
					if($res_s!==false)
					{
						//保存修改记录
						$data = array (
								'business' => 'qbzx',
								'category' => 'operation',
								'operation_id' => $operation_id,
								'info_id' => $operation_id,
								'field_name' => 'sealno',
								'field_old_value' => $seal,
								'field_new_value' => $sealno,
								'uid' => $uid,
								'date' => date ( 'Y-m-d H:i:s'),
								'remark' => $remark
						);
						$amend = new \Common\Model\AmendModel();
						if(!$amend->create($data))
						{
							//对data数据进行验证
							$this->error($amend->getError());
						}else{
							//验证通过 可以对数据进行验证
							$amend->add($data);
						}
					}
				}
				// 因对工作中的箱子不允许修改，所以修改的都是已铅封的箱子，所以需要删除原单证，重新生成单证
				// 根据箱id找出单证
				$prove = new \Common\Model\QbzxProveModel();
				$ctn_certify = $prove->where ( "ctn_id=$ctn_id" )->find ();
				// 将原单证备注保存
				$ccremark = $ctn_certify ['remark'];
				// 删除原单证
				$res_d = $prove->where ( "ctn_id=$ctn_id" )->delete ();
				if($res_d!==false)
				{
					// 重新生成单证
					$prove->generateDocumentByQbzx($ctn_id,$ccremark);
					echo '<script>alert("修改成功");top.location.reload(true);window.close();</script>';
					exit ();
				}else {
					$this->error('修改成功，重新生成单证失败！');
				}
			}else {
				$this->error('铅封号、修改原因不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//修改关
	public function editlevel($operation_id,$level_id)
	{
		layout ( false );
		$amend = new \Common\Model\AmendModel();
		$this->assign ( 'operation_id', $operation_id );
		$this->assign ( 'level_id', $level_id );
		$uid = $_SESSION ['uid'];
		$where = array (
				'uid' => $uid
		);
		$user = new \Common\Model\UserModel();
		$user=$user->where ( $where )->find ();
		$shift_id=$user ['shift_id'];
		// 如果是部门长，可以直接修改，否则需要判断是否为当班理货长
		$common=new \Common\Model\ShiftModel();
		if($_SESSION ['u_group_id']!='13')
		{
			//判断用户是否为当班理货长
			$res_m=$common->isWorkMaster($uid, $shift_id);
			if($res_m['code']!=0)
			{
				$this->error('非当班理货长不得操作!');
			}
		}
		//根据关ID获取关信息
		$level=new \Common\Model\QbzxOperationLevelModel();
		$msg=$level->getLevelMsg($level_id);
		$this->assign('msg',$msg);
		if (I('post.'))
		{
			if(I('post.remark'))
			{
				$remark=I('post.remark');
			}else {
				$this->error('修改原因不能为空！');
			}
			$cargo_number = I('post.cargo_number');
			$damage_num = I('post.damage_num');
			$billno = strtoupper(I('post.billno'));
			//工作中，不允许修改
			$operation = new \Common\Model\QbzxOperationModel();
			$res_c = $operation->where ( "id='$operation_id'")->find ();

			//作业ID对应的箱ID
			$ctn_id=$res_c ['ctn_id'];

			// 作业状态为已交班，非部门长不允许修改
			if ($res_c ['per_no'] == '3') 
			{
				$user = new \Common\Model\UserModel();
				$u = $user->where("uid='$uid'")->find();
				if ($u ['group_id'] != '13')
				{
					$this->error('非部门长不允许修改!');
				}
			}
			$level = new \Common\Model\QbzxOperationLevelModel();
			$g = $level->where ( "id='$level_id'" )->find ();

			if ($g ['cargo_number'] != $cargo_number) 
			{
				//修改关的货物件数
				$data_cn=array(
						'cargo_number'=>$cargo_number
				);
				$res_cn = $level->where("id=$level_id")->save($data_cn);
				if($res_cn!==false)
				{
					//保存修改记录
					$data = array (
							'business' => 'qbzx',
							'category' => 'operation_level',
							'operation_id' => $operation_id,
							'info_id' => $level_id,
							'field_name' => 'cargo_number',
							'field_old_value' => $g ['cargo_number'],
							'field_new_value' => $cargo_number,
							'uid' => $uid,
							'date' => date ( 'Y-m-d H:i:s'),
							'remark' => $remark
					);
					if(!$amend->create($data))
					{
						//对data数据进行验证
						$this->error($amend->getError());
					}else{
						//验证通过 可以对数据进行操作
						$amend->add($data);
					}
				}
			}
			
			if ($g ['damage_num'] != $damage_num) 
			{
				//修改关的残损件数
				$data_dn=array(
						'damage_num'=>$damage_num
				);
				if(!$level->create($data_dn))
				{
					//对数据进行验证
					$this->error($level->getError());
				}else{
					//验证通过 可以对数据进行操作
				    $res_dn = $level->where("id=$level_id")->save($data_dn);	
				}
				if($res_dn!==false)
				{
					//保存修改记录
					$data = array (
							'business' => 'qbzx',
							'category' => 'operation_level',
							'operation_id' => $operation_id,
							'info_id' => $level_id,
							'field_name' => 'damage_num',
							'field_old_value' => $g ['damage_num'],
							'field_new_value' => $damage_num,
							'uid' => $uid,
							'date' => date ( 'Y-m-d H:i:s', time () ),
							'remark' => $remark
					);
					if(!$amend->create($data))
					{
						//对data数据进行验证
						$this->error($amend->getError());
					}else{
						//验证通过 可以对数据进行操作
						$amend->add($data);
					}
				}	
			}
			
			if ($g ['billno'] != $billno) 
			{
				//修改关的提单号
				$data_o=array(
						'billno'=>$billno
				);
				$res_o =$level->where("id=$level_id")->save($data_o);
				if($res_o!==false)
				{
					//保存修改记录
					$data = array (
							'business' => 'qbzx',
							'category' => 'opration_level',
							'operation_id' => $operation_id,
							'info_id' => $level_id,
							'field_name' => 'billno',
							'field_old_value' => $g ['billno'],
							'field_new_value' => $billno,
							'uid' => $uid,
							'date' => date ( 'Y-m-d H:i:s', time () ),
							'remark' => $remark
					);
					if(!$amend->create($data))
					{
						//对data数据进行验证
						$this->error($amend->getError());
					}else{
						//验证通过 可以对数据进行操作
						$amend->add($data);
					}
				}
			}
			$container = new \Common\Model\QbzxInstructionCtnModel();
			$ctnMsg = $container->where("id='$ctn_id'")->field('status')->find();
			$status = $ctnMsg['status'];
			if($res_c['operation_examine'] != '2')
			{
				echo '<script>alert("修改成功");top.location.reload(true);window.close();</script>';
				exit ();
			}else{
				// 因对工作中的箱子不允许修改，所以修改的都是已铅封的箱子，所以需要删除原单证，重新生成单证
				// 根据箱id找出单证
				$prove = new \Common\Model\QbzxProveModel();
				$ctn_certify = $prove->where ( "ctn_id=$ctn_id" )->find ();
				// 将原单证备注保存
				$ccremark = $ctn_certify ['remark'];
				// 删除原单证
				$res_d =$prove->where ( "ctn_id=$ctn_id" )->delete ();
				if($res_d!==false)
				{
					// 重新生成单证
					$document=new \Common\Model\QbzxProveModel();
					$document->generateDocumentByQbzx($ctn_id,$ccremark);
					echo '<script>alert("修改成功");top.location.reload(true);window.close();</script>';
					exit ();
				}else {
					$this->error('修改成功，重新生成单证失败！');
				}
			}
		}else {
			$this->display ();
		}
	}

	//打包下载所有箱作业照片
	public function pack_img()
	{
		layout(false);
		$uid=$_SESSION['uid'];
		//检验用户是否是当班理货长
		$user = new \Common\Model\UserModel();
		$instruction_id = I('get.instruction_id');
		$res_is = $user->isPermissionsforexamine($uid, $instruction_id,$business='qbzx');
		if($res_is['code']!=0)
		{
			$this->error($res_is['msg']);
		}
		$operation_id = I('get.operation_id');
		$ctn_id = I('get.ctn_id');
		//根据箱ID获取指令ID
		$instructctn = new \Common\Model\QbzxInstructionCtnModel();
		$instruction_id = $instructctn->field('instruction_id,ctnno')->where(array('id'=>$ctn_id))->find();
		if($instruction_id)
		{
			//新建一个ZipArchive的对象
			$zip = new \ZipArchive();
			//设置.zip下载后的文件名
			$zname = $instruction_id['ctnno'].'.zip';
			//创建临时文件夹保存图片
			$filename = $instruction_id['ctnno'].'_'.$ctn_id;
			if (!is_dir($filename)) mkdir($filename); // 如果不存在则创建
			
			//获取铅封照片，办关门照片，全关门照片，空想照片(获取作业详情)
			$operation = new \Common\Model\QbzxOperationModel();
			$msg=$operation->getOperationMsg($ctn_id);
			//获取关详情
			$level=new \Common\Model\QbzxOperationLevelModel();
			$levelList=$level->getLevelList($operation_id);
			//开始操作.zip压缩包
			if ($zip->open($zname, \ZIPARCHIVE::CREATE)===TRUE) {
				//向.zip压缩包里添加文件
				//关照片，关残损照片
				if($levelList != '')
				{
					foreach ($levelList as $vo)
					{
						
						//根据管ID获取关照片
						$cargo_img = new \Common\Model\QbzxLevelCargoImgModel();
						$level_id = $vo['id'];
						$level_id_list = $cargo_img->where("level_id='$level_id'")->select();
						foreach($level_id_list as $k)
						{
							$cargo_name = "./Public/upload/qbzx/cargo/".$k['cargo_picture'];
							copy($cargo_name, './'.$filename.'/'.$k['cargo_picture']);
							$zip->addFile('./'.$filename.'/'.$k['cargo_picture']);
						}
						//获取关残损照片
						$damageimg = new \Common\Model\QbzxLevelDamageImgModel();
						$level_damageimg_list = $damageimg->where("level_id='$level_id'")->select();
						foreach ($level_damageimg_list as $v)
						{
							$cdamage_name = "./Public/upload/qbzx/cdamage/".$v['damage_picture'];
							copy ( $cdamage_name , './'.$filename.'/'.$v['damage_picture'] );
							$zip->addFile('./'.$filename.'/'.$v['damage_picture']);
						}
					}
				}
				//铅封照片，办关门照片，全关门照片，空想照片
				if($msg != '')
				{
					//空箱照片
					foreach ($msg['empty_picture'] as $vo)
					{
						$empty_pic = "./Public/upload/qbzx/empty/".$vo['empty_picture'];
						copy($empty_pic , './'.$filename.'/'.$vo['empty_picture']);
						$zip->addFile( './'.$filename.'/'.$vo['empty_picture']);
					}
					
					//铅封照片
					$seal_pic = "./Public/upload/qbzx/seal/".$msg ['seal_picture'];
					copy($seal_pic , './'.$filename.'/'.$msg ['seal_picture'] );
					$zip->addFile('./'.$filename.'/'.$msg ['seal_picture']);
					//半关门
					$halfclose_door_picture = "./Public/upload/qbzx/halfclosedoor/".$msg ['halfclose_door_picture'];
					copy($halfclose_door_picture , './'.$filename.'/'.$msg ['halfclose_door_picture'] );
					$zip->addFile('./'.$filename.'/'.$msg ['halfclose_door_picture']);
					//全关门
					$close_door_picture = "./Public/upload/qbzx/closedoor/".$msg ['close_door_picture'];
					copy($close_door_picture , './'.$filename.'/'.$msg ['close_door_picture'] );
					$zip->addFile('./'.$filename.'/'.$msg ['close_door_picture']);
				}
				//文件添加完，关闭ZipArchive的对象
				$zip->close();
				//清空（擦除）缓冲区并关闭输出缓冲
				ob_end_clean();
				//下载建好的.zip压缩包
				header("Content-Type: application/force-download");//告诉浏览器强制下载
				header("Content-Transfer-Encoding: binary");//声明一个下载的文件
				header('Content-Type: application/zip');//设置文件内容类型为zip
				header('Content-Disposition: attachment; filename='.$zname);//声明文件名
				header('Content-Length: '.filesize($zname));//声明文件大小
				error_reporting(0);
				//将欲下载的zip文件写入到输出缓冲
				readfile($zname);
				//将缓冲区的内容立即发送到浏览器，输出
				flush();
			}
			unlink('./'.$zname);
			delDirAndFile('./'.$filename);
		}else{
			$this->error('没有找到该指令');
		}
	}

	//箱子的作业内容审核通过
	public function operation_examine()
	{
		layout(false);
		$instruction_ctn = new \Common\Model\QbzxInstructionCtnModel();
		$operation = new \Common\Model\QbzxOperationModel();
		$uid = $_SESSION['uid'];
		//检验用户是否是当班理货长
		$user = new \Common\Model\UserModel();
		$instruction_id = I('get.instruction_id');
		$res_is = $user->isPermissionsforexamine($uid, $instruction_id,$business='qbzx');
		if($res_is['code']!=0)
		{
			$this->error($res_is['msg']);
		}
		//判断箱子是否铅封
		$ctn_id = I('get.ctn_id');
		$res = $instruction_ctn->field('status,instruction_id')->where("id='$ctn_id'")->find();
			
		if($res['status'] != 2)
		{
			$this->error('该箱子尚未铅封！');
		}else{
			//铅封，申城单证，改变审核状态
			$operation_id = I('get.operation_id');
			$remark = '';
			$prove = new \Common\Model\QbzxProveModel ();
			$res_p = $prove->generateDocumentByQbzx ( $ctn_id,$remark );
			if ($res_p ['code'] == '0') {
				// 判断指令下的配箱是否都已经完成，都完成将指令状态改为完成
				$instruction_id = $res['instruction_id'];
				$no_container_num = $instruction_ctn->where ( "instruction_id='$instruction_id' and status not in(2,-1)" )->count ();
				if ($no_container_num == 0) {
					// 修改指令状态为已完成
					$data_i = array (
							'status' => '2'
					);
					$instruction = new \Common\Model\QbzxInstructionModel ();
					$instruction->where ( "id='$instruction_id'" )->save ( $data_i );
				}
				// 修改箱作业为相应的审核通过状态
				$data_t = array (
						'operation_examine' => I('get.operation_examine')
				);
				$operation->where("id='$operation_id'")->save($data_t);
				$this->success('审核成功');
			} else {
// 				$this->error('生成单证失败，审核操作失败');
				$this->error($res_p ['msg']);
			}
		}
	}
}
?>

