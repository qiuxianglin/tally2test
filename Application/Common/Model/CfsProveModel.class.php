<?php
/**
 * 起驳装箱业务类
 * 单证管理类
 */

namespace Common\Model;
use Think\Model;

class CfsProveModel extends Model
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
	 * 生成CFS装箱单证
	 * @param int $ctn_id:箱ID
	 * @return boolean
	 */
	public function generateDocumentByCfs($ctn_id,$remark)
	{
		$container=new \Common\Model\CfsInstructionCtnModel();
		$containerMsg=$container->getContainerMsg($ctn_id);
		//箱型尺寸
		$cube=$containerMsg[0]['ctn_size'];
		//根据箱ID获取作业ID
		$operation=new \Common\Model\CfsOperationModel();
		$res_o=$operation->where("ctn_id=$ctn_id")->field('id')->find();
		$operation_id=$res_o['id'];
		//根据箱ID获取作业详情
		$operationMsg = $operation->where("ctn_id='$ctn_id'")->find();
		//计算总重量
		$totalweight = $operationMsg['empty_weight'] + $operationMsg['cargo_weight'];
		//铅封号
		$seal_no=$operationMsg['sealno'];
		//计算实际作业中的货物总件数和关数
		$sql="select l.blno,c.dangerlevel,count(l.id) as sling,sum(l.num) as units,sum(l.damage_num) as damage_unit  from __PREFIX__cfs_operation_level l,__PREFIX__cfs_instruction_cargo c where l.operation_id='$operation_id' and l.blno=c.blno  group by l.blno";
		$res_total=M()->query($sql);
		$votes = count($res_total);
		//总关数
		$sling=$res_total[0]['sling'];
		//总件数
		$units=$res_total[0]['units'];
		//残损件数
		$damage_unit=$res_total[0]['damage_unit'];
		//根据指令ID获取指令详情
		$instruction_id=$containerMsg[0]['instruction_id'];
		$instruction=new \Common\Model\CfsInstructionModel();
		$instructionMsg=$instruction->getInstructionMsg($instruction_id);
        //船ID
		$ship_id=$instructionMsg['ship_id'];
        //装箱地点ID
		$location_id=$instructionMsg['location_id'];
		//内容
		$a = 0;
		for($i=0;$i < $votes;$i++){
		//提单号
				$content [$i] ['blno'] = $res_total [$i] ['blno'];
				$instruction_cargo = new \Common\Model\CfsInstructionCargoModel();
				$cargo = $instruction_cargo->where ( "blno='" . $res_total [$i] ['blno'] . "'" )->find ();
				//包装
				$content [$i] ['package'] = $cargo ['package'];
				//标志
				$content [$i] ['mark'] = $cargo ['mark'];
				//货物件数
				$content [$i] ['cargo_unit'] = $res_total [$i] ['units'];
				$a = $a + $content [$i] ['cargo_unit'];
				//残损件数
				$content [$i] ['damage_unit'] = $res_total [$i] ['damage_unit'];
				//危险品等级
				$danger_rank .= $res_total [$i] ['dangerlevel'];
		}
		$content=json_encode($content);
		//$danger_rank=json_encode($danger_rank);
		$data = array (
				'ctn_id' => $ctn_id,
				'ctnno' => $containerMsg[0]['ctnno'],      // 箱号
				'ship_id' => $ship_id,                          // 集装箱船ID
				'ship_name'=>$instructionMsg['ship_name'],   //船名
				'voyage' => $instructionMsg['voyage'],           // 航次
				'total_ticket' => $votes,                             // 总票数
				'total_package' => $units,                        // 总件数
				'flflag' => $containerMsg[0]['lcl'],         // 整拼标志 Y整箱 N拼箱
				'ctn_type_code' => $cube,                          // 箱型尺寸
				'ctn_master' => $containerMsg[0]['ctn_master'], // 箱主ID
				'loadingtype' => $instructionMsg ['operation_type'], // 拆箱方式 0人工 1机械
				'location_id' => $location_id,                  // 作业场地ID
				'location_name'=>$instructionMsg['location_name'],//作业场地名
				'total_weight' => $totalweight,    // 总重量
				'empty_weight' => $operationMsg['empty_weight'],  // 空箱重量
				'cargo_weight' => $operationMsg['cargo_weight'],  // 货物重量
				'dangerlevel' => $danger_rank, // 危险等级
				'sealno' => $seal_no,                    // 铅封号
				'level_num' => $sling,                        // 关数
				'damage_num' => $damage_unit,       // 残损数量
				'operator_id' => $containerMsg[0]['operator_id'], // 操作人
				'operator_name'=>$containerMsg[0]['cmaster'],//操作人名称
				'content' => $content,                    // 内容
				'remark' => $remark,                      // 备注
				'consignee' => '',    // 对接人                 
				'createtime' => date ( 'Y-m-d H:i:s' )
		);
		$res=$this->add($data);
		if($res!==false)
		{
			return true;
		}else {
			return false;
		}
	}
}
