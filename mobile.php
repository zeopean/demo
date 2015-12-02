<?php 
/**
 * 
 * @author zeopean
 * @date 2015-12-02 22:44:01
 */

//----------------------------------
// 聚合数据-手机号码归属地查询API
//----------------------------------


function getLocationByMobile($mobile="18665746639")
{
	$apiurl = 'http://apis.juhe.cn/mobile/get';
	$params = array(
	  'key' => 'b4b88a8ffc09e2fd3f24251ee19fa168', 	//您申请的手机号码归属地查询接口的appkey
	  'phone' => $mobile 							//要查询的手机号码
	);
	 
	$paramsString = http_build_query($params);
	 
	$content = @file_get_contents($apiurl.'?'.$paramsString);
	$result = json_decode($content,true);
	if($result['error_code'] == '0'){
		$res['location']	= $result['result']['province'].$result['result']['city'];
	   	return json_encode($res);
	}else{
	    return $result['reason']."(".$result['error_code'].")";
	}

	/*
    "province":"浙江",
    "city":"杭州",
    "areacode":"0571",
    "zip":"310000",
    "company":"中国移动",
    "card":"移动动感地带卡"
    */
}

var_dump(getLocationByMobile());