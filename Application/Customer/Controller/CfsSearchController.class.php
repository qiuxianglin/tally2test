<?php
/**
 * 查询统计
 * CFS装箱查询
 */
namespace Customer\Controller;
use Think\Controller;

class CfsSearchController extends CommonController
{
	public function index(){
		$this->display();
	}

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
		
		$id = $_SESSION['id'];
		$where="c.status in (1,2) and o.operation_examine in (1,3) and i.entrust_company=$id";
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
		$sql = "select c.*,i.voyage,s.ship_name,l.location_name from 
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
		
		$id = $_SESSION['id'];
		$where1 = "i.entrust_company=$id";
		if(I('get.ship_name'))
		{
			$ship_name=I('get.ship_name');
			$where1.=" and p.ship_name='$ship_name'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where1.=" and p.voyage='$voyage'";
		}
		if(I('get.location_name'))
		{
			$location_name=I('get.location_name');
			$where1.=" and p.location_name='$location_name'";
		}
		if(I('get.ctnno'))
		{
			$ctnno=I('get.ctnno');
			$ctnno = str_replace("'", "", $ctnno);
			$where1.=" and p.ctnno='$ctnno'";
		}
		if(I('get.flflag'))
		{
			$flflag=I('get.flflag');
			$where1.=" and p.flflag='$flflag'";
		}
		if(I('get.ctn_type_code'))
		{
			$ctn_type_code=I('get.ctn_type_code');
			$ctn_type_code= str_replace("'", "", $ctn_type_code);
			$where1.=" and p.ctn_type_code='$ctn_type_code'";
		}
		if (I('get.begin_time') && I ('get.end_time'))
		{
			$begin_time = I ( 'begin_time' );
			$end_time = I ( 'end_time' );
			$end_time=strtotime("$end_time +1 day");
			$end_time=date('Y-m-d',$end_time);
			$where1 .= " and p.createtime between '$begin_time' and '$end_time' ";
		}
		if( I ('post.billno') )
		{
			$billno=I('post.billno');
			$billno = str_replace("'", "", $billno);
			$where1.=" and p.content like '".'%"billno":"'. $billno .'%\'';
		}
		$prove=new \Common\Model\CfsProveModel();
		$count=$prove->alias('p')->field('p.*')
				->join('tally_cfs_instruction_ctn c ON c.id=p.ctn_id')
				->join('tally_cfs_instruction i ON i.id=c.instruction_id')
				->where($where1)->count();
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
		//$sql="select p.* from __PREFIX__cfs_prove p where $where1 order by p.id desc limit $begin_num,$per";
		$list=$prove->alias('p')->field('p.*')
				->join('tally_cfs_instruction_ctn c ON c.id=p.ctn_id')
				->join('tally_cfs_instruction i ON i.id=c.instruction_id')
				->where($where1)->order('p.id desc')->limit($begin_num,$per)->select();
		//$list=M()->query($sql);
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
		//该客户可查看照片的权限
		$Customer = M('customer');
		$id = $_SESSION['id'];
		$cus = $Customer->field('authority')->where('id='.$id)->find();
		$authority = json_decode($cus['authority'],true);
		$photoauth = $authority['photo'];
		$this->assign('photoauth',$photoauth);
		//客户是否有权限
		$a = in_array('1',$photoauth) && in_array('2',$photoauth);
		$b = in_array('1',$photoauth) && !in_array('2',$photoauth);
		$c = !in_array('1',$photoauth) && in_array('2',$photoauth);
		$this->assign('a',$a);
		$this->assign('b',$b);
		$this->assign('c',$c);
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
	
		$id = $_SESSION['id'];
		$where = "i.entrust_company=$id";
		if(I('get.ship_name'))
		{
			$ship_name=I('get.ship_name');
			$where.=" and p.ship_name='$ship_name'";
		}
		if(I('get.voyage'))
		{
			$voyage=I('get.voyage');
			$voyage = str_replace("'", "", $voyage);
			$where.=" and p.voyage='$voyage'";
		}
		if(I('get.location_name'))
		{
			$location_name=I('get.location_name');
			$where.=" and p.location_name='$location_name'";
		}
		if(I('get.ctnno'))
		{
			$ctnno=I('get.ctnno');
			$ctnno  = str_replace("'", "", $ctnno);
			$where.=" and p.ctnno='$ctnno'";
		}
		if(I('get.flflag'))
		{
			$flflag=I('get.flflag');
			$where.=" and p.flflag='$flflag'";
		}
		if (I ( 'begin_time' ) && I ( 'end_time' ))
		{
			$begin_time = I ( 'get.begin_time' );
			$end_time = I ( 'get.end_time' );
			$end_time=strtotime("$end_time +1 day");
			$end_time=date('Y-m-d',$end_time);
			$where .= " and p.createtime between '$begin_time' and '$end_time' ";
		}
		$prove=new \Common\Model\CfsProveModel();
		//单证总数
		$count = $prove->alias('p')->field('p.*')
				->join('tally_cfs_instruction_ctn c ON c.id=p.ctn_id')
				->join('tally_cfs_instruction i ON i.id=c.instruction_id')
				->where ( $where )->count();
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
		/* $sql="select p.* from __PREFIX__cfs_prove p where $where order by p.id desc limit $begin,$per";
		$list=M()->query($sql); */
		$list=$prove->alias('p')->field('p.*')
				->join('tally_cfs_instruction_ctn c ON c.id=p.ctn_id')
				->join('tally_cfs_instruction i ON i.id=c.instruction_id')
				->where($where)->order('p.id desc')->limit($begin_num,$per)->select();
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
		$id = $_SESSION['id'];
		$where = "i.entrust_company=$id";
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
		$sql = "select i.* from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_cargo c where i.id=c.instruction_id and $where";
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
	    $sql = "select i.voyage,i.id,s.ship_name,l.location_name,c.blno from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_cargo c,__PREFIX__location l,__PREFIX__ship s where i.id=c.instruction_id and l.id=i.location_id and s.id=i.ship_id and $where order by i.id desc limit $begin_num,$per";
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
		
}