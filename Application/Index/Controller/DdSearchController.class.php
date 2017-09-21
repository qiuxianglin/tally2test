<?php
/**
 * 查询统计
 * 门到门拆箱查询
 */
namespace Index\Controller;
use Think\Controller;

class DdSearchController extends Controller
{
	//委托计划查询
	public function index()
	{
		//集装箱船列表
		$Ship=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$Ship->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		$Plan=new \Common\Model\DdPlanModel();
		$where="1";
		if(I('get.orderid'))
		{
			$orderid=I('get.orderid');
			$orderid = str_replace("'", " ", $orderid);
			$where.=" and orderid='$orderid'";
		}
		if(I('get.vslname'))
		{
			$vslname=I('get.vslname');
			$vslname = str_replace("'", " ", $vslname);
			$where.=" and vslname='$vslname'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", " ", $voyage);
			$where.=" and voyage='$voyage'";
		}

		
		$count=$Plan->where($where)->count();
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		// 分页显示输出
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);
		$this->assign('page',$show);

		$list = $Plan->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		$this->display();
	}

	//委托计划详情查询
	public function edit($id)
	{
		//指令对应的预报计划详情
		$DdPlan=new \Common\Model\DdPlanModel();
		$msg=$DdPlan->getPlanMsg($id);
		$this->assign('msg',$msg);
		//  预报计划-配箱
		$planContainer = new \Common\Model\DdPlanContainerModel();
		$plancontainerlist = $planContainer->getContainerList($id);
		$this->assign('plancontainerlist',$plancontainerlist);
		// 预报计划-配货
		$planCargo=new \Common\Model\DdPlanCargoModel();
		$cargoList = $planCargo->getCargoList ( $id );
		$this->assign ( 'cargolist', $cargoList );
		$this->display();
	}

	//实时作业查询
	public function real_time()
	{
		// 集装箱船列表
		$Ship=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$Ship->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		// 作业场地
		$Location=new \Common\Model\LocationModel();
		$locationlist=$Location->getLocationList();
		$this->assign('locationlist',$locationlist);

		// 箱状态
		$ctn_status=json_decode(ctn_status,true);
		// 已完成
		$ctn_status_finished=$ctn_status['finished'];
		// $where="c.status in (1,2,4) and c.operator_id!=''";
		$where="c.status in (1,2) and o.operation_examine !=2";
		if(I('get.vslname'))
		{
			$vslname=I('get.vslname');
			$vslname = str_replace("'", "", $vslname);
			$where.=" and p.vslname='$vslname'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where.=" and p.voyage='$voyage'";
		}
		if(I('get.unpackagingplace'))
		{
			$unpackagingplace=I('get.unpackagingplace');
			$where.=" and p.unpackagingplace='$unpackagingplace'";
		}
		$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
		$count=$DdPlanContainer->alias('c')->join("left join tally_dd_operation o on o.ctn_id=c.id")->join("left join tally_dd_plan p on p.id=c.plan_id")->where($where)->count();
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		// 分页显示输出
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);
		$this->assign('page',$show);
			
		$begin_num=($p-1)*$per;
		$sql="select p.vslname,p.voyage,p.unpackagingplace,c.*,c.status cstatus from __PREFIX__dd_plan p,__PREFIX__dd_plan_container c,__PREFIX__dd_operation o where $where and o.ctn_id=c.id and  p.id=c.plan_id order by c.id desc limit $begin_num,$per";
		$list=M()->query($sql);
		//遍历结果，取出其它数据
		$num=count($list);
		$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
		for ($i=0;$i<$num;$i++)
		{
			$ctn_id=$list[$i]['id'];
			$DdOperation=new \Common\Model\DdOperationModel();
			$res_o=$DdOperation->where("ctn_id=$ctn_id")->find();
			if($res_o['id']!='')
			{
				$operation_id=$res_o['id'];
				$list[$i]['begin_time']=$res_o['begin_time'];
				//装太
				$list[$i]['operation_examine']=$res_o['operation_examine'];
// 				$list[$i]['begin_time']=$res_o['begin_time'];
				//关数
				$levelnum=$DdOperationLevel->sumLevelNum($operation_id);
				$list[$i]['levelnum']=$levelnum;
				//货物件数
				$cargonum=$DdOperationLevel->sumCargoNum($operation_id);
				$list[$i]['cargonum']=$cargonum;
				//残损件数
				$damage_num=$DdOperationLevel->sumDamageNum($operation_id);
				$list[$i]['damage_num']=$damage_num;
				//最新操作时间
				$res_new=$DdOperationLevel->where("operation_id=$operation_id")->field('createtime')->order("id desc")->find();
				if($res_new['createtime']!='')
				{
					$list[$i]['newtime']=$res_new['createtime'];
				}else {
					$list[$i]['newtime']=$res_o['begin_time'];
				}
				$time1 = strtotime ( $list[$i]['newtime'] );
				$time2 = time ();
				if (($time2 - $time1) / 300 > 1) 
				{
					$list [$i] ['red'] = 1;
				}
			}
		}
// 		var_dump($list);exit;
		$this->assign('list',$list);
		$this->display();
	}
	
	//实时作业详情
	public function realtimeDetail($ctn_id)
	{
		//根据箱ID获取箱详情
		$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
		$msg=$DdPlanContainer->getContainerMsg($ctn_id);
		$this->assign('msg',$msg);
		if($msg!==false)
		{
			$plan_id=$msg['plan_id'];
			$DdPlan=new \Common\Model\DdPlanModel();
			$planMsg=$DdPlan->getPlanMsg($plan_id);
			$this->assign('planMsg',$planMsg);
		}
		//根据箱ID获取作业详情
		$DdOperation=new \Common\Model\DdOperationModel();
		$operationMsg=$DdOperation->getOperationMsgByCtn($ctn_id);
		$this->assign('operationMsg',$operationMsg);
		if($operationMsg['id']!='')
		{
			$operation_id=$operationMsg['id'];
			//根据作业ID获取关列表
			$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
			$levellist=$DdOperationLevel->getLevelList($operation_id);
			$this->assign('levellist',$levellist);
		}
		$this->display();
	}
	
	//完成作业查询
	public function complete()
	{
		//集装箱船列表
		$Ship=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$Ship->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		//作业场地
		$Location=new \Common\Model\LocationModel();
		$locationlist=$Location->getLocationList();
		$this->assign('locationlist',$locationlist);
		//获取箱型列表
		$Container=new \Common\Model\ContainerModel();
		$containerlist=$Container->getContainerList();
		$this->assign('containerlist',$containerlist);
		
		$where="1";
		if(I('post.ship_name'))
		{
			$ship_name=I('post.ship_name');
			$ship_name = str_replace("'", "", $ship_name);
			$where.=" and p.ship_name='$ship_name'";
		}
		if(I('post.vargo'))
		{
			$vargo=I('post.vargo');
			$vargo = str_replace("'", "", $vargo);
			$where.=" and p.vargo='$vargo'";
		}
		if(I('post.location_id'))
		{
			$location_id=I('post.location_id');
			$where.=" and p.location_id=$location_id";
		}
		if(I('post.ctn_no'))
		{
			$ctn_no=I('post.ctn_no');
			$ctn_no = str_replace("'", "", $ctn_no);
			$where.=" and p.ctn_no='$ctn_no'";
		}
		if(I('post.flflag'))
		{
			$flflag=I('post.flflag');
			$where.=" and p.flflag='$flflag'";
		}
		if(I('post.ctn_type_code'))
		{
			$ctn_type_code=I('post.ctn_type_code');
			$ctn_type_code = str_replace("'", "", $ctn_type_code);
			$where.=" and p.ctn_type_code='$ctn_type_code'";
		}
		if (I('post.begin_time') && I ('post.end_time')) 
		{
			$begin_time = I ( 'begin_time' );
			$end_time = I ( 'end_time' );
			$end_time=strtotime("$end_time +1 day");
			$end_time=date('Y-m-d',$end_time);
			$where .= " and p.createtime between '$begin_time' and '$end_time' ";
		}
		if( I ('post.bl_no') )
		{
			$bl_no=I('post.bl_no');
			$bl_no = str_replace("'", "", $bl_no);
			$where.=" and p.content like '".'%"blno":"'. $bl_no .'%\'';
		}
		$DdProve=new \Common\Model\DdProveModel();
		$count=$DdProve->alias('p')->where($where)->count();
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

		$begin_num=($p-1)*$per;
// 		$sql="select p.*,pl.business from __PREFIX__dd_prove p,__PREFIX__dd_plan_container c,__PREFIX__dd_plan pl where $where and c.plan_id=pl.id order by p.id desc limit $begin_num,$per";
// 		$list=M()->query($sql);
		// $prove = new \Common\Model\DdProveModel();
		// $list = $prove->field("p.*,pl.business")->alias('p')
		// ->join("left join tally_dd_plan_container c on c.id=p.ctn_id")
		// ->join("left join tally_dd_plan pl on pl.id=c.plan_id")
		// ->where($where)->page($begin_num.','.$per)->order('p.id desc')->select();
		$sql3 = "select p.*,pl.business from tally_dd_prove p,tally_dd_plan_container c,tally_dd_plan pl where c.id=p.ctn_id and pl.id=c.plan_id and $where order by p.id desc limit $begin_num,$per";
		$list = M()->query($sql3);
		$this->assign('list',$list);
		$this->display();
	}
	
	//完成作业详情
	public function completeDetail($ctn_id)
	{
		//根据箱ID获取单证详情
		$DdProve=new \Common\Model\DdProveModel();
		$msg=$DdProve->getProveMsgByCtn($ctn_id);
		$this->assign('msg',$msg);
		//根据箱ID获取作业详情
		$DdOperation=new \Common\Model\DdOperationModel();
		$operationMsg=$DdOperation->getOperationMsgByCtn($ctn_id);
		$this->assign('operationMsg',$operationMsg);

		if($operationMsg['id']!='')
		{
			$operation_id=$operationMsg['id'];
			//根据作业ID获取关列表
			$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
			$levellist=$DdOperationLevel->getLevelList($operation_id);
			$this->assign('levellist',$levellist);
			//修改记录-待做
			$user = new \Common\Model\UserModel();
			$amendlist=$user->getamend('dd',$operation_id);
			$this->assign('amendlist',$amendlist);
		}
		$this->display();
	}
	
	//分箱单证
	public function documentByCtn()
	{
		//获取船舶列表
		$ship_type=json_decode(ship_type,true);
		$Ship=new \Common\Model\ShipModel();
		$shiplist=$Ship->getShipList($ship_type['container']);
		$this->assign('shiplist',$shiplist);
		//获取作业地点列表
		$Location=new \Common\Model\LocationModel();
		$locationlist=$Location->getLocationList();
		$this->assign('locationlist',$locationlist);
	
		$where="1";
		if(I('get.ship_name'))
		{
			$ship_name=I('get.ship_name');
			$ship_name = str_replace("'", "", $ship_name);
			$where.=" and ship_name='$ship_name'";
		}
		if(I('get.vargo'))
		{
			$vargo=I('get.vargo');
			$vargo = str_replace("'", "", $vargo);
			$where.=" and vargo='$vargo'";
		}
		if(I('get.location_id'))
		{
			$location_id=I('get.location_id');
			$where.=" and location_id='$location_id'";
		}
		if(I('get.ctn_no'))
		{
			$ctn_no=I('get.ctn_no');
			$ctn_no = str_replace("'", "", $ctn_no);
			$where.=" and ctn_no='$ctn_no'";
		}
		if(I('get.flflag'))
		{
			$flflag=I('get.flflag');
			$where.=" and flflag='$flflag'";
		}
		if (I ( 'begin_time' ) && I ( 'end_time' ))
		{
			$begin_time = I ( 'get.begin_time' );
			$end_time = I ( 'get.end_time' );
			$end_time=strtotime("$end_time +1 day");
			$end_time=date('Y-m-d',$end_time);
			$where .= " and createtime between '$begin_time' and '$end_time' ";
		}
		$DdProve=new \Common\Model\DdProveModel();
		//单证总数
		$count = $DdProve->where ( $where )->count();
		$per = 15;
		if ($_GET ['p']) {
			$p = $_GET ['p'];
		} else {
			$p = 1;
		}
		// 分页显示输出
		$Page=new \Common\Model\PageModel();
		$show = $Page->show ($count, $per); 
		$this->assign ( 'page', $show );
	
		//列表
		$list = $DdProve->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	//分箱单证详情
	public function documentByCtnMsg($id)
	{
		$DdProve=new \Common\Model\DdProveModel();
		$msg=$DdProve->getProveMsg($id);
		$this->assign('msg',$msg);
		//根据箱ID获取关操作记录
		if($msg['ctn_id']!='')
		{
			$ctn_id=$msg['ctn_id'];
			//获取作业ID
			$DdOperation=new \Common\Model\DdOperationModel();
			$res_o=$DdOperation->getOperationMsgByCtn($ctn_id);
			if($res_o['id']!='')
			{
				$begin_time=$res_o['begin_time'];
				$this->assign('begin_time',$begin_time);
				$operation_id=$res_o['id'];
				$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
				$levellist=$DdOperationLevel->getLevelList($operation_id);
				$this->assign('list',$levellist);
			}
		}
		$this->display();
	}
	
	//分票单证
	public function documentByTicket()
	{
		//获取船舶列表
		$ship_type=json_decode(ship_type,true);
		$Ship=new \Common\Model\ShipModel();
		$shiplist=$Ship->getShipList($ship_type['container']);
		$this->assign('shiplist',$shiplist);
		//获取作业地点列表
		$Location=new \Common\Model\LocationModel();
		$locationlist=$Location->getLocationList();
		$this->assign('locationlist',$locationlist);
		
		//获取列表
		$DdPlan=new \Common\Model\DdPlanModel();
		$where="is_valid='Y' and transit='CY' and category='2'";
		if(I('get.vslname'))
		{
			$vslname=I('get.vslname');
			$where.=" and vslname='$vslname'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where.=" and voyage='$voyage'";
		}
		if(I('get.unpackagingplace'))
		{
			$unpackagingplace=I('get.unpackagingplace');
			$where.=" and unpackagingplace='$unpackagingplace'";
		}
		if(I('get.blno'))
		{
			$blno=I('get.blno');
			$blno = str_replace("'", "", $blno);
			$where.=" and blno='$blno'";
		}
		$count = $DdPlan->where ( $where )->count();
		$per = 15;
		if ($_GET ['p']) {
			$p = $_GET ['p'];
		} else {
			$p = 1;
		}
		// 分页显示输出
		$Page=new \Common\Model\PageModel();
		$show = $Page->show ($count, $per); 
		$this->assign ( 'page', $show );

		//列表
		$list = $DdPlan->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		$this->display();
	}

	//分票单证详情
	public function documentByTicketMsg($blno,$plan_id)
	{
		//根据预报计划ID获取预报详情
		$DdPlan=new \Common\Model\DdPlanModel();
		$planMsg=$DdPlan->getPlanMsg($plan_id);
		$this->assign('planMsg',$planMsg);
		//根据提单号查找单证列表
		$DdProve=new \Common\Model\DdProveModel();
		$bl='"bl_no":"'.$blno.'",';
		$list=$DdProve->where("content like '%$bl%'")->select();
		$this->assign('list',$list);
		//计算总箱数、总件数、总残损
		$sql="select count(id) as total_ctn,sum(total_package) as total_num,sum(damaged_quantity) as total_damage from ctn_prove where content like '%$bl%'";
		$res=M()->query($sql);
		$this->assign('total_ctn',$res[0]['total_ctn']);
		$this->assign('total_num',$res[0]['total_num']);
		$this->assign('total_damage',$res[0]['total_damage']);
		//批注
		foreach ($list as $l)
		{
			$remark.= $l['ctn_no'] . ':' . $l ['remark'] . ';';
		}
		$this->assign('remark',$remark);
		//完成时间
		$num=count($list);
		$finished_time=$list[$num-1]['createtime'];
		$this->assign('finished_time',$finished_time);

		$this->display();
	}

	//预报列表
	public function ddplan()
	{
		//集装箱船列表
		$Ship=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$Ship->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		$Plan=new \Common\Model\DdPlanModel();
		$where="1";
		if(I('get.orderid'))
		{
			$orderid=I('get.orderid');
			$orderid = str_replace("'", " ", $orderid);
			$where.=" and orderid='$orderid'";
		}
		if(I('get.vslname'))
		{
			$vslname=I('get.vslname');
			$vslname = str_replace("'", " ", $vslname);
			$where.=" and vslname='$vslname'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", " ", $voyage);
			$where.=" and voyage='$voyage'";
		}
		if(I('get.blno'))
		{
			$blno=I('get.blno');
			$blno = str_replace("'", " ", $blno);
			$where.=" and blno='$blno'";
		}
		if(I('get.rcvflag')!=='')
		{
			$rcvflag=I('get.rcvflag');
			$where.=" and rcvflag='$rcvflag'";
		}
		$count=$Plan->where($where)->count();
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		// 分页显示输出
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);
		$this->assign('page',$show);

		$list = $Plan->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);

		$this->display();
	}

	//查看预报详情
	public function ddplanDetail($id)
	{
		$Plan=new \Common\Model\DdPlanModel();
		$msg=$Plan->getPlanMsg($id);
		$this->assign('msg',$msg);
		$this->display();
	}

	//完成作业查询批量打包照片
	public function pack_img()
	{
		layout(false);
		if(I('post.ctn_id'))
		{
			$ctn_id_list = $_POST['ctn_id'];
			//新建一个ZipArchive的对象
			$img_file = new \ZipArchive();
			//设置.zip下载后的文件名
			$zname1 = '拆箱图片打包'.'.zip';
			if ($img_file->open($zname1, \ZIPARCHIVE::CREATE)===TRUE)
			{
				foreach($ctn_id_list as $o)
				{
					//根据箱ID获取指令ID
					$plan_ctn = new \Common\Model\DdPlanContainerModel();
					$ctnno = $plan_ctn->field('ctnno')->where("id='$o'")->find();
					if($ctnno)
					{
						// 新建一个ZipArchive的对象
						$zip = new \ZipArchive();
						//设置.zip下载后的文件名
						$zname = $ctnno['ctnno']."_".$o.'.zip';
						$filelist[] = $zname;
						//根据箱ID获取作业详情(箱门照片、铅封照片、整箱货物照片、作业前箱残损照片、作业后箱残损照片)
						$DdOperation=new \Common\Model\DdOperationModel();
						$msg=$DdOperation->getOperationMsgByCtn($o);

						if($msg)
						{
							$operation_id=$msg['id'];
							//根据作业ID获取关列表
							$DdOperationLevel=new \Common\Model\DdOperationLevelModel();
							$levelList=$DdOperationLevel->getLevelList($operation_id);
						}
						

						//创建临时文件夹保存图片
						$filename = $ctnno['ctnno']."_".$o;
						if (!is_dir($filename)) mkdir($filename); // 如果不存在则创建

						if ($zip->open($zname, \ZIPARCHIVE::CREATE)===TRUE)
						{
							//向.zip压缩包里添加文件
							//关照片，关残损照片
							if($levelList != '')
							{
								foreach ($levelList as $vo)
								{
									//根据管ID获取关照片
									foreach($vo['cargo_level_img'] as $k)
									{
										$cargo_name = "./Public/upload/dd/cargo/".$k['level_img'];
										copy($cargo_name, './'.$filename.'/'.$k['level_img']);
										$zip->addFile('./'.$filename.'/'.$k['level_img']);
									}
									foreach ($vo['cargo_damage_img'] as $v)
									{
										$cdamage_name = "./Public/upload/dd/cdamage/".$v['img'];
										copy ( $cdamage_name , './'.$filename.'/'.$v['img'] );
										$zip->addFile('./'.$filename.'/'.$v['img']);
									}
								}
							}
							//箱门照片、铅封照片、整箱货物照片、作业前箱残损照片、作业后箱残损照片
							if($msg != '')
							{
								//箱门照片
								$door_pic = "./Public/upload/dd/door/".$msg['door_picture'];
								copy($door_pic , './'.$filename.'/'.$msg['door_picture']);
								$zip->addFile( './'.$filename.'/'.$msg['door_picture']);
								//空箱照片
								$empty_pic = "./Public/upload/dd/empty/".$msg['empty_picture'];
								copy($empty_pic , './'.$filename.'/'.$msg['empty_picture']);
								$zip->addFile( './'.$filename.'/'.$msg['empty_picture']);
								//铅封照片
								$seal_pic = "./Public/upload/dd/seal/".$msg ['seal_picture'];
								copy($seal_pic , './'.$filename.'/'.$msg ['seal_picture'] );
								$zip->addFile('./'.$filename.'/'.$msg ['seal_picture']);
								//整箱货物照片
								$cargo_picture = "./Public/upload/dd/cargo/".$msg ['cargo_picture'];
								copy($cargo_picture , './'.$filename.'/'.$msg ['cargo_picture'] );
								$zip->addFile('./'.$filename.'/'.$msg ['cargo_picture']);
								//作业前箱残损照片
								foreach ($msg['ctn_damage_img'] as $l)
								{
									$ctn_damage_img = "./Public/upload/dd/damage/".$l ['img'];
									copy($ctn_damage_img , './'.$filename.'/'.$l ['img'] );
									$zip->addFile('./'.$filename.'/'.$l ['img']);
								}
								//作业后箱残损照片
								foreach ($msg['ctn_damage_after_img'] as $l)
								{
									$ctn_damage_after_img = "./Public/upload/dd/damageAfter/".$l ['img'];
									copy($ctn_damage_after_img , './'.$filename.'/'.$l ['img'] );
									$zip->addFile('./'.$filename.'/'.$l ['img']);
								}
							}
							//文件添加完，关闭ZipArchive的对象
							$zip->close();
							//清空（擦除）缓冲区并关闭输出缓冲
							ob_end_clean();
							$img_file->addFile('./'.$zname);
						}
						delDirAndFile('./'.$filename);
					}
				}
				// 文件添加完，关闭ZipArchive的对象
				$img_file->close();
				//清空（擦除）缓冲区并关闭输出缓冲
				ob_end_clean();
				//下载建好的.zip压缩包
				header("Content-Type: application/force-download");//告诉浏览器强制下载
				header("Content-Transfer-Encoding: binary");//声明一个下载的文件
				header('Content-Type: application/zip');//设置文件内容类型为zip
				header('Content-Disposition: attachment; filename='.$zname1);//声明文件名
				header('Content-Length: '.filesize($zname1));//声明文件大小
				error_reporting(0);
				//将欲下载的zip文件写入到输出缓冲
				readfile($zname1);
				//将缓冲区的内容立即发送到浏览器，输出
				flush();
				foreach ($filelist as $k)
				{
					unlink('./'.$k);
				}
				unlink('./'.$zname1);
	
			}
		}else{
			$this->error("未选择箱");
		}
	}
}
?>