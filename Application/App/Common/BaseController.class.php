<?php
namespace App\Common;
use Think\Controller;
use Think\Auth;

class BaseController extends Controller 
{
	public $ERROR_CODE_COMMON =array();         // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();      // 公共返回码中文描述
	public $ERROR_CODE_USER =array();           // 用户相关返回码
	public $ERROR_CODE_USER_ZH =array();        // 用户相关返回码中文描述
	public $ERROR_CODE_SHIFT =array();          // 工班管理返回码
	public $ERROR_CODE_SHIFT_ZH =array();       // 工班管理返回码中文描述
	public $ERROR_CODE_DEPARTMENT =array();     // 部门管理返回码
	public $ERROR_CODE_DEPARTMENT_ZH =array();  // 部门管理返回码中文描述
	public $ERROR_CODE_CUSTOMER =array();       // 客户管理返回码
	public $ERROR_CODE_CUSTOMER_ZH =array();    // 客户管理返回码中文描述
	public $ERROR_CODE_SHIP =array();           // 船舶管理返回码
	public $ERROR_CODE_SHIP_ZH =array();        // 船舶管理返回码中文描述
	public $ERROR_CODE_LOCATION =array();       // 作业地点管理返回码
	public $ERROR_CODE_LOCATION_ZH =array();    // 作业地点管理返回码中文描述
	public $ERROR_CODE_INSTRUCTION =array();    // 指令管理返回码
	public $ERROR_CODE_INSTRUCTION_ZH =array(); // 指令管理返回码中文描述
	public $ERROR_CODE_OPERATION =array();      // 作业管理返回码
	public $ERROR_CODE_OPERATION_ZH =array();   // 作业管理返回码中文描述
	public $ERROR_CODE_DOCUMENT =array();       // 单证管理返回码
	public $ERROR_CODE_DOCUMENT_ZH =array();    // 单证管理返回码中文描述
	
	//初始化
	protected function _initialize()
	{
		// 返回码配置
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_USER = json_decode(error_code_user,true);
		$this->ERROR_CODE_USER_ZH = json_decode(error_code_user_zh,true);
		$this->ERROR_CODE_SHIFT = json_decode(error_code_shift,true);
		$this->ERROR_CODE_SHIFT_ZH = json_decode(error_code_shift_zh,true);
		$this->ERROR_CODE_DEPARTMENT = json_decode(error_code_department,true);
		$this->ERROR_CODE_DEPARTMENT_ZH = json_decode(error_code_department_zh,true);
		$this->ERROR_CODE_CUSTOMER = json_decode(error_code_department,true);
		$this->ERROR_CODE_CUSTOMER_ZH = json_decode(error_code_department_zh,true);
		$this->ERROR_CODE_SHIP = json_decode(error_code_ship,true);
		$this->ERROR_CODE_SHIP_ZH = json_decode(error_code_ship_zh,true);
		$this->ERROR_CODE_LOCATION = json_decode(error_code_location,true);
		$this->ERROR_CODE_LOCATION_ZH = json_decode(error_code_location_zh,true);
		$this->ERROR_CODE_INSTRUCTION = json_decode(error_code_instruction,true);
		$this->ERROR_CODE_INSTRUCTION_ZH = json_decode(error_code_instruction_zh,true);
		$this->ERROR_CODE_OPERATION = json_decode(error_code_operation,true);
		$this->ERROR_CODE_OPERATION_ZH = json_decode(error_code_operation_zh,true);
		$this->ERROR_CODE_DOCUMENT = json_decode(error_code_document,true);
		$this->ERROR_CODE_DOCUMENT_ZH = json_decode(error_code_document_zh,true);
		
		// 权限过滤
	}
}