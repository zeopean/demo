<?php

function getVhost()
{
	$file 	= fopen('hosts.log' ,'r');
	$arr 	= array();
	static $flag = 1 ;
	while(!feof($file))
	{	
		$line 	= fgets($file);
		$string = preg_replace('/[\s]+/', ',', $line);
		$tmp 	= explode(',', $string); 
		$arr[$flag]['ip'] 	= $tmp[0];
		$arr[$flag]['host']	= $tmp[1];
		$flag++;
	}
	return $arr;
}


// 利用curl 实现ip伪造
function getLocationByMobile($arr=array(),$mobile="")
{	
	$apiurl = 'http://apis.juhe.cn/mobile/get';
	$params = array(
	  'key' => 'b4b88a8ffc09e2fd3f24251ee19fa168', 	//您申请的手机号码归属地查询接口的appkey
	  'phone' => $mobile 							//要查询的手机号码
	);

	if(!empty($arr)){
		$res = array();
		foreach($arr as $key=>$value){
			$cip = $value['ip']; 
			$xip = $value['ip'];

			$binfo =array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)','Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0','Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET4.0C; Alexa Toolbar)','Mozilla/4.0(compatible; MSIE 6.0; Windows NT 5.1; SV1)',$_SERVER['HTTP_USER_AGENT']);
			$header = array( 
						"CLIENT-IP:$cip", 
						"X-FORWARDED-FOR:$xip", 
					);
			

			//初始化
		    $curl = curl_init();
		    //设置抓取的url
		    curl_setopt($curl, CURLOPT_URL, $apiurl);
		    //伪造头信息
		    curl_setopt ($curl, CURLOPT_HTTPHEADER, $header);
		    curl_setopt ($curl, CURLOPT_USERAGENT, $binfo[mt_rand(0,3)]);

		    //设置获取的信息以文件流的形式返回，而不是直接输出。
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    //设置post方式提交
		    curl_setopt($curl, CURLOPT_POST, 1);
		    // post 传参数
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		    //执行命令
		    $data 	= curl_exec($curl);
		    // "province":"广东","city":"广州","areacode"
		    $pos1 	= strpos($data , '"province":');
		    $pos2 	= strpos($data , ',"areacode"');
		    // 截取字符串
		    $length = $pos2 - $pos1-1 ;
		    $data	= substr($data , $pos1+1 , $length);
		    // 字符串转数组
		    $tmp 	= explode(',', $data);
		    $tmpArr = array();
		    foreach($tmp as $key=>$val){
		    	$arr = explode(':' , $val);
		    	unset($arr[0]);// 去除省
		    	$tmpArr = $arr[1];
		    }
		    $res[] 	= $tmpArr;
		    //关闭URL请求
		    curl_close($curl);


			unset($arr[$key]);
		}
		var_dump($arr,'hello');
		return $res;
	}
}

$arr = getVhost(); 
$res = getLocationByMobile($arr, '18665746627');
print_r($res);
