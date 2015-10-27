<?php

namespace Home\Controller;
use Think\Controller;

class CarController extends BaseController{
    
    public function index(){
        $this->get_car_list();
    }
    
    public function _empty($name){
        $this->get_car_list($name);

    }
    
 
    
    protected function get_car_list($name){

        $pathInfo = explode('/', $_SERVER['PATH_INFO']);
        
        $urlArr = array();
        
        $map = array();
        
        $carSeries = '';
        
        $webCity = session('webcity');
        
        if(!empty($webCity)){
            $map['city_id'] = $webCity['id'];
        }
        
        if(!empty($_GET['word'])){
            $map['title'] = array('like', '%'.I('get.word').'%');
        }
        
        if(!empty($name)){
            
            if(count($pathInfo) == 2){
                $urlArr = parse_car_preg($pathInfo[1]);
                if(empty($urlArr['url'])){
                    $carBrand = M('CarBrand')->where(array('pinyin'=>$pathInfo[1]))->find();
                    if(!empty($webCity)){
                        $carSeries = D('CarSeriesView')->where(array(
                            'brand_id' => $carBrand['id'],
                            'id' => array('in', implode(',', S('SITE_CAR_SERIES_'.$webCity['id'])))
                        ))->select();                     
                    }else{
                        $carSeries = D('CarSeriesView')->where(array(
                            'brand_id' => $carBrand['id'],
                            'id' => array('in', implode(',', S('SITE_CAR_SERIES')))
                        ))->select();
                    }
                    
                    $map['brand_id'] = $carBrand['id'];
                    $urlArr['url']['brand'] = $pathInfo[1];
                }else{
                    $map = $urlArr['ser'];
                }
            }

            if(count($pathInfo) == 3){
                $urlArr = parse_car_preg($pathInfo[2]);
                
                $carBrand = M('CarBrand')->where(array('pinyin'=>$pathInfo[1]))->find();
                
                if(!empty($webCity)){
                    $carSeries = D('CarSeriesView')->where(array(
                        'brand_id' => $carBrand['id'],
                        'id' => array('in', implode(',', S('SITE_CAR_SERIES_'.$webCity['id'])))
                    ))->select();                     
                }else{
                    $carSeries = D('CarSeriesView')->where(array(
                        'brand_id' => $carBrand['id'],
                        'id' => array('in', implode(',', S('SITE_CAR_SERIES')))
                    ))->select();
                }
                $map['brand_id'] = $carBrand['id'];
                //车系
                if(empty($urlArr['url'])){
                    $series = M('CarSeries')->where(array('pinyin'=>$pathInfo[2], 'brand_id'=>$carBrand['id']))->find();
                    $map['series_id'] = $series['id'];
                    $urlArr['url']['series'] = $pathInfo[2];
                }else{
                    $map = array_merge($map, $urlArr['ser']);
                }
                $urlArr['url']['brand'] = $pathInfo[1];
            }

            if(count($pathInfo) == 4){
                $urlArr = parse_car_preg($pathInfo['3']);

                $carSeriesNow = M('CarSeries')->where(array('pinyin'=>$pathInfo[2]))->find();
                $carBrandNow = M('CarBrand')->where(array('pinyin'=>$pathInfo[1]))->find();
                
                if(!empty($webCity)){
                    $carSeries = D('CarSeriesView')->where(array(
                        'brand_id' => $carBrandNow['id'],
                        'id' => array('in', implode(',', S('SITE_CAR_SERIES_'.$webCity['id'])))
                    ))->select();                     
                }else{
                    $carSeries = D('CarSeriesView')->where(array(
                        'brand_id' => $carBrandNow['id'],
                        'id' => array('in', implode(',', S('SITE_CAR_SERIES')))
                    ))->select();
                }
                
                $map['brand_id'] = $carBrandNow['id'];
                $map['series_id'] = $carSeriesNow['id'];
                $map = array_merge($map, $urlArr['ser']);
                
                
                $urlArr['url']['brand'] = $pathInfo[1];
                $urlArr['url']['series'] = $pathInfo[2];
            }
        }
       
        
 
        //品牌
        if(!empty($webCity)){
            $carBrand = M('CarBrand')->where(array(
               'id' => array('in', implode(',', S('SITE_CAR_BRAND_'.$webCity['id'])))
            ))->order('pinyin')->select();           
        }else{
            $carBrand = M('CarBrand')->where(array(
                'id' => array('in', implode(',', S('SITE_CAR_BRAND')))
            ))->order('pinyin')->select();
        }
        
        //车辆级别
        $carLevel = M('CarLevel')->where(array('is_red = 1'))->order('sort desc')->select();
        //搜索价格
        $serPrice = M('CarPrice')->select();
        //搜索车龄
        $serCarYear = M('CarYears')->where('status = 0')->select();
        //排放标准
        $serEsid = array(
            array('id'=>2, 'title'=> '国二（含欧二）'),
            array('id'=>3, 'title'=> '国三（含欧三）'),
            array('id'=>4, 'title'=> '国四（含欧四）'),
            array('id'=>5, 'title'=> '国五（含欧五）')
        );
        //门店
        $serStore = M('store')->where('status = 0 and city = '.$webCity['id'])->select();
        
        $map['status'] = array('in', '1,2');
        
        $count   =  M('Car')->where($map)->count();
        $page       = new \Think\Page($count,12);
        $show       = $page->show();
        $carList =  M('Car')->where($map)->order('addtime desc,clicktimes desc')->limit($page->firstRow.','.$page->listRows)->select();
        
		if(!$carList){
			unset($map['city_id']);
			$count   =  M('Car')->where($map)->count();
			$page    = new \Think\Page($count,12);
			$show    = $page->show();
			$otherList =  M('Car')->where($map)->order('addtime desc,clicktimes desc')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('otherList',$otherList);
		}
        
        
        $this->assign(array(
            'carBrand'  => $carBrand,
            'carSeries' => $carSeries,
            'carLevel'  => $carLevel,
            'serPrice'  => $serPrice,
            'serCarYear'=> $serCarYear,
            'serEsid'  => $serEsid,
            'serStore' => $serStore,
            'carList'   => $carList,
            'page'      =>  $show,
            'urlReg'    => $urlArr['url'],
            'urlCond'   => $urlArr['condition']
        ));
        $this->display('list');
        
    }
    
    
    public function detail(){
        
        $pathInfo = $_SERVER['PATH_INFO'];
        $pathArr = explode('/', $pathInfo);
        
        $id = intval(end($pathArr));
        
        if(!is_int($id)){
            $this->redirect('/');
        }
        
        $carData = M('Car')->find($id);
        if(!$carData){
            $this->redirect(__APP__.'/car/');
        }     
        if(!in_array($carData['status'], array(1,2))){
            $this->redirect(__APP__.'/car/');
        }
        
        $saleMan = M('Saleman')->find($carData['uid']);
        
        
        $carBrand = M('CarBrand')->find($carData['brand_id']);
        $carClass = M('CarClass')->find($carData['class_id']);
        $carEsid = M('CarEsid')->find($carData['esid_id']);
        $carColor = M('CarColor')->find($carData['color_id']);
        $storeInfo = M('Store')->find($carData['store_id']);
        
        
        $redMap = array(
            'price' => array(array('egt', $carData['price']-15), array('elt', $carData['price']+15), 'and'),
            'id' =>  array('neq',$carData['id']),
	    'status'=> array('in','1'),
            'store_id' => $carData['store_id']
        );
        $redCarList = M('Car')->where($redMap)->limit(4)->select();
        
        
        $cookieUser = explode('-', cookie('user_cars'));
        if(!in_array($id, $cookieUser)){
            M('Car')->where(array('id'=>$id))->setInc('clicktimes',1);
            cookie('user_cars', $id.'-'.cookie('user_cars'), 3600);
        }
        
        $this->assign(array(
            'carData' => $carData,
            'carImg'  => json_decode($carData['imglist'], true),
            'carBrand'=> $carBrand,
            'carClass'=> $carClass,
            'carEsid' => $carEsid,
            'carColor'=> $carColor,
            'saleMan' => $saleMan,
            'storeInfo' => $storeInfo,
            'storeImg' => json_decode($storeInfo['imglist'], true),
            'redCarList' => $redCarList
        ));
        $this->display();
    }
}
