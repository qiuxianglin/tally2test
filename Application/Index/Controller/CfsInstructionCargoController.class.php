<?php
/**
 * CFS装箱-配货
 */
namespace Index\Controller;
use Index\Common\BaseController;

class CfsInstructionCargoController extends BaseController
{
	public function add($instruction_id)
	{
		$uid = $_SESSION['uid'];
		layout(false);
		$this->assign('instruction_id',$instruction_id);
		// 港口信息
		$port = new \Common\Model\PortModel ();
		$portlist = $port->getPortList ();
		$this->assign ( 'portlist', $portlist );
		if(I('post.'))
		{
			if(I('post.billno'))
			{
				$blno=strtoupper(I('post.billno'));
			}else {
				$this->error('提单号不能为空！');
			}
			//检查参数是否含有特殊字符
			$data_b = filterString($blno);
			if($data_b == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			if(I('post.cargoname'))
			{
				$name=I('post.cargoname');
			}else {
				$this->error('货名不能为空！');
			}
			//检查参数是否含有特殊字符
			$data_c = filterString($name);
			if($data_c == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			if(I('post.package'))
			{
				$package=I('post.package');
			}else {
				$this->error('包装不能为空！');
			}
			//检查参数是否含有特殊字符
			$data_p = filterString($package);
			if($data_p == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			if(I('post.number'))
			{
				$po=I('post.number');
			}else {
				$this->error('件数不能为空！');
			}
			//检查参数是否含有特殊字符
			$data_po = filterString($po);
			if($data_po == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			// 检验目的港名称是否正确
			$port = new \Common\Model\PortModel();
			$port_name = I ( 'post.port_name' );
			$data_l = filterString ( $port_name );
			if ($data_l == false) {
				$this->error ( '目的港不能含有特殊字符' );
				exit ();
			}
			$res_l = $port->where ( "name='$port_name'" )->field ( 'id' )->find ();
			if ($res_l ['id'] == '') {
				$this->error ( '目的港不存在！' );
			} else {
				$port_id = $res_l ['id'];
			}
			$data=array(
					'instruction_id'=>$instruction_id,
					'blno'=>I ('post.billno'),
					'name'=>I ('post.cargoname'),
					'number'=>I ('post.number'),
					'package'=>I ('post.package'),
					'mark'=>I ('post.mark'),
					'totalweight'=>I ('post.totalweight'),
					'dangerlevel'=>I ('post.dangerlevel'),
			        'totalvolume'=>I('post.totalvolume'),
					'po'=>I('post.po'),
					'remark'=>I('post.remark'),
					'crgno'=>I('post.crgno'),
					'last_operator'=>$uid,
					'last_operationtime'=>date('Y-m-d H:i:s'),
					'port_id'   =>   $port_id
			);				
			$instructionCargo=new \Common\Model\CfsInstructionCargoModel();
			if(!$instructionCargo->create($data))
			{
				//对data数据进行操作
				$this->error($instructionCargo->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res = $instructionCargo->add($data);
				if ($res !==false)
				{
					$this->success('新增指令配货成功！',U('CfsInstruction/edit',array('id'=>$instruction_id)));
				} else {
					$this->error('操作失败!');
				}
			}
		}else {
			$this->display();
		}
	}
	//编辑配货
	public function edit($id)
	{
		layout(false);
		$this->assign('id',$id);
		//根据ID获取配货信息
		$instructioncargo = new \Common\Model\CfsInstructionCargoModel();
		$msg = $instructioncargo->getCargoMsg($id);
		$this->assign('msg',$msg);
		// 港口信息
		$port = new \Common\Model\PortModel ();
		$portlist = $port->getPortList ();
		$this->assign ( 'portlist', $portlist );
		if(I('post.'))
		{
			if(I('post.blno'))
			{
				$blno=strtoupper(I('post.blno'));
			}else {
				$this->error('提单号不能为空！');
			}
			//检查参数是否含有特殊字符
			$data_b = filterString($blno);
			if($data_b == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			if(I('post.name'))
			{
				$name=I('post.name');
			}else {
				$this->error('货名不能为空！');
			}
			//检查参数是否含有特殊字符
			$data_n = filterString($name);
			if($data_n == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			if(I('post.package'))
			{
				$package=I('post.package');
			}else {
				$this->error('包装不能为空！');
			}
			//检查参数是否含有特殊字符
			$data_p = filterString($package);
			if($data_p == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			if(I('post.number'))
			{
				$po=I('post.number');
			}else {
				$this->error('件数不能为空！');
			}
			//检查参数是否含有特殊字符
			$data_po = filterString($po);
			if($data_po == false)
			{
				$this->error("不能含有特殊字符");
				exit();
			}
			
			// 检验目的港名称是否正确
			$port = new \Common\Model\PortModel();
			$port_name = I ( 'post.port_name' );
			$data_l = filterString ( $port_name );
			if ($data_l == false) {
				$this->error ( '目的港不能含有特殊字符' );
				exit ();
			}
			$res_l = $port->where ( "name='$port_name'" )->field ( 'id' )->find ();
			if ($res_l ['id'] == '') {
				$this->error ( '目的港不存在！' );
			} else {
				$port_id = $res_l ['id'];
			}
			$data=array(
					'blno'=>$blno,
					'crgno'=>I('post.crgno'),
					'name'=>$name,
					'number'=>I('post.number'),
					'package'=>$package,
					'mark'=>I('post.mark'),
					'totalweight'=>I('post.totalweight'),
					'dangerlevel'=>I('post.dangerlevel'),
					'totalvolume'=>I('post.totalvolume'),
					'po'=>$po,
					'remark'=>I('post.remark'),
					'last_operator'=>$_SESSION['uid'],
					'last_operationtime'=>date('Y-m-d H:i:s'),
					'port_id'   => $port_id
			);
			if(!$instructioncargo->create($data))
			{
				//对data数据进行验证
				$this->error($instructioncargo->getError());
			}else{
				//验证通过 可以对数据进行操作
				$res=$instructioncargo->where("id=$id")->save($data);
				if($res!==false)
				{
					echo '<script>alert("修改指令配货成功!");top.location.reload(true);window.close();</script>';
				}else {
					$this->error("操作失败");
				}
			}
		}else {
			$this->display();
		}
	}
	//删除配货
	public function del($id)
	{
		layout(false);
		$sql="select i.status from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_cargo c where i.id=c.instruction_id and c.id='$id'";
		$res_i=M()->query($sql);
		$status = $res_i[0]['status'];
		if ($status == '0') 
		{
			$instructioncargo = new \Common\Model\CfsInstructionCargoModel ();
			$res = $instructioncargo->where ( "id='$id'" )->delete ();
			if ($res !== false) 
			{
				$this->success ( '删除配货成功！' );
			} else {
				$this->error ( '操作失败！' );
			}
		}else{
			$this->error("不能删除此配货，指令已开始作业");
		}
	}
}