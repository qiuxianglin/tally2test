<?php
/**
 * 查询统计
 * 起驳装箱查询
 */
namespace Index\Controller;
use Index\Common\BaseController;

class QbzxSearchController extends BaseController
{
	 // 实时作业查询
	public function RealTime() {
		\Common\Common\LogController::info(123);

		// 集装箱船列表
		$ship = new \Common\Model\ShipModel ();
		$ship_type = json_decode ( ship_type, true );
		$shiplist = $ship->getShipList ( $ship_type ['container'] );
		$this->assign ( 'shiplist', $shiplist );
		// 作业场地
		$location = new \Common\Model\LocationModel ();
		$locationlist = $location->getLocationList ();
		$this->assign ( 'locationlist', $locationlist );
		// 港口信息
		$port = new \Common\Model\PortModel ();
		$portlist = $port->getPortList ();
		$this->assign ( 'portlist', $portlist );

		$where = "c.status in (1,2) and c.operator_id!='' and o.operation_examine in (1,3)";
		if (I ( 'get.ship_name' )) {
			$ship_name = I ( 'get.ship_name' );
			// 根据船名获取船ID
			$res_s = $ship->where ( "ship_name='$ship_name'" )->field ( 'id' )->find ();
			$ship_id = $res_s ['id'];
			$where .= " and p.ship_id='$ship_id'";
		}
		if (I ( 'get.voyage' )) {
			$voyage = I ( 'get.voyage' );
			$voyage = str_replace ( "'", " ", $voyage );
			$where .= " and p.voyage='$voyage'";
		}
		if (I ( 'get.port' )) {
			// 检验港口是否正确
			$port_name = I ( 'get.port' );
			$port_name = str_replace ( "'", " ", $port_name );
			// 根据港口名称获取港口ID
			$port_id = $port->where ( "name='$port_name'" )->field ( 'id' )->find ();
			$port_id = $port_id ['id'];
			$where .= " and pc.port_id='$port_id'";
		}
		if (I ( 'get.location_name' )) {
			$location_name = I ( 'get.location_name' );
			// 根据理货地点获取理货地点ID
			$res_l = $location->where ( "location_name='$location_name'" )->field ( 'id' )->find ();
			$location_id = $res_l ['id'];
			$where .= " and i.location_id='$location_id'";
		}
		if(I('get.billno')){
			$billno = I ( 'get.billno' );
			$billno = str_replace ( "'", " ", $billno );
			$where .= " and pc.billno like '%$billno%'";
		}
		if(I('get.ctnno')){
			$ctnno = I ( 'get.ctnno' );
			$ctnno = str_replace ( "'", " ", $ctnno );
			$where .= " and c.ctnno like '%$ctnno%'";
		}
		if(I('get.cstatus'))
		{
			$cstatus = I('get.cstatus');
			$where .= " and c.status='$cstatus'";
		}
		$sql = "select i.plan_id,pc.billno,po.name poname,c.*,u.user_name,p.voyage,s.ship_name,l.location_name
			from tally_qbzx_instruction_ctn c
			LEFT JOIN tally_qbzx_instruction i on i.id=c.instruction_id
			LEFT JOIN tally_location l on l.id=i.location_id
			LEFT JOIN tally_user u on u.uid =c.operator_id
			LEFT JOIN tally_qbzx_plan p on i.plan_id=p.id
			LEFT JOIN tally_qbzx_plan_cargo pc on pc.plan_id=p.id
			LEFT JOIN tally_ship s on p.ship_id=s.id
			LEFT JOIN tally_port po on pc.port_id=po.id
			LEFT JOIN tally_qbzx_operation o on o.ctn_id=c.id
			where $where GROUP BY c.id order by c.id desc";
		$list = M ()->query ( $sql );
		$count = count ( $list );
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

		$begin_num = ($p - 1) * $per;
		$sql = "select i.plan_id,pc.billno,po.name poname,c.*,u.user_name,p.voyage,s.ship_name,l.location_name,o.operation_examine
			from tally_qbzx_instruction_ctn c 
			LEFT JOIN tally_qbzx_instruction i on i.id=c.instruction_id 
			LEFT JOIN tally_location l on l.id=i.location_id
			LEFT JOIN tally_user u on u.uid =c.operator_id
			LEFT JOIN tally_qbzx_plan p on i.plan_id=p.id
			LEFT JOIN tally_qbzx_plan_cargo pc on pc.plan_id=p.id
			LEFT JOIN tally_ship s on p.ship_id=s.id
			left join tally_port po on pc.port_id=po.id
			LEFT JOIN tally_qbzx_operation o on o.ctn_id=c.id
			where  $where GROUP BY c.id order by c.id desc  limit $begin_num,$per";

		$list = M ()->query ( $sql );
		// 遍历结果，取出其它数据
		$num = count ( $list );
		$level = new \Common\Model\QbzxOperationLevelModel ();
		for($i = 0; $i < $num; $i ++) {
			$ctn_id = $list [$i] ['id'];
			$operation = new \Common\Model\QbzxOperationModel ();
			$res_o = $operation->where ( "ctn_id=$ctn_id" )->find ();
			if ($res_o ['id'] != '') {
				$operation_id = $res_o ['id'];
				$list [$i] ['begin_time'] = $res_o ['begin_time'];
				// 关数
				$levelnum = $level->sumLevelNum ( $operation_id );
				$list [$i] ['level_num'] = $levelnum;
				// 货物件数
				$cargonum = $level->sumCargoNum ( $operation_id );
				$list [$i] ['cargo_number'] = $cargonum;
				// 残损件数
				$damage_num = $level->sumDamageNum ( $operation_id );
				$list [$i] ['damage_num'] = $damage_num;
				// 最新操作时间
				$res_new = $level->where ( "operation_id=$operation_id" )->field ( 'createtime' )->order ( "id desc" )->find ();
				if ($res_new ['createtime'] != '') {
					$list [$i] ['newtime'] = $res_new ['createtime'];
				} else {
					$list [$i] ['newtime'] = $res_o ['begin_time'];
				}
				$time1 = strtotime ( $list [$i] ['newtime'] );
				$time2 = time ();
				if (($time2 - $time1) / 300 > 1) {
					$list [$i] ['red'] = 1;
				}
			}
		}
		$this->assign ( 'list', $list );
		$this->display ();
	}
	
	//实时作业详情
	public function RealTimeDetail($ctn_id) {
		// 传入图片路径常量
		$this->assign ( 'IMAGE_QBZX_CARGO', IMAGE_QBZX_CARGO );
		$this->assign ( 'IMAGE_QBZX_SEAL', IMAGE_QBZX_SEAL );
		$this->assign ( 'IMAGE_QBZX_EMPTY', IMAGE_QBZX_EMPTY );
		$this->assign ( 'IMAGE_QBZX_CDAMAGE', IMAGE_QBZX_CDAMAGE );
		// 根据箱ID获取箱详情
		$container = new \Common\Model\QbzxInstructionCtnModel ();
		$msg = $container->getContainerMsg ( $ctn_id );
		$this->assign ( 'msg', $msg );
		if ($msg !== false) {
			$instruction_id = $msg ['instruction_id'];
			$sql = "select i.*,s.ship_name,p.voyage,l.location_name from __PREFIX__qbzx_instruction i,__PREFIX__location l,__PREFIX__ship s,__PREFIX__qbzx_plan p where i.id='$instruction_id' and l.id=i.location_id and s.id=p.ship_id and i.plan_id=p.id  order by i.id desc";
			$res_i = M ()->query ( $sql );
			$instructionMsg = $res_i [0];
			$this->assign ( 'instructionMsg', $instructionMsg );
		}
		// 根据箱ID获取作业详情
		$operation = new \Common\Model\QbzxOperationModel ();
		$operationMsg = $operation->getOperationMsg ( $ctn_id );

		$this->assign ( 'operationMsg', $operationMsg );
		if ($operationMsg ['id'] != '') {
			$operation_id = $operationMsg ['id'];
			// 根据作业ID获取关列表
			$level = new \Common\Model\QbzxOperationLevelModel ();
			$levellist = $level->getLevelList ( $operation_id );
			$this->assign ( 'levellist', $levellist );
		}
		$this->display ();
	}
	
	//完成作业查询
	public function OperationFinish()
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
	
		$where = '1';
		if(I('post.ship_name'))
		{
			$ship_name=I('post.ship_name');
			$ship_id = $shipModel->field('id')->where("ship_name='$ship_name'")->find();
			$where.=" and ship_id='".$ship_id['id']."'";
		}
		if(I('post.location_name'))
		{
			$location_name=I('post.location_name');
			$location_id = $location->field('id')->where("location_name='$location_name'")->find();
			$where.=" and location_id='".$location_id['id']."'";
		}
		if(I('post.voyage'))
		{
			$voyage=I('post.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where.=" and voyage='$voyage'";
		}
		if(I('post.ctnno'))
		{
			$ctnno=I('post.ctnno');
			$ctnno = str_replace("'", "", $ctnno);
			$where.=" and ctnno='$ctnno'";
		}
		if(I('post.flflag'))
		{
			$flflag=I('post.flflag');
			$where.=" and flflag='$flflag'";
		}
		if(I('get.ctn_type_code'))
		{
			$ctn_type_code=I('post.ctn_type_code');
			$ctn_type_code = str_replace("'", "", $ctn_type_code);
			$where.=" and ctn_type_code='$ctn_type_code'";
		}
		if (I('post.begin_time') && I ('post.end_time'))
		{
			$begin_time = I('post.begin_time');
			$end_time = I('post.end_time');
			$end_time=strtotime("$end_time +1 day");
			$end_time=date('Y-m-d',$end_time);
			$where .= " and createtime between '$begin_time' and '$end_time' ";
		}
		if( I ('post.billno') )
		{
			$billno=I('post.billno');
			$billno = str_replace("'", "", $billno);
			$where.=" and content like '".'%"billno":"'. $billno .'%\'';
		}
		
		$prove=new \Common\Model\QbzxProveModel();
		$count=$prove->where($where)->count();
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
			
		//$begin_num=($p-1)*$per;
		/*$sql="select p.* from __PREFIX__qbzx_prove p where $where and p.ship=s.shipInfoId and p.location=l.operationLocationInfoId order by p.id desc limit $begin_num,$per";
		$list=M()->query($sql);*/
		$list = $prove->where($where)->page($p.','.$per)->order('id desc')->select();
		
		$this->assign('list',$list);
		$this->display();
	}
	
	//完成作业详情
	public function OperationFinishDetail($ctn_id) {
		// 引入图片路径
		$this->assign ( 'IMAGE_QBZX_SEAL', IMAGE_QBZX_SEAL ); // 铅封照
		$this->assign ( 'IMAGE_QBZX_EMPTY', IMAGE_QBZX_EMPTY ); // 空箱照
		$this->assign ( 'IMAGE_QBZX_CARGO', IMAGE_QBZX_CARGO ); // 货物照
		$this->assign ( 'IMAGE_QBZX_CDAMAGE', IMAGE_QBZX_CDAMAGE ); // 货残损照
		$this->assign ( 'IMAGE_QBZX_HALFCLOSEDOOR', IMAGE_QBZX_HALFCLOSEDOOR ); // 半关门照
		$this->assign ( 'IMAGE_QBZX_CLOSEDOOR', IMAGE_QBZX_CLOSEDOOR ); // 全关门照
		                                                            // 根据箱ID获取单证详情
		$prove = new \Common\Model\QbzxProveModel ();
		$msg = $prove->getDocumentMsgByCtn ( $ctn_id );
		$this->assign ( 'msg', $msg );
		// 根据箱ID获取作业详情
		$operation = new \Common\Model\QbzxOperationModel ();
		$operationMsg = $operation->getOperationMsgByCtn ( $ctn_id );
		$this->assign ( 'operationMsg', $operationMsg );
		if ($operationMsg ['id'] != '') {
			$operation_id = $operationMsg ['id'];
			// 根据作业ID获取空箱照片
			$empty = new \Common\Model\QbzxEmptyCtnImgModel ();
			$emptylist = $empty->where ( "operation_id='$operation_id'" )->select ();
			$this->assign ( 'emptylist', $emptylist );
			// 根据作业ID获取关列表
			$level = new \Common\Model\QbzxOperationLevelModel ();
			$levellist = $level->getLevelList ( $operation_id );
			$this->assign ( 'levellist', $levellist );
		}
		// 修改记录-待做
		// dump($levellist);die;
		$this->display ();
	}
	
	//分箱单证
	public function ProveByCtn()
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
	
		$where = '1';
		if(I('get.ship_name'))
		{
			$ship_name=I('get.ship_name');
			$ship_id = $ship->field('id')->where("ship_name='$ship_name'")->find();
			$where.=" and ship_id='".$ship_id['id']."'";
		}
		
		if(I('get.location_name'))
		{
			$location_name=I('get.location_name');
			$location_id = $location->field('id')->where("location_name='$location_name'")->find();
			$where.=" and location_id='".$location_id['id']."'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where.=" and voyage='$voyage'";
		}
		if(I('get.ctnno'))
		{
			$ctnno=I('get.ctnno');
			$ctnno = str_replace("'", "", $ctnno);
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
		$prove=new \Common\Model\QbzxProveModel();
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
	
		$list = $prove->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	//分箱单证详情
	public function ProveByCtnMsg($id)
	{
		$prove=new \Common\Model\QbzxProveModel();
		$msg=$prove->getDocumentMsg($id);
		$this->assign('msg',$msg);
		//根据箱ID获取关操作记录
		if($msg['ctn_id']!='')
		{
			$ctn_id=$msg['ctn_id'];
			//获取作业ID
			$operation=new \Common\Model\QbzxOperationModel();
			$res_o=$operation->getOperationMsgByCtn($ctn_id);
			if($res_o['id']!='')
			{
				$begin_time=$res_o['begin_time'];
				$this->assign('begin_time',$begin_time);
				$operation_id=$res_o['id'];
				$level=new \Common\Model\QbzxOperationLevelModel();
				$levellist=$level->getLevelList($operation_id);
				$this->assign('list',$levellist);
			}
		}
		$this->display();
	}
	
	//分票单证
	public function ProveByTicket()
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
		$instruction=new \Common\Model\QbzxInstructionModel();
		$where = '1';

		if(I('get.ship_name'))
		{
			$ship_name=I('get.ship_name');
			$ship_id = $ship->field('id')->where("ship_name='$ship_name'")->find();
			$where.=" and p.ship_id='".$ship_id['id']."'";
		}
		
		if(I('get.location_name'))
		{
			$location_name=I('get.location_name');
			$location_id = $location->field('id')->where("location_name='$location_name'")->find();
			$where.=" and i.location_id='".$location_id['id']."'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where.=" and p.voyage='$voyage'";
		}
		if(I('get.billno'))
		{
			$billno=I('get.billno');
			$billno= str_replace("'", "", $billno);
			$where.=" and c.billno='$billno'";
		}
		$sql = "select i.* from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_plan_cargo c,__PREFIX__qbzx_plan p where i.plan_id=p.id and i.plan_id=c.plan_id  and $where";
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
	    $sql = "select p.voyage,i.id,s.ship_name,l.location_name,c.billno from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_plan_cargo c,__PREFIX__location l,__PREFIX__ship s,__PREFIX__qbzx_plan p where i.plan_id=c.plan_id and l.id=i.location_id and s.id=p.ship_id and i.plan_id=p.id and p.id=c.plan_id and $where order by i.id desc limit $begin_num,$per";
		$list = M()->query($sql);
		//$list = $instruction->where($where)->page($p.','.$per)->order('id desc')->select();

		$this->assign('list',$list);
		$this->display();
	}
	
	//分票单证详情
	public function ProveByTicketMsg($billno,$instruction_id)
	{
		//根据指令ID获取指令详情
		$sql = "select p.voyage,s.ship_name,l.location_name,c.billno from __PREFIX__qbzx_instruction i,__PREFIX__qbzx_plan_cargo c,__PREFIX__location l,__PREFIX__ship s,__PREFIX__qbzx_plan p where i.id='$instruction_id' and i.plan_id=c.plan_id and i.plan_id=p.id and l.id=i.location_id and s.id=p.ship_id order by i.id desc";
		$res_i=M()->query($sql);
		$instructionMsg=$res_i[0];
		$this->assign('instructionMsg',$instructionMsg);
		//根据提单号查找单证列表
		$prove=new \Common\Model\QbzxProveModel();
		$bl='"billno":"'.$billno.'",';
		$list=$prove->where("content like '%$bl%'")->select();
		$this->assign('list',$list);
		//计算总箱数、总件数、总残损
		$sql="select count(id) as total_ctn,sum(total_package) as total_num,sum(damage_num) as total_damage from __PREFIX__qbzx_prove where content like '%$bl%'";
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
		// var_dump()
		$this->display();
	}
	
	//分驳船单证
	public function ProveByShip()
	{
		$ship = new \Common\Model\ShipModel();
		$ship_type=json_decode(ship_type,true);
		//驳船列表
		$shiplist=$ship->getShipList($ship_type['barge']);
		$this->assign ( 'shiplist', $shiplist );
		//集装箱船列表
		$shiplist2=$ship->getShipList($ship_type['container']);
		$this->assign ( 'shiplist2', $shiplist2 );
		
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$begin_num=($p-1)*$per;
		$prove = new \Common\Model\QbzxProveModel();
		if(I('get.ship_container') or I('get.ship_barge') or I('get.voyage') )
		{
			if (I ( 'get.ship_barge' )) {
				//检验船舶名称是否正确
				$shipname = I ( 'get.ship_barge' );
				$res_s=$ship->where("ship_name='$shipname'")->field('id')->find();
				if($res_s['id']=='')
				{
					layout(false);
					$this->error('船舶名称不存在！');
				}else {
					$ship_id = $res_s['id'];
					$like_s='"ship_id":"'.$ship_id.'"';
					$where=" barge_ship_content like '%$like_s%' ";
					$this->assign('ship_id',$ship_id);
				}
			}else {
				layout(false);
				$this->error('驳船名称不能为空！');
			}
			if (I ( 'get.ship_container' )) {
				//检验船舶名称是否正确
				$ship_container = I ( 'get.ship_container' );
				$res_s=$ship->where("ship_name='$ship_container'")->field('id')->find();
				if($res_s['id']=='')
				{
					layout(false);
					$this->error('船舶名称不存在！');
				}else {
					$containership_id = $res_s['id'];
					$where.="and ship_id='$containership_id'";
				}
			}
			if(I('get.voyage'))
			{
				$voyage=I('get.voyage');
				$voyage = str_replace("'", "", $voyage);
				$where.=" and voyage='$voyage'";
			}
			
			//单证列表
			// $ctnlist = $prove->where($where)->page($p.','.$per)->order('id desc')->select();
			/*$sql2="select p.* from __PREFIX__qbzx_prove p,shipinfo s,operationlocationinfo l where c.ship=s.shipInfoId and c.location=l.operationLocationInfoId $where order by c.id desc limit $begin,$per";
			$ctnlist=M('')->query($sql2);*/
			//总记录条数--用于计算分页
			$res = $prove->where("$where and barge_ship_content!='null'")->order('id desc')->select();
			$count = count ( $res );
		}else {
			//单证列表
			// $ctnlist = $prove->page($p.','.$per)->order('id desc')->select();
			$where ="1 and barge_ship_content!='null'";
			//总记录条数--用于计算分页
			$res = $prove->where("$where")->order('id desc')->select();
			$count = count ( $res );
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

		$ctnlist = $prove->where("$where")->page($begin_num.','.$per)->order('id desc')->select();
		
		$this->assign ( 'ctnlist', $ctnlist );
		$this->display ();
	}

	//分驳船单证详情
	public function ProveByShipMsg($ctn_id,$ship_id)
	{
		//根据单证ID查询单证详情
		$prove = new \Common\Model\QbzxProveModel();
		$res = $prove->where("ctn_id='$ctn_id'")->select();
		$ctn_content=$res[0];
		//驳船对应的总件数，总残损数
		$ship_content=json_decode($ctn_content['barge_ship_content'],true);
		foreach ($ship_content as $s)
		{
			if($s['ship_id']==$ship_id)
			{
				$ctn_content['cargo_unit']=$s['cargo_unit'];
				$ctn_content['damage_unit']=$s['damage_unit'];
			}
		}
		//驳船名称
		$ship=new \Common\Model\ShipModel();
		$res_s=$ship->getShipMsg($ship_id);
		$ctn_content['ship_name']=$res_s['ship_name'];
		$this->assign('ctn_content',$ctn_content);
		//箱ID
		$container_id=$ctn_content['ctn_id'];
		//根据箱ID查询作业ID
		$sql = "select c.*,o.id from __PREFIX__qbzx_operation o,__PREFIX__qbzx_instruction_ctn c where c.id=o.ctn_id and c.id='$container_id' and c.status = '2'";
		$res_o = M()->query($sql); 
		if($res_o)
		{
			$operation_id=$res_o[0]['id'];
			//根据作业ID统计关信息
			//并且驳船ID和查询的驳船相同
			$sql2="select l.*,u.user_name,c.pack,c.mark from __PREFIX__qbzx_operation_level l,__PREFIX__user u,__PREFIX__qbzx_plan_cargo c where l.operation_id=$operation_id and l.ship_id=$ship_id and l.operator_id=u.uid and l.billno=c.billno";
			//作业关列表
			$list=M()->query($sql2);
			$this->assign('list',$list);
			//装箱作业开始时间
			$level = new \Common\Model\QbzxOperationLevelModel();
			$res_t=$level->where("operation_id=$operation_id")->field('createtime')->find();
			if($res_t!==false)
			{
				$this->assign('begin_time',$res_t['createtime']);
			}
			$this->display();
		}
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
			$zname1 = '起驳图片打包'.'.zip';
			if ($img_file->open($zname1, \ZIPARCHIVE::CREATE)===TRUE) {
				foreach($ctn_id_list as $o)
				{
					//根据箱ID获取指令ID
					$instructctn = new \Common\Model\QbzxInstructionCtnModel();
					$instruction_id = $instructctn->field('instruction_id,ctnno')->where("id='$o'")->find();
					if($instruction_id)
					{
						// 新建一个ZipArchive的对象
						$zip = new \ZipArchive();
						//设置.zip下载后的文件名
						$zname = $instruction_id['ctnno']."_".$o.'.zip';
						$filelist[] = $zname;
						//获取铅封照片，办关门照片，全关门照片，空想照片(获取作业详情)
						$operation = new \Common\Model\QbzxOperationModel();
						$msg=$operation->getOperationMsg($o);
	
						//获取关详情
						$level=new \Common\Model\QbzxOperationLevelModel();
						$levelList=$level->getLevelList($msg['id']);
	
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