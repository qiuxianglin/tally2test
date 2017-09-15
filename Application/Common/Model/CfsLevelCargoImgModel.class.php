<?php
/**
 * CFS管理业务类
 * 关货物照管理
 */

namespace Common\Model;
use Think\Model;

class CfsLevelCargoImgModel extends Model
{
	
	//验证规则
	protected $_validate = array(
			array('level_id','require','关不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('level_id','is_positive_int','关必须为正整数',self::EXISTS_VALIDATE,'function'), //存在验证 ，必须为正整数
			array('level_num','require','关数不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('level_num','is_positive_int','关数必须为正整数',self::EXISTS_VALIDATE,'function'),      //存在即验证，必须为正整数
			array('level_picture','require','残损照片不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('level_picture','1,255','残损照片长度不能超过255个字符',self::EXISTS_VALIDATE,'length'), //存在即验证，长度不能超过255个字符
	);


	// 根据关ID 获取对应的图片信息
	public function getImgByLevelId($level_id){
		$sql = "select * from tally_cfs_level_cargo_img where level_id = $level_id";
		$data = M()->query($sql);
		if(empty($data)){
			return false;
		} else {
			return $data[0]['level_img'];
		}
	}
}