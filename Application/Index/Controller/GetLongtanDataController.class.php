<?php
/**
 * 门到门拆箱-预报计划配货
 */
namespace Index\Controller;
use Index\Common\BaseController;
header("Content-Type:text/html;charset=utf-8");
class GetLongtanDataController extends BaseController
{

	// 权限配置 
	public function serach(){
		$config = array (  
                'db_type' => 'sqlsrv', //采用pdo方式连接  
                'db_user' => 'sa',  
                'db_pwd' => 'xggx',  
                'db_charset' => 'utf8',  
                'DB_DSN' => 'sqlsrv:Server=192.168.32.2;Database=ctsdb',  
        );
        $sql = "Select* from OPENQUERY(LT,’select* from ACTOMS.temp_cfs_report_tb ‘)";

    	$data = M("", "", $config)->query($sql);
    	var_dump($data);
	}
	public function dealwith($data){
		$data = ltrim(rtrim($data,']'),'[');
		$res = explode('},', $data);
		for ($i=0; $i < count($res)-1; $i++) { 
			$res[$i] .= '}';
		}
		$res[count($res)-1] = str_replace(']', '', $res[count($res)-1]);
		$res[count($res)-1] = rtrim($res[count($res)-1],'  ');
		var_dump($res[3074].'"');exit;
		var_dump(json_decode($res[3074]));exit;
		foreach ($res as $k => $v) {
			$res[$k] = json_decode($v,TRUE);
		}
		return $res;
	}

	public function curl_request($url,$post='',$cookie='', $returnCookie=0){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
	}
}