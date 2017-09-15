<?php
/**
 * 起驳装箱业务类
 * 单证管理类
 */

namespace Common\Model;
use Think\Model;

class QbzxProveModel extends Model
{
	public $ERROR_CODE_COMMON =array();     // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();  // 公共返回码中文描述
	public $ERROR_CODE_DOCUMENT =array();       // 单证管理返回码
	public $ERROR_CODE_DOCUMENT_ZH =array();    // 单证管理返回码中文描述
	
	//初始化
	protected function _initialize()
	{
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_DOCUMENT = json_decode(error_code_document,true);
		$this->ERROR_CODE_DOCUMENT_ZH = json_decode(error_code_document_zh,true);
	}
	//验证规则
	protected $_validate = array(
			array('ctn_id','require','箱不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('ctn_id','is_positive_int','箱子不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('ctnno','require','箱号不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('ctnno',array(11),'箱号固定长度为11位',self::EXISTS_VALIDATE,'in'),//存在即验证 长度等于11位
			array('ship_id','require','船舶不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('ship_id','is_positive_int','船舶不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('location_id','require','作业地点不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('lcoation_id','is_positive_int','作业地点不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('ship_name','require','船舶名称不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('ship_name','1,100','船舶名称长度不能超过100个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过100个字符
			array('location_name','require','作业场地名称不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('location_name','1,30','作业场地名称长度不能超过30个字符',self::EXISTS_VALIDATE,'length'),//存在即验证，长度不能超过30个字符
			array('voyage','require','航次不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('voyage','1,20','航次长度不能超过20个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过20个字符
			array('total_ticket','require','总票数不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('total_ticket','is_positive_int','总票数必须为正整数',self::EXISTS_VALIDATE,'function'),//	存在即验证 必须为正整数
			array('total_package','require','总件数不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('total_package','is_positive_int','总件数必须为正整数',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('flflag','require','整拼标志不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('flflag',array('F','L'),'请选择整拼标志',self::EXISTS_VALIDATE,'in'),//存在即验证 F：整箱 L：拼箱
			array('ctn_type_code','require','箱型尺寸不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('ctn_type_code','1,10','箱型尺寸长度不能超过10个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过10个字符
			array('ctn_master','require','箱主不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('ctn_master','1,30','箱主长度不能超过30个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过3讴歌字符
			array('loadingtype','require','装箱方式不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('loadingtype',array('0','1'),'请选择装箱方式',self::EXISTS_VALIDATE,'in'),//存在即验证 0.人工 1.机械
			array('total_weight','require','总重量不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('total_weight','is_decimal','总重量是否带两位小数',self::EXISTS_VALIDATE,'function'),//存在即验证 带两位小数
			array('empty_weight','require','空箱重量不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('empty_weight','is_decimal','空箱重量是否带两位小数',self::EXISTS_VALIDATE,'function'),//存在即验证 带两位小数
			array('cargo_weight','require','货重量不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('cargo_weight','is_decimal','货重量是否带两位小数',self::EXISTS_VALIDATE,'function'),//存在即验证 带两位小数
			array('dangerlevel','require','危险品等级不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('dangerlevel','1,100','危险品等级长度不能超过100个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过100个字符
			array('sealno','require','铅封号不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('sealno','1,100','铅封号长度不能超过100个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过100个字符
			array('level_num','require','关数不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('level_num','is_positive_int','关数必须为正整数',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('damage_num','require','残损数不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('damage_num','is_positive_int','残损数必须为正整数',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('barge_ship_content','1,500','驳船统计内容不能超过500个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过500个字符
			array('location_content','1,500','来源场地统计内容不能超过500个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过500个字符
			array('car_content','1,500','接车统计内容不能超过500个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过500个字符
			array('operator_id','require','操作人不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('operator_id','is_positive_int','操作人不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
			array('operator_name','require','操作人姓名不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('operator_name','1,20','操作人姓名长度不能超过20个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过20个字符
			array('content','require','内容不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('content','1,1000','内容长度不能超过1000个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过1000个字符
			array('remark','1,1000','备注长度不能超过1000个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过1000个字符
			array('consignee','1,20','对接人长度不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
			array('createtime','require','完成时间不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
			array('createtime','is_datetime','完成时间必须为日期时间格式',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为日期时间格式
	);
	/**
	 * 获取单证详情
	 * @param int $id:单证ID
	 * @return array|boolean
	*/
	public function getDocumentMsg($id)
	{
		$res = $this->where("id='$id'")->find();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 根据箱ID获取单证详情
	 * @param int $ctn_id:箱ID
	 * @return array|boolean
	 */
	public function getDocumentMsgByCtn($ctn_id)
	{
		$res = $this->where("ctn_id='$ctn_id'")->find();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 判断单证是否已存在
	 * @param int $ctn_id:箱ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function is_exist($ctn_id)
	{
		$res_exist = $this->where("ctn_id='$ctn_id'")->field('id')->find ();
		if ($res_exist['id'])
		{
			$res=array(
					'code'=>$this->ERROR_CODE_DOCUMENT['DOCUMENT_ALREADY_EXIST'],
					'msg'=>$this->ERROR_CODE_DOCUMENT_ZH[$this->ERROR_CODE_DOCUMENT['DOCUMENT_ALREADY_EXIST']]
			);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'可以生成'
			);
		}
		return $res;
	}
	
	/**
	 * 生成起泊装箱单证
	 * @param int $ctn_id:箱ID
	 * @param string $remark:备注
	 * @return boolean
	 */
	public function generateDocumentByQbzx($ctn_id,$remark)
	{
		$res_prove = $this->is_exist($ctn_id);
		if($res_prove['code'] == '0')
		{
			// ①根据箱ID查找起驳作业指令箱表，得到数据
			$container = new \Common\Model\QbzxInstructionCtnModel();
			$res_ctn = $container->where("id='$ctn_id'")->find();
			if ($res_ctn)
			{
				// 箱主名
				$cmaster = new \Common\Model\ContainerMasterModel ();
				$res_cm = $cmaster->where ( "id='" . $res_ctn ['ctn_master'] . "'" )->field ( 'ctn_master' )->find ();
				// 指令ID
				$instruction_id = $res_ctn ['instruction_id'];
				
				// ②根据指令ID查找起驳作业指令表，得到数据
				$instruction = new \Common\Model\QbzxInstructionModel ();
				$res_i = $instruction->where ( "id='$instruction_id'" )->find ();
				
				// 获取作业场地名
				$location = new \Common\Model\LocationModel ();
				$res_l = $location->where ( "id='" . $res_i ['location_id'] . "'" )->field ( 'location_name' )->find ();
				
				// 预报计划编号
				$plan_id = $res_i ['plan_id'];
				// ③根据预报计划编号查找预报计划表，得到数据
				$plan = new \Common\Model\QbzxPlanModel ();
				$res_p = $plan->where ( "id=$plan_id" )->find ();
				// 获取船名
				$ship = new \Common\Model\ShipModel ();
				$res_s = $ship->where ( "id='" . $res_p ['ship_id'] . "'" )->field ( 'ship_name' )->find ();
				
				// ④根据预报计划编号查找预报计划货物表，得到数据
				$cargo = new \Common\Model\QbzxPlanCargoModel ();
				$data_c = $cargo->where ( "plan_id=$plan_id" )->field ( 'dangerlevel' )->select ();
				// 危险品级别
				foreach ( $data_c as $dl ) 
				{
					if ($dl)
						$res ['dangerlevel'] .= $dl ['dangerlevel'] . ',';
				}
				// ⑤根据箱ID查找起驳作业表，得到数据
				$operation = new \Common\Model\QbzxOperationModel ();
				$data_o = $operation->where ( "ctn_id=$ctn_id" )->find ();
				
				// 根据箱ID获取作业ID
				$operation_id = $data_o ['id'];
				
				// ⑥根据作业ID、业务系统查找起驳作业明细表，得到数据
				$level = new \Common\Model\QbzxOperationLevelModel ();
				$data_l = $level->field ( 'sum(cargo_number),sum(damage_num) as damaged_quantity,count(id) as level_num,operator_id' )->where ( "operation_id=$operation_id" )->order ( 'id desc' )->find ();
				// 获取操作人名称
				$user = new \Common\Model\UserModel ();
				$res_u = $user->where ( "uid='" . $data_l ['operator_id'] . "'" )->field ( 'user_name' )->find ();
				
				$sql = "select billno,sum(cargo_number) as cargo_number,sum(damage_num) as damage_number from __PREFIX__qbzx_operation_level where operation_id=$operation_id group by billno";
				$data_l2 = M ()->query ( $sql );
				$c_num = count ( $data_l2 );
				
				$data = array (
						'ctn_id' => $ctn_id,
						'ctnno' => $res_ctn ['ctnno'],
						'ship_id' => $res_p ['ship_id'],
						'ship_name' => $res_s ['ship_name'],
						'voyage' => $res_p ['voyage'],
						'ctn_type_code' => $res_ctn ['ctn_type_code'],
						'ctn_master' => $res_cm ['ctn_master'],
						'loadingtype' => $res_i ['loadingtype'],
						'location_id' => $res_i ['location_id'],
						'location_name' => $res_l ['location_name'],
						'total_weight' => $data_o ['empty_weight'] + $data_o ['cargo_weight'],
						'empty_weight' => $data_o ['empty_weight'],
						'cargo_weight' => $data_o ['cargo_weight'],
						'dangerlevel' => substr ( $res ['dangerlevel'], 0, - 1 ),
						'sealno' => $data_o ['sealno'],
						'level_num' => $data_l ['level_num'],
						'damage_num' => $data_l ['damaged_quantity'],
						'operator_id' => $data_l ['operator_id'],
						'operator_name' => $res_u ['user_name'],
						'remark' => $remark,
						'createtime' => date ( 'Y-m-d H:i:s' ) 
				);
				
				// 整拼标志
				if ($c_num > 1) 
				{
					$data ['flflag'] = 'L';
				} else {
					$data ['flflag'] = 'F';
				}
				$a = 0;
				for($i = 0; $i < $c_num; $i ++) 
				{
					// 提单号
					$content [$i] ['billno'] = $data_l2 [$i] ['billno'];
					$cargolist = $cargo->where ( "billno='" . $data_l2 [$i] ['billno'] . "'" )->find ();
					// 包装
					$content [$i] ['package'] = $cargolist ['pack'];
					// 标志
					$content [$i] ['mark'] = $cargolist ['mark'];
					// 货物件数
					$content [$i] ['cargo_unit'] = $data_l2 [$i] ['cargo_number'];
					$a = $a + $content [$i] ['cargo_unit'];
					// 残损件数
					$content [$i] ['damage_unit'] = $data_l2 [$i] ['damage_number'];
				}
				$data ['content'] = json_encode ( $content, JSON_UNESCAPED_UNICODE );
				// 总票数
				$data ['total_ticket'] = count ( $content );
				// 总件数
				$data ['total_package'] = $a;
				// 总重量
				$data ['total_weight'] = $data ['empty_weight'] + $data ['cargo_weight'];
				
				// 根据作业ID分驳船统计内容
				$sql_s = "select ship_id,sum(cargo_number) as cargo_num,sum(damage_num) as damage_num from __PREFIX__qbzx_operation_level where operation_id=$operation_id and ship_id!='' group by ship_id";
				$data_s = M ()->query ( $sql_s );
				$s_num = count ( $data_s );
				for($i = 0; $i < $s_num; $i ++) 
				{
					// 船ID
					$ship_content [$i] ['ship_id'] = $data_s [$i] ['ship_id'];
					// 货物件数
					$ship_content [$i] ['cargo_unit'] = $data_s [$i] ['cargo_num'];
					// 残损件数
					$ship_content [$i] ['damage_unit'] = $data_s [$i] ['damage_num'];
				}
				$data ['barge_ship_content'] = json_encode ( $ship_content, JSON_UNESCAPED_UNICODE );
				// 根据作业ID分场地统计内容
				$sql_location = "select location_id,sum(cargo_number) as cargo_num,sum(damage_num) as damage_num from __PREFIX__qbzx_operation_level where operation_id=$operation_id  and location_id!='' group by location_id";
				$data_location = M ()->query ( $sql_location );
				$location_num = count ( $data_location );
				for($i = 0; $i < $location_num; $i ++) 
				{
					// 场地ID
					$location_content [$i] ['location_id'] = $data_location [$i] ['location_id'];
					// 货物件数
					$location_content [$i] ['cargo_unit'] = $data_location [$i] ['cargo_num'];
					// 残损件数
					$location_content [$i] ['damage_unit'] = $data_location [$i] ['damage_num'];
				}
				$data ['location_content'] = json_encode ( $location_content, JSON_UNESCAPED_UNICODE );
				
				// 根据作业ID分接车统计内容
				$sql_car = "select sum(cargo_number) as cargo_num,sum(damage_num) as damage_num from __PREFIX__qbzx_operation_level where operation_id=$operation_id and car!='N' group by car";
				$data_car = M ()->query ( $sql_car );
				$car_num = count ( $data_car );
				for($i = 0; $i < $car_num; $i ++) 
				{
					// 接车ID
					$car_content [$i] ['car'] = $data_car [$i] ['car'];
					// 货物件数
					$car_content [$i] ['cargo_unit'] = $data_car [$i] ['cargo_num'];
					// 残损件数
					$car_content [$i] ['damage_unit'] = $data_car [$i] ['damage_num'];
				}
				$data ['car_content'] = json_encode ( $car_content, JSON_UNESCAPED_UNICODE );
				if ($this->create ( $data )) 
				{
					// 对data数据进行验证
					$res = array (
							'code' => $this->ERROR_CODE_COMMON ['PARAMENT_ERROR'],
							'msg' => $this->getError () 
					);
				} else {
					$result = $this->add ( $data );
					if ($result !== false) {
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['SUCCESS'],
								'msg' => '成功' 
						);
					} else {
						$res = array (
								'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
								'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
						);
					}
				}
			} else {
				$res = array (
						'code' => $this->ERROR_CODE_COMMON ['DB_ERROR'],
						'msg' => $this->ERROR_CODE_COMMON_ZH [$this->ERROR_CODE_COMMON ['DB_ERROR']] 
				);
			}
		}else{
			$res = array (
					'code' => $this->ERROR_CODE_DOCUMENT ['DOCUMENT_ALREADY_EXIST'],
					'msg' => $this->ERROR_CODE_DOCUMENT_ZH [$this->ERROR_CODE_DOCUMENT ['DOCUMENT_ALREADY_EXIST']]
			);
		}
		return $res;
	}
}