<?php

/**
 * APP接口
 * 查询接口
 * @author 殷根朋  2016-8-23
 */

namespace App\Controller;
use App\Common\BaseController;

header ( "Access-Control-Allow-Origin: *" );

class CfsSearchController extends BaseController
{
	/**
	 * 指令查询
	 * @param  location_name 装箱地点
	 * @param  status 作业状态
	 * @param  date 指令日期
	 * @param  vslname 中文船名
	 * @param  voyage 船次
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param result:成功时返回指令查询结果列表
	 */
	public function instruction() 
	{
		$where='1';
		if (I ( 'post.location_name' )) 
		{
			$location_name = I ( 'post.location_name' );
			// 根据理货地点获取理货地点ID
			$location = new \Common\Model\LocationModel();
			$res_l = $location->where ( "location_name='$location_name'" )->field ( 'id' )->find ();
			$location_id = $res_l ['id'];
			$where .= " and i.location_id='$location_id'";
		}
		if (I ( 'post.status' ) !== '') 
		{
			$status = I ( 'post.status' );
			$where .= " and i.status='$status'";
		}
		if (I ( 'post.date' )) 
		{
			$date = I ( 'post.date' );
			$where .= " and i.date='$date'";
		}
		if (I ( 'post.ship_name' )) 
		{
			$ship_name = I ( 'post.ship_name' );
			// 根据船名获取船ID
			$ship = new \Common\Model\ShipModel();
			$res_s = $ship->where ( "ship_ame='$ship_name'" )->field ( 'id' )->find ();
			$ship_id = $res_s ['id'];
			$where .= " and i.ship_id='$ship_id'";
		}
		if (I ( 'post.voyage' )) 
		{
			$voyage = I ( 'post.voyage' );
			$where .= " and i.voyage='$voyage'";
		}
		$sql = "select i.*,s.ship_name,l.location_name,d.department_name from __PREFIX__cfs_instruction i,__PREFIX__ship s,__PREFIX__department d,__PREFIX__location l where i.ship_id=s.id and i.location_id=l.id and i.department_id=d.id and $where order by id desc limit 0,50";
		$list = M ()->query ( $sql );
		if ($list !== false) 
		{
			// 新增状态对应的文字描述
			$num = count ( $list );
			for($i = 0; $i < $num; $i ++) 
			{
				// 拆箱方式
				$operate_contanier_method_d = json_decode ( operate_contanier_method_d, true );
				$operation_type_zh = $operate_contanier_method_d [$list[$i]['operation_type']];
				$list[$i]['operation_type_zh'] = $operation_type_zh;
				// 指令状态
				$instruction_status_d = json_decode ( instruction_status_d, true );
				$status_zh = $instruction_status_d [$list[$i]['status']];
				$list[$i]['status_zh'] = $status_zh;
			}
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
					'msg' => '成功',
					'list' => $list 
			);
		} else {
			//数据库连接错误
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list' => '' 
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}
	
	/**
	 * 作业查询
	 * @param ctnno 箱号
	 * @param vslname 中文船名
	 * @param voyage 航次
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param ctn_msg:成功时返回符合条件的箱信息
	 * @return @param operation_msg:成功时返回符合条件的箱的作业信息
	 * @return @param list:成功时返回符合条件的箱的关列表
	 */
	public function Operation()
	{
		if(I('post.ctnno') and I('post.vslname') and I('post.voyage'))
		{
			$ctnno=I('post.ctnno');
			$ship_name=I('post.vslname');
			//根据船名获取船ID
			$ship = new \Common\Model\ShipModel();
			$res_s = $ship->where("ship_name='$ship_name'")->field('id')->find();
			$ship_id = $res_s['id'];
			$voyage=I('post.voyage');
			$sql = "select c.* from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_ctn c where i.ship_id='$ship_id' and i.voyage='$voyage' and c.ctnno='$ctnno' and c.instruction_id=i.id";
			$res_c=M()->query($sql);
			if($res_c !== false)
			{
				$ctn_msg=$res_c[0];
				if($ctn_msg == null)
				{
					$ctn_msg = '';
				}
				$ctn_id = $res_c[0]['id'];
				$sql = "select o.*,u.user_name from __PREFIX__cfs_operation o,__PREFIX__user u where o.ctn_id='$ctn_id'and o.operator_id=u.uid";
				$res_o= M()->query($sql);
				$operation_msg = $res_o[0];
				if($operation_msg['id']!='')
				{
					$operation_id=$operation_msg['id'];
					//获取关列表
					$level=new \Common\Model\CfsOperationLevelModel();
					$list=$level->where("operation_id='$operation_id'")->field('num,damage_num,level_num')->select();
					if ($operation_msg !== '' and $list !== '') 
					{
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg' => '成功',
								'ctn_msg' => $ctn_msg,
								'operation_msg' => $operation_msg,
								'list' => $list 
						);
					} else {
						//数据库连接错误
						$res = array (
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
								'ctn_msg' => '',
								'operation_msg' => '',
								'list' => '' 
						);
					}
				}else {
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'ctn_msg'=>$ctn_msg,
							'operation_msg'=>'',
							'list'=>''
					);
				}
			}else{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'ctn_msg'=>'',
						'operation_msg'=>'',
						'list'=>''
				);
			}
		}else{
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 单证查询
	 * @param string $ctn_no:箱号
	 * @param int $ship_name:船名
	 * @param string $vargo:航次
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param content:成功时返回符合条件的单证列表
	 */
	public function prove()
	{
		$where = '1';
		if(I('post.ctnno'))
		{
			$ctnno=I('post.ctnno');
			$where.=" and p.ctnno = '$ctnno'";
		}
		if(I('post.ship_name'))
		{
			$ship_name = I('post.ship_name');
			$where.=" and p.ship_name = '$ship_name'";
		}
		if(I('post.voyage'))
		{
			$vargo=I('post.voyage');
			$where.=" and p.voyage = '$voyage'";
		}
	
		$prove = new \Common\Model\CfsProveModel();
		$list= $prove->where($where)->order("id desc")->limit(0,50)->select();
	
		if($list!==false)
		{
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'list'=>$list
			);
		}else {
			//数据库连接错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
					'list'=>''
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 获取单证详情
	 * @param int $id:单证ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param content:成功时返回符合条件的单证记录
	 */
	public function getProveMsg()
	{
		if (I ( 'post.prove_id' ))
		{
			$prove_id = I ( 'post.prove_id' );
			$prove = new \Common\Model\CfsProveModel();
			$res_c = $prove->where("id='$prove_id'")->find();
			if ($res_c !== false)
			{
				$content=$res_c;
				$content['content']=json_decode($res_c['content']);
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg' => '成功',
						'content' => $content
				);
			} else {
				//数据库连接错误
				$res = array (
						'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],
						'content'=>''
				)
				;
			}
		} else {
			//参数不正确，参数缺失
			$res = array (
					'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ( $res, JSON_UNESCAPED_UNICODE );
	}

}