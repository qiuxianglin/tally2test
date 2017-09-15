<?php
namespace Common\Common;

define(_LOG_FILE_NAME, "./Application/Runtime/Logs/info.log");
class LogController{


	public static function Info($info){
		$time = date('Y-m-d H:i:s', time());
		$str = $time."  [INFO]: \r\n".$info."  \r\n";
		if (!file_exists(_LOG_FILE_NAME)) {
			touch(_LOG_FILE_NAME);
		}
		file_put_contents(_LOG_FILE_NAME, $str, FILE_APPEND);
	}
}