<?php

//解析搜索条件

function parse_car_preg($url){
    
    $arrSer = array();
    
    $arrUrl = explode('_', $url);
    
    $arrCon = array();
    
    foreach ($arrUrl as $k=>$value){
        //价格
        if(preg_match('/^jg\d{1,3}-\d{1,3}$/', $value)){
            $arr = explode('-', str_replace('jg', '', $value));
            $arrSer['ser']['price'] = $arr[1] == 0 ? array('egt', $arr[0]) : array(array('egt',$arr[0]),array('elt',$arr[1]), 'and');
            $arrSer['url']['jg'] = str_replace('jg', '', $value);
            $arrCon[] = $value;
        }
        //车型
        if(preg_match('/^cx\d{1,2}$/', $value)){
            $arrSer['ser']['level_id'] = str_replace('cx', '', $value);
            $arrSer['url']['cx'] = str_replace('cx', '', $value);
            $arrCon[] = $value;
        }
        //车龄
        if(preg_match('/^cl\d{1}-\d{1}$/', $value)){
            $arr = explode('-', str_replace('cl', '', $value));
            $arrSer['ser']['regtime'] = $arr[1] == 0 ? array('gt', date('Y-m', strtotime(date('Y-m')." - ".$arr[1]." year"))) : array(array('lt', date('Y-m', strtotime(date('Y-m')." + ".$arr[0]." year"))),array('gt', date('Y-m', strtotime(date('Y-m')." - ".$arr[1]." year"))), 'and');
            $arrSer['url']['cl'] = str_replace('cl', '', $value);
            $arrCon[] = $value;
        }
        //排放标准
        if(preg_match('/^pz\d{1}$/', $value)){
            $arr = str_replace('pz', '', $value);
            switch ($arr){
                case 2:
                    $arrSer['ser']['esid_id'] = array('in', '1,6');
                    break;
                case 3:
                    $arrSer['ser']['esid_id'] = array('in', '2,7');
                    break;
                case 4:
                    $arrSer['ser']['esid_id'] = array('in', '3,8');
                    break;
                case 5:
                    $arrSer['ser']['esid_id'] = array('in', '4,10');
                    break;
            }
            $arrSer['url']['pz'] = str_replace('pz', '', $value);
            $arrCon[] = $value;
        }
        //门店
        if(preg_match('/^md\d{1,}$/', $value)){
            $arr = str_replace('md', '', $value);
            $arrSer['ser']['store_id'] = $arr;
            $arrSer['url']['md'] = str_replace('md', '', $value);
            $arrCon[] = $value;
        }
        
    }
    $arrSer['condition'] = implode('_', $arrCon);
    return $arrSer;
}


function remove_search($str, $value, $reg){
    $str = str_replace($value.$reg, '', $str);
    $arr = array_filter(explode('_', $str));
    return count($arr)>0 ?  implode('_', $arr).'/' :  implode('_', $arr);
}


function check_verify($code, $id = ''){    
    $verify = new \Think\Verify();   
    return $verify->check($code, $id);
}

//截取文件名和扩展
function extend($file_name,$index=0)  
{
    $extend = explode("." , $file_name); 
    return $extend[$index];  
}

function curl_get_contents($url)   
{   
    $ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址   
    //curl_setopt($ch,CURLOPT_HEADER,1);            //是否显示头部信息   
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);           //设置超时   
    curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);   //用户访问代理 User-Agent   
    curl_setopt($ch, CURLOPT_REFERER,_REFERER_);        //设置 referer   
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);      //跟踪301   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果   
    $r = curl_exec($ch);   
    curl_close($ch);   
    return $r;   
}


function get_esid($id){
    $data = M('CarEsid')->find($id);
    return $data['title'];
}


/**
 * 字符串截取，支持中文和其他编码
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=false) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}


function isMobile(){  
	$useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';  
	$useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';  	  
	function CheckSubstrs($substrs,$text){  
		foreach($substrs as $substr)  
			if(false!==strpos($text,$substr)){  
				return true;  
			}  
			return false;  
	}
	$mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
	$mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');  
		  
	$found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||  
			  CheckSubstrs($mobile_token_list,$useragent);  
		  
	if ($found_mobile){  
		return true;  
	}else{  
		return false;  
	}  
}

function getCarCityUrl($cityid, $field){
	$city = M('City')->where('id ='.$cityid)->find();
	return $city[$field];
}
