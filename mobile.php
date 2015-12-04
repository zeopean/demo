<?php 
/**
 * 
 * @author zeopean
 * @date 2015-12-02 22:44:01
 */

//----------------------------------
// 聚合数据-手机号码归属地查询API
//----------------------------------


function getLocationByMobile($mobile)
{
	$apiurl = 'http://apis.juhe.cn/mobile/post';
	$params = array(
	  'key' => 'b4b88a8ffc09e2fd3f24251ee19fa168', 	//您申请的手机号码归属地查询接口的appkey
	  'phone' => $mobile 							//要查询的手机号码
	);
	 
	$paramsString = http_build_query($params);
	 
	$content = @file_get_contents($apiurl.'?'.$paramsString);
	$result = json_decode($content,true);
	if($result['error_code'] == '0'){
		$res['location']	= $result['result']['city'].'市';
	   	return $res;
	}else{
	    return array();
	}

}

$mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '18665746640';
return  getLocationByMobile($mobile);