<?php
/**
 * 门到门拆箱业务系统
 * 单证管理
 */
namespace Common\Model;
use Think\Model;

class DdProveModel extends Model
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
			array('ctn_id','require','箱子不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('ctn_id','is_positive_int','箱子不存在',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('ctn_no','require','箱号不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('ctn_no','11','箱号不符合国际规范',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度为11个字符
			array('ctn_type_code','1,20','箱型尺寸不超过20个字符',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不能超过20个字符
			array('ctn_master','1,50','箱主名称不超过50个字符',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不能超过50个字符
			array('ship_id','require','集装箱船不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('ship_id','is_positive_int','集装箱船不存在',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('ship_name','require','集装箱船名不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('ship_name','1,150','集装箱船名不超过150个字符',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不能超过150个字符
			array('vargo','require','航次不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('vargo','1,50','航次不超过50个字符',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不能超过50个字符
			array('total_ticket','require','总票数不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('total_ticket','is_positive_int','总票数必须为正整数',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('total_package','require','总件数不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('total_package','is_positive_int','总件数必须为正整数',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('damaged_quantity','is_natural_num','残损件数必须为自然数',self::VALUE_VALIDATE,'function'), //值不为空的时候验证 ，必须为自然数
			array('level_num','require','关数不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('level_num','is_positive_int','关数必须为正整数',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('flflag',array('F','L'),'整拼标志类型不正确',self::VALUE_VALIDATE,'in'), //值不为空的时候验证 ，只能为F整箱 L拼箱
			array('loadingtype',array('0','1'),'拆箱方式类型不正确',self::VALUE_VALIDATE,'in'), //值不为空的时候验证 ，只能为0人工 1机械
			array('location_id','require','作业场地不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('location_id','is_positive_int','作业场地不存在',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('location_name','require','作业场地名称不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('location_name','1,50','作业场地名称不超过50个字符',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不能超过50个字符
			array('empty_weight','currency','空箱重量必须为数字',self::VALUE_VALIDATE),  //值不为空的时候验证，必须为数字
			array('cargo_weight','currency','货物重量必须为数字',self::VALUE_VALIDATE),  //值不为空的时候验证，必须为数字
			array('total_weight','require','总重量不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('total_weight','currency','总重量必须为数字',self::EXISTS_VALIDATE),  //存在即验证，必须为数字
			array('dangerlevel','1,100','危险品等级不超过100个字符',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不能超过100个字符
			array('sealno','require','铅封号不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('sealno','1,20','铅封号不超过20个字符',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不能超过20个字符
			array('operator_id','require','理货员不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('operator_id','is_positive_int','理货员不存在',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('operator_name','require','理货员姓名不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('operator_name','1,20','理货员姓名不超过20个字符',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不能超过20个字符
			array('content','require','单证内容不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('content','1,1000','单证内容不超过1000个字符',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不能超过1000个字符
			array('remark','1,1000','备注不超过1000个字符',self::VALUE_VALIDATE,'length'),  //存在即验证，长度不能超过1000个字符
			array('consignee','1,20','对接人不超过20个字符',self::VALUE_VALIDATE,'length'),  //存在即验证，长度不能超过20个字符
			array('createtime','require','单证创建时间不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('createtime','is_datetime','单证创建时间必须为时间格式',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为时间格式	
	);
	
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
	 * 生成门到门拆箱单证
	 * @param int $ctn_id:箱ID
	 * @return boolean
	 */
	public function generateDocument($ctn_id,$remark)
	{
		//判断单证是否存在
		$res_prove = $this->is_exist($ctn_id);
		if($res_prove['code'] == '0')
		{
			//1:根据箱id获取预报计划配箱表详情
			$DdPlanContainer=new \Common\Model\DdPlanContainerModel();
			$containerMsg=$DdPlanContainer->getContainerMsg($ctn_id);
			//箱型尺寸
			$ctn_type_code=$containerMsg['ctnsize'].$containerMsg['ctntype'];
			//铅封号
			$seal_no=$containerMsg['sealno'];
			//根据箱ID获取作业ID
			$DdOperation=new \Common\Model\DdOperationModel();
			$res_o=$DdOperation->where("ctn_id='$ctn_id'")->field('true_sealno,id')->find();
			$operation_id=$res_o['id'];
			if($res_o['true_sealno'])
			{
				//存在实际铅封号则使用实际铅封号
				$seal_no=$res_o['true_sealno'];
			}
			//计算实际作业中的货物总件数和关数
			$sql="select blno,count(id) as level_num,sum(num) as total_package,sum(damage_num) as damaged_quantity  from __PREFIX__dd_operation_level where operation_id=$operation_id group by blno";
			$res_total=M()->query($sql);
			$c_num = count($res_total);
			//总关数
			$level_num=$res_total[0]['level_num'];
			//总件数
			//$total_package=$res_total[0]['total_package'];
			//残损件数
			//$damaged_quantity=$res_total[0]['damaged_quantity'];
			//获取content内容
			$cargo = new \Common\Model\DdPlanCargoModel();
			
			//根据预报计划ID获取预报计划详情
			$plan_id=$containerMsg['plan_id'];
			$DdPlan=new \Common\Model\DdPlanModel();
			$planMsg=$DdPlan->getPlanMsg($plan_id);
			//根据船名获取集装箱船ID
			$shipname=$planMsg['vslname'];
			$Ship=new \Common\Model\ShipModel();
			$res_ship=$Ship->where("ship_name='$shipname' or ship_english_name='$shipname'")->field('id,ship_name,ship_english_name')->find();
			//集装箱船ID、名称
			$ship_id=$res_ship['id'];
			$ship_name=$res_ship['ship_name'];
			//根据拆箱地点名称获取拆箱地点ID
			$unpackagingplace=$planMsg['unpackagingplace'];
			$Location=new \Common\Model\LocationModel();
			$res_location=$Location->where("location_name='$unpackagingplace'")->field('id,location_name')->find();
			//拆箱地点ID、名称
			$location_id=$res_location['id'];
			$location_name=$res_location['location_name'];
			//根据操作人ID获取操作人姓名
			$user = new \Common\Model\UserModel();
			$username = $user->getUserMsg($containerMsg ['operator_id']);
			
			//$a:总件数；$b:总残损件数；
			$a = 0;
			$b = 0; 
			for($i = 0; $i < $c_num; $i ++)
			{
				// 提单号
				$content [$i] ['blno'] = $res_total [$i] ['blno'];
				$cargolist = $cargo->where ( "blno='" . $res_total [$i] ['blno'] . "'" )->find ();
				// 包装
				$content [$i] ['package'] = $cargolist ['package'];
				// 标志
				$content [$i] ['mark'] = $cargolist ['mark'];
				// 货物件数
				$content [$i] ['cargo_unit'] = $res_total [$i] ['total_package'];
				$a = $a + $content [$i] ['cargo_unit'];
				// 残损件数
				$content [$i] ['damage_unit'] = $res_total [$i] ['damaged_quantity'];
				$b = $b+$content [$i] ['damage_unit'];
				
			}
			$content = json_encode ( $content, JSON_UNESCAPED_UNICODE );
			$data = array (
					'ctn_id' => $ctn_id,
					'ctn_no' => $containerMsg ['ctnno'],            // 箱号
					'ctn_type_code' => $ctn_type_code,              // 箱型尺寸
					'ctn_master' => null,                           // 箱主ID
					'ship_id' => $ship_id,                          // 集装箱船ID
					'ship_name' =>$ship_name,                       // 集装箱船名
					'vargo' => $planMsg ['voyage'],                 // 航次
					'total_ticket' => count ( $content ),           // 总票数
					'total_package' => $a,              			// 总件数
					'damaged_quantity' => $b,        				// 残损数量
					'level_num' => $level_num,                      // 关数
					'flflag' => $containerMsg ['flflag'],           // 整拼标志 F整箱 L拼箱
					'loadingtype' => $planMsg ['operating_type'],   // 拆箱方式 0人工 1机械
					'location_id' => $location_id,                  // 作业场地ID
					'location_name' => $location_name,              // 作业场地名称
					'empty_weight' => 0.00,                         // 空箱重量
					'cargo_weight' => 0.00,                         // 货物重量
					'total_weight' => $containerMsg ['weight'],     // 总重量
					'dangerlevel' => $containerMsg ['classes'],     // 危险等级
					'sealno' => $seal_no,                           // 铅封号
					'operator_id' => $containerMsg ['operator_id'], // 理货员ID
					'operator_name' => $username['user_name'],              // 理货员姓名
					'content' => $content,                          // 内容
					'remark' => $remark,                            // 备注
					'consignee' => $planMsg ['consignee'],          // 对接人
					'createtime'  => date("Y-m-d H:i:s")
			);
			if(!$this->create($data))
			{
				// 验证不通过
				// 参数不正确，参数缺失
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
				);
			}else {
				// 验证通过
				$res=$this->add($data);
				if($res!==false)
				{
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'生成单证成功'
					);
				}else {
					// 数据库操作错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else{
			$res = array (
				'code' => $this->ERROR_CODE_DOCUMENT ['DOCUMENT_ALREADY_EXIST'],
				'msg' => $this->ERROR_CODE_DOCUMENT_ZH [$this->ERROR_CODE_DOCUMENT ['DOCUMENT_ALREADY_EXIST']]
			);
		}
		return $res;
	}
	
	/**
	 * 根据箱ID获取单证详情
	 * @param int $ctn_id:箱ID
	 * @return array|boolean
	 */
	public function getProveMsgByCtn($ctn_id)
	{
		$msg=$this->where("ctn_id='$ctn_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取单证详情
	 * @param int $id:单证ID
	 * @return array|boolean
	 */
	public function getProveMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
}
?>