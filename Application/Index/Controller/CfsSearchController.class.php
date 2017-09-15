<?php
/**
 * 查询统计
 * CFS装箱查询
 */
namespace Index\Controller;
use Think\Controller;

class CfsSearchController extends Controller
{
	// 实时作业查询
	public function real_time()
	{
		// 集装箱船列表
		$shipinfo = new \Common\Model\ShipModel ();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$shipinfo->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		//作业场地
		$location=new \Common\Model\LocationModel();
		$locationlist=$location->getLocationList();
		$this->assign('locationlist',$locationlist);
		// 港口信息
		$port = new \Common\Model\PortModel ();
		$portlist = $port->getPortList ();
		$this->assign ( 'portlist', $portlist );

		$where="c.status in (1,2) and c.operator_id!='' and o.operation_examine in (1,3)";
		if(I('get.ship_name'))
		{
			$ship_name=I('get.ship_name');
			// 根据船名获取船ID
			$res_s = $shipinfo->where ( "ship_name='$ship_name'" )->field ( 'id' )->find ();
			$ship_id = $res_s ['id'];
			$where.=" and i.ship_id='$ship_id'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where.=" and i.voyage='$voyage'";
		}
		if (I ( 'get.port' )) {
			// 检验港口是否正确
			$port_name = I ( 'get.port' );
			$port_name = str_replace ( "'", " ", $port_name );
			// 根据港口名称获取港口ID
			$port_id = $port->where ( "name='$port_name'" )->field ( 'id' )->find ();
			$port_id = $port_id ['id'];
			$where .= " and ic.port_id='$port_id'";
		}
		if(I('get.location_name'))
		{
			$location_name=I('get.location_name');
			// 根据理货地点获取理货地点ID
			$res_l = $location->where ( "location_name='$location_name'" )->field ( 'id' )->find ();
			$location_id = $res_l ['id'];
			$where.=" and i.location_id='$location_id'";
		}
		if(I('get.billno')){
			$billno = I ( 'get.billno' );
			$billno = str_replace ( "'", " ", $billno );
			$where .= " and ic.blno like '%$billno%'";
		}
		if(I('get.cstatus'))
		{
			$cstatus = I('get.cstatus');
			$where .= " and c.status='$cstatus'";
		}
		//$sql = "select c.id from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_ctn c,__PREFIX__cfs_instruction_cargo ic  where i.id=c.instruction_id and ic.instruction_id=i.id and $where";
		$sql = "select c.* from 
			tally_cfs_instruction_ctn c
		 LEFT JOIN tally_cfs_instruction i on i.id=c.instruction_id
		 LEFT JOIN tally_location l on l.id=i.location_id
		 LEFT JOIN tally_user u on u.uid =c.operator_id
		 LEFT JOIN tally_cfs_instruction_cargo ic on ic.instruction_id = i.id
		 LEFT JOIN tally_ship s on i.ship_id=s.id
		 LEFT JOIN tally_port po on ic.port_id=po.id
		 LEFT JOIN tally_cfs_operation o on o.ctn_id=c.id
		 where $where GROUP BY c.id order by c.id desc
		";
		$list = M()->query($sql);
		$count = count($list);
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
		$sql = "select c.*,i.voyage,s.ship_name,l.location_name,o.operation_examine from 
			tally_cfs_instruction_ctn c
		 LEFT JOIN tally_cfs_instruction i on i.id=c.instruction_id
		 LEFT JOIN tally_location l on l.id=i.location_id
		 LEFT JOIN tally_user u on u.uid =c.operator_id
		 LEFT JOIN tally_cfs_instruction_cargo ic on ic.instruction_id = i.id
		 LEFT JOIN tally_ship s on i.ship_id=s.id
		 LEFT JOIN tally_port po on ic.port_id=po.id
		 LEFT JOIN tally_cfs_operation o on o.ctn_id=c.id
		 where $where GROUP BY c.id order by c.id desc limit $begin_num,$per
		";
		// $sql="select i.voyage, s.ship_name,l.location_name,c.* from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_ctn c ,__PREFIX__location l,__PREFIX__ship s,__PREFIX__cfs_instruction_cargo ic where i.id=c.instruction_id and l.id=i.location_id and s.id=i.ship_id and $where and ic.instruction_id=i.id group by c.id order by c.id desc limit $begin_num,$per";
		$list=M()->query($sql);
		//遍历结果，取出其它数据
		$num=count($list);
		$level=new \Common\Model\CfsOperationLevelModel();
		for ($i=0;$i<$num;$i++)
		{
			$ctn_id=$list[$i]['id'];
			$operation = new \Common\Model\CfsOperationModel();
			$res_o=$operation->where("ctn_id=$ctn_id")->find();
			if($res_o['id']!='')
			{
				$operation_id=$res_o['id'];
				$list[$i]['begin_time']=$res_o['begin_time'];
				//关数
				$levelnum=$level->sumLevelNum($operation_id);
				$list[$i]['level_num']=$levelnum;
				//货物件数
				$cargonum=$level->sumCargoNum($operation_id);
				$list[$i]['num']=$cargonum;
				//残损件数
				$damage_num=$level->sumDamageNum($operation_id);
				$list[$i]['damage_num']=$damage_num;
				//最新操作时间
				$res_new=$level->where("operation_id=$operation_id")->field('createtime')->order("id desc")->find();
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
		$this->assign ( 'list', $list );
		$this->display ();
	}
	
	//实时作业详情
	public function realtimeDetail($ctn_id)
	{
		//根据箱ID获取箱详情
		$container=new \Common\Model\CfsInstructionCtnModel();
		$msg=$container->getContainerMsg($ctn_id);
		$this->assign('msg',$msg);
		if($msg!==false)
		{
			$instruction_id=$msg[0]['instruction_id'];
			$sql = "select i.*,s.ship_name,l.location_name,c.blno,c.package,c.mark from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_cargo c,__PREFIX__location l,__PREFIX__ship s where i.id='$instruction_id' and c.instruction_id=i.id and l.id=i.location_id and s.id=i.ship_id order by i.id desc";
			$res_i = M()->query($sql);
			$instructionMsg = $res_i[0];
			$this->assign('instructionMsg',$instructionMsg);
		}
		//根据箱ID获取作业详情
		$operation=new \Common\Model\CfsOperationModel();
		$operationMsg=$operation->getOperationMsgByCtn($ctn_id);
		$this->assign('operationMsg',$operationMsg);
// 		print_r($operationMsg);exit;
		if($operationMsg['id']!='')
		{
			$operation_id=$operationMsg['id'];
			//根据作业ID获取空箱照片
			$empty = new \Common\Model\CfsCtnEmptyImgModel();
			$emptylist = $empty->where("operation_id='$operation_id'")->select();
			$this->assign('emptylist',$emptylist);
			//根据作业ID获取关列表
			$level=new \Common\Model\CfsOperationLevelModel();
			$levellist=$level->getLevelList($operation_id);
			$this->assign('levellist',$levellist);
		}
		$this->display();
	}
	
	//完成作业查询
	public function complete()
	{
		//集装箱船列表
		$shipModel=new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		$shiplist =$shipModel->getShipList($ship_type['container']);
		$this->assign ( 'shiplist', $shiplist );
		//作业场地
		$location=new \Common\Model\LocationModel();
		$locationlist=$location->getLocationList();
		$this->assign('locationlist',$locationlist);
		//获取箱型列表
		$container=new \Common\Model\ContainerModel();
		$containerlist=$container->getContainerList();
		$this->assign('containerlist',$containerlist);
	
		$where1 = '1';
		if(I('get.ship_name'))
		{
			$ship_name=I('get.ship_name');
			$where1.=" and ship_name='$ship_name'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where1.=" and voyage='$voyage'";
		}
		if(I('get.location_name'))
		{
			$location_name=I('get.location_name');
			$where1.=" and location_name='$location_name'";
		}
		if(I('get.ctnno'))
		{
			$ctnno=I('get.ctnno');
			$ctnno = str_replace("'", "", $ctnno);
			$where1.=" and ctnno='$ctnno'";
		}
		if(I('get.flflag'))
		{
			$flflag=I('get.flflag');
			$where1.=" and flflag='$flflag'";
		}
		if(I('get.ctn_type_code'))
		{
			$ctn_type_code=I('get.ctn_type_code');
			$ctn_type_code= str_replace("'", "", $ctn_type_code);
			$where1.=" and ctn_type_code='$ctn_type_code'";
		}
		if (I('get.begin_time') && I ('get.end_time'))
		{
			$begin_time = I ( 'begin_time' );
			$end_time = I ( 'end_time' );
			$end_time=strtotime("$end_time +1 day");
			$end_time=date('Y-m-d',$end_time);
			$where1 .= " and createtime between '$begin_time' and '$end_time' ";
		}
		if( I ('get.billno') )
		{
			$billno=I('get.billno');
			$billno = str_replace("'", "", $billno);
			$where1 .=" and content like '".'%"blno":"'. $billno .'%\'';
		}
		$prove=new \Common\Model\CfsProveModel();
		$count=$prove->where($where1)->count();
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
		$sql="select * from __PREFIX__cfs_prove where $where1 order by id desc limit $begin_num,$per";
		$list=M()->query($sql);
		$this->assign('list',$list);
		$this->display();
	}
	
	//完成作业详情
	public function completeDetail($ctn_id)
	{
		//根据箱ID获取单证详情
		$prove=new \Common\Model\CfsProveModel();
		$msg=$prove->getDocumentMsgByCtn($ctn_id);
		$this->assign('msg',$msg);
		//根据箱ID获取作业详情
		$operation=new \Common\Model\CfsOperationModel();
		$operationMsg=$operation->getOperationMsgByCtn($ctn_id);
		$this->assign('operationMsg',$operationMsg);
		if($operationMsg['id']!='')
		{
			$operation_id=$operationMsg['id'];
			//根据作业ID获取空箱照片
			$empty = new \Common\Model\CfsCtnEmptyImgModel();
			$emptylist = $empty->where("operation_id='$operation_id'")->select();
			$this->assign('emptylist',$emptylist);
			//根据作业ID获取关列表
			$level=new \Common\Model\CfsOperationLevelModel();
			$levellist=$level->getLevelList($operation_id);
			$this->assign('levellist',$levellist);
		}
		//修改记录-待做
		$this->display();
	}
	
	//分箱单证
	public function documentByCtn()
	{
		//获取船舶列表
		$ship_type=json_decode(ship_type,true);
		$ship=new \Common\Model\ShipModel();
		$shiplist=$ship->getShipList($ship_type['container']);
		$this->assign('shiplist',$shiplist);
		//获取作业地点列表
		$location=new \Common\Model\LocationModel();
		$locationlist=$location->getLocationList();
		$this->assign('locationlist',$locationlist);
	
		$where= '1';
		if(I('get.ship_name'))
		{
			$ship_name=I('get.ship_name');
			$where.=" and ship_name='$ship_name'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where.=" and voyage='$voyage'";
		}
		if(I('get.location_name'))
		{
			$location_name=I('get.location_name');
			$where.=" and location_name='$location_name'";
		}
		if(I('get.ctnno'))
		{
			$ctnno=I('get.ctnno');
			$ctnno  = str_replace("'", "", $ctnno);
			$where.=" and ctnno='$ctnno'";
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
		$prove=new \Common\Model\CfsProveModel();
		//单证总数
		$count = $prove->where ( $where )->count();
		$per = 15;
		if ($_GET ['p']) {
			$p = $_GET ['p'];
		} else {
			$p = 1;
		}
		$Page = new \Think\Page ( $count, $per ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->rollPage = 10; // 分页栏每页显示的页数
		$Page->setConfig ( 'header', '共%TOTAL_ROW%条' );
		$Page->setConfig ( 'first', '首页' );
		$Page->setConfig ( 'last', '共%TOTAL_PAGE%页' );
		$Page->setConfig ( 'prev', '上一页' );
		$Page->setConfig ( 'next', '下一页' );
		$Page->setConfig ( 'link', 'indexpagenumb' ); // pagenumb 会替换成页码
		$Page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 ' . I ( 'p', 1 ) . ' 页/共 %TOTAL_PAGE% 页 (<font color="red">' . $per . '</font> 条/页 共 %TOTAL_ROW% 条)' );
		$show = $Page->show (); // 分页显示输出
		$this->assign ( 'page', $show );
	
		//列表
		$begin=($p-1)*$per;
		$sql="select p.* from __PREFIX__cfs_prove p where $where order by p.id desc limit $begin,$per";
		$list=M()->query($sql);
		$this->assign('list',$list);
		$this->display();
	}
	
	//分箱单证详情
	public function documentByCtnMsg($id)
	{
		$prove=new \Common\Model\CfsProveModel();
		$msg=$prove->getDocumentMsg($id);
		$this->assign('msg',$msg);
		//根据箱ID获取关操作记录
		if($msg['ctn_id']!='')
		{
			$ctn_id=$msg['ctn_id'];
			//获取作业ID
			$operation=new \Common\Model\CfsOperationModel();
			$res_o=$operation->getOperationMsgByCtn($ctn_id);
			if($res_o['id']!='')
			{
				$begin_time=$res_o['begin_time'];
				$this->assign('begin_time',$begin_time);
				$operation_id=$res_o['id'];
				$level=new \Common\Model\CfsOperationLevelModel();
				$levellist=$level->getLevelList($operation_id);
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
		$ship=new \Common\Model\ShipModel();
		$shiplist=$ship->getShipList($ship_type['container']);
		$this->assign('shiplist',$shiplist);
		//获取作业地点列表
		$location=new \Common\Model\LocationModel();
		$locationlist=$location->getLocationList();
		$this->assign('locationlist',$locationlist);
	
		//获取列表
		$instruction=new \Common\Model\CfsInstructionModel();
		if(I('get.ship_name'))
		{
			$ship_name=I('get.ship_name');
			// 根据船名获取船ID
			$res_s = $ship->where ( "ship_name='$ship_name'" )->field ( 'id' )->find ();
			$ship_id = $res_s ['id'];
			$where.=" and i.ship_id='$ship_id'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where.=" and i.voyage='$voyage'";
		}
			if(I('get.location_name'))
		{
			$location_name=I('get.location_name');
			// 根据理货地点获取理货地点ID
			$res_l = $location->where ( "location_name='$location_name'" )->field ( 'id' )->find ();
			$location_id = $res_l ['id'];
			$where.=" and i.location_id='$location_id'";
		}
		if(I('get.blno'))
		{
			$blno=I('get.blno');
			$blno = str_replace("'", "", $blno);
			$where.=" and c.blno='$blno'";
		}
		$sql = "select i.* from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_cargo c where i.id=c.instruction_id $where";
		$list = M()->query($sql);
		$count = count($list);
		$per = 15;
		if ($_GET ['p']) {
			$p = $_GET ['p'];
		} else {
			$p = 1;
		}
		$begin_num=($p-1)*$per;
		$Page = new \Think\Page ( $count, $per ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->rollPage = 10; // 分页栏每页显示的页数
		$Page->setConfig ( 'header', '共%TOTAL_ROW%条' );
		$Page->setConfig ( 'first', '首页' );
		$Page->setConfig ( 'last', '共%TOTAL_PAGE%页' );
		$Page->setConfig ( 'prev', '上一页' );
		$Page->setConfig ( 'next', '下一页' );
		$Page->setConfig ( 'link', 'indexpagenumb' ); // pagenumb 会替换成页码
		$Page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 ' . I ( 'p', 1 ) . ' 页/共 %TOTAL_PAGE% 页 (<font color="red">' . $per . '</font> 条/页 共 %TOTAL_ROW% 条)' );
		$show = $Page->show (); // 分页显示输出
		$this->assign ( 'page', $show );
	    $sql = "select i.voyage,i.id,s.ship_name,l.location_name,c.blno from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_cargo c,__PREFIX__location l,__PREFIX__ship s where i.id=c.instruction_id and l.id=i.location_id and s.id=i.ship_id $where order by i.id desc limit $begin_num,$per";
		$list = M()->query($sql);
		//$list = $instruction->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	//分票单证详情
	public function documentByTicketMsg($blno,$instruction_id)
	{
		//根据指令ID获取指令详情
		$sql = "select i.voyage,s.ship_name,l.location_name,c.blno from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_cargo c,__PREFIX__location l,__PREFIX__ship s where i.id='$instruction_id' and i.id=c.instruction_id and l.id=i.location_id and s.id=i.ship_id order by i.id desc";
		$res_i=M()->query($sql);
		$instructionMsg=$res_i[0];
		$this->assign('instructionMsg',$instructionMsg);
		//根据提单号查找单证列表
		$prove=new \Common\Model\CfsProveModel();
		$bl='"blno":"'.$blno.'",';
		$list=$prove->where("content like '%$bl%'")->select();
		$this->assign('list',$list);
		//计算总箱数、总件数、总残损
		$sql="select count(id) as total_ctn,sum(total_package) as total_num,sum(damage_num) as total_damage from __PREFIX__cfs_prove where content like '%$bl%'";
		$res=M()->query($sql);
		$this->assign('total_ctn',$res[0]['total_ctn']);
		$this->assign('total_num',$res[0]['total_num']);
		$this->assign('total_damage',$res[0]['total_damage']);
		//批注
		foreach ($list as $l)
		{
			$remark.= $l['ctnno'] . ':' . $l ['remark'] . ';';
		}
		$this->assign('remark',$remark);
		//完成时间
		$num=count($list);
		$finished_time=$list[$num-1]['createtime'];
		$this->assign('finished_time',$finished_time);
	
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
			$zname1 = 'cfs图片打包'.'.zip';
			if ($img_file->open($zname1, \ZIPARCHIVE::CREATE)===TRUE) {
				foreach($ctn_id_list as $o)
				{
					//根据箱ID获取指令ID
					$instructctn = new \Common\Model\CfsInstructionCtnModel();
					$instruction_id = $instructctn->field('instruction_id,ctnno')->where("id='$o'")->find();
					if($instruction_id)
					{
						// 新建一个ZipArchive的对象
						$zip = new \ZipArchive();
						//设置.zip下载后的文件名
						$zname = $instruction_id['ctnno']."_".$o.'.zip';
						$filelist[] = $zname;
						//获取铅封照片，办关门照片，全关门照片，空想照片(获取作业详情)
						$operation = new \Common\Model\CfsOperationModel();
						$msg=$operation->getOperationMsgByCtn($o);
						//获取关详情
						$level = new \Common\Model\CfsOperationLevelModel();
						$levelList=$level->getLevelList($msg['id']);
						// 						dump($levelList);die;
						//创建临时文件夹保存图片
						$filename = $instruction_id['ctnno']."_".$o;
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
								
									foreach($vo['level_cargo_img'] as $k)
									{
										$cargo_name = "./Public/upload/cfs/cargo/".$k['level_img'];
										copy($cargo_name, './'.$filename.'/'.$k['level_img']);
										$zip->addFile('./'.$filename.'/'.$k['level_img']);
									}
									foreach ($vo['cargo_damage_img'] as $v)
									{
										$cdamage_name = "./Public/upload/cfs/cdamage/".$v['img'];
										copy ( $cdamage_name , './'.$filename.'/'.$v['img'] );
										$zip->addFile('./'.$filename.'/'.$v['img']);
									}
								}
							}
							//铅封照片，办关门照片，全关门照片，空想照片
							if($msg != '')
							{
								//空箱照片
								foreach ($msg['empty_img'] as $vo)
								{
									$empty_pic = "./Public/upload/cfs/empty/".$vo['empty_img'];
									copy($empty_pic , './'.$filename.'/'.$vo['empty_img']);
									$zip->addFile( './'.$filename.'/'.$vo['empty_img']);
								}
								//铅封照片
								$seal_pic = "./Public/upload/cfs/seal/".$msg ['seal_picture'];
								copy($seal_pic , './'.$filename.'/'.$msg ['seal_picture'] );
								$zip->addFile('./'.$filename.'/'.$msg ['seal_picture']);
								//半关门
								$halfclose_door_picture = "./Public/upload/cfs/halfclosedoor/".$msg ['halfclose_door_picture'];
								copy($halfclose_door_picture , './'.$filename.'/'.$msg ['halfclose_door_picture'] );
								$zip->addFile('./'.$filename.'/'.$msg ['halfclose_door_picture']);
								//全关门
								$close_door_picture = "./Public/upload/cfs/closedoor/".$msg ['close_door_picture'];
								copy($close_door_picture , './'.$filename.'/'.$msg ['close_door_picture'] );
								$zip->addFile('./'.$filename.'/'.$msg ['close_door_picture']);
							}
							//$zip->addFromString("图片打包文件说明.txt", "cargo：关照片\r\ncdamage：关残损照片\r\nempty：空箱照片\r\nseal：铅封照片\r\nhalfclose_door_picture_a：半关门照片\r\nclose_door_picture_a：全关门照片");
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