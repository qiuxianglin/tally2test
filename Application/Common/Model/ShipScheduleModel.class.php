<?php
/**
 * 基础类
 * 船期维护类
 */
namespace Common\Model;
use Think\Model;

class ShipScheduleModel extends Model
{
	//验证规则
	protected $_validate = array(
	
	);
	
	/**
	 * 获取船期信息
	 */
	public function getMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		if($msg)
		{
			//查询起运港、目的港名称
			$port=new \Common\Model\PortModel();
			if($msg['loading_port'])
			{
				$res_p=$port->getPortMsg($msg['loading_port']);
				if($res_p!==false)
				{
					$msg['loading_port_name']=$res_p['name'];
				}
			}
			if($msg['destination_port'])
			{
				$res_p=$port->getPortMsg($msg['destination_port']);
				if($res_p!==false)
				{
					$msg['destination_port_name']=$res_p['name'];
				}
			}
			return $msg;
		}else {
			return false;
		}
	}
}