<?php

namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller{
    
    protected function _initialize(){
		header("Content-type: text/html; charset=utf-8");
		if(isMobile()){
			header("location:".C('WEB_MOBILE_URL'));
		}
		
        //网站配置
        $SiteConfigData =   S('SITE_CONFIG_DATA');
        if(!$SiteConfigData){
            $data = M('Config')->where(array('group'=>1))->select();
            $arr = array();
            foreach ($data as $key => $value){
                $arr[$value['keys']] = $value['value'];
            }
            S('SITE_CONFIG_DATA', $arr, C('APP_S_CACHE_TIME'));
			$SiteConfigData =   $arr;
        }
        
        //缓存 城市列表 品牌 车系
        $SiteCityData = S('SITE_CITY_DATA');
        if(!$SiteCityData){
            
            $data = M('City')->where('status = 0')->order('sort desc,id asc')->select();
            S('SITE_CITY_DATA', $data,  C('APP_S_CACHE_TIME')); 
            $SiteCityData = $data;
			
            $CAR_BRAND_LIST = S('CAR_BRAND_LIST');
            if(!$CAR_BRAND_LIST){
                $carList = M('Car')->where('status in (1,2)')->field('brand_id,series_id,city_id')->select();
                foreach ($carList as $k=>$v){
                   $brand[]  =  $v['brand_id'];
                   $series[] =  $v['series_id'];
                }
                S('SITE_CAR_BRAND',$brand, C('APP_S_CACHE_TIME'));
                S('SITE_CAR_SERIES',$series, C('APP_S_CACHE_TIME'));
				S('CAR_BRAND_LIST', $carList,  C('APP_S_CACHE_TIME'));
				$CAR_BRAND_LIST = $carList;
            }
			
            foreach ($data as $key=>$value){
                $brand = array();
                $series = array();
                $carList = M('Car')->where('status in (1,2) and city_id ='.$value['id'])->field('brand_id,series_id,city_id')->select();
                foreach ($carList as $k=>$v){
                   $brand[]  =  $v['brand_id'];
                   $series[] =  $v['series_id'];
                }
                S('SITE_CAR_BRAND_'.$value['id'], $brand, C('APP_S_CACHE_TIME'));
                S('SITE_CAR_SERIES_'.$value['id'], $series, C('APP_S_CACHE_TIME'));
            }
        }
        
        //获取当前城市
        $subDomian = extend($_SERVER['HTTP_HOST']);
		$cookiewebcity = cookie('webcity');
		$sessionwebcity =  session('webcity');
        $webCity = empty($cookiewebcity) ? $sessionwebcity : $cookiewebcity;
		session('webcity', $webCity);
        if($subDomian == 'www'){
            if(!$webCity){
                $ipData = curl_get_contents(C('IP_LOCATION_URL').get_client_ip());
                $city = json_decode($ipData,true);
                $data = M('City')->field('id,title,pinyin')->where(array( 'title'=> $city['city'] ))->find();
                $data ? session('webcity', $data) : session('webcity', array());
                $data ? cookie('webcity', $data) : cookie('webcity', array());
            }
        }else{
            $data = M('City')->field('id,title,pinyin')->where(array( 'pinyin'=> $subDomian ))->find();
            $data ? session('webcity', $data) : session('webcity', array());
            $data ? cookie('webcity', $data) : cookie('webcity', array());
        }
        
        
        //友情链接
        $friendLink = S('Friend_Link');
        if(!$friendLink){
            $friendLink = M('Link')->where(array('status'=>0,'is_read'=>1))->order('sort desc')->limit(10)->select(); 
            S('Friend_Link', $friendLink,  C('APP_S_CACHE_TIME'));
        }
        

        $this->assign(array(
            'SiteConfigData' => $SiteConfigData,
            'SiteCityData'  => $SiteCityData,
            'SiteCurentCity'=> session('webcity'),
            'friendLink'    => $friendLink
        ));

    }
    
    /* 空操作，用于输出404页面 */
    public function _empty(){
        $this->redirect('/');
    }
    
}
