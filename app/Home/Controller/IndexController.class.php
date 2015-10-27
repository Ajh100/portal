<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController{
    public function index(){
        $webCity = session('webcity');
        //左侧搜索
        if(!empty($webCity)){
            $redBrand = M('CarBrand')->where('id in ('.  implode(',', S('SITE_CAR_BRAND_'.$webCity['id'])).')')->select();
        }else{
            $redBrand = M('CarBrand')->where('id in ('.  implode(',', S('SITE_CAR_BRAND')).')')->select();
        }
		
        
        $Brand_Letter_Arr = array();
        foreach ($redBrand as $key=>$value){
           $Brand_Letter_Arr[] = $value['letter'];
        }
        $Brand_Letter = array_unique($Brand_Letter_Arr);

        
        $redLevel = M('CarLevel')->where('is_red = 1')->limit(5)->select();
        $redPrice = M('CarPrice')->order('id')->limit(5)->select();
        //$redOutput = M('CarOutput')->order('id')->limit(5)->select();
        $redYears = M('CarYears')->order('id')->limit(5)->select();
       
        //排放标准
        $serEsid = array(
            array('id'=>2, 'title'=> '国二(欧二)'),
            array('id'=>3, 'title'=> '国三(欧三)'),
            array('id'=>4, 'title'=> '国四(欧四)'),
            array('id'=>5, 'title'=> '国五(欧五)')
        );
        //banner图片
        $homeBanner = M('Advert')->where("module = 'homebanner'")->order('sort desc')->limit(3)->select();
        
        //推荐车源
        if(!empty($webCity)){
			$redCar = M('Car')->where(array('recommend' => 2, 'status' => 1, 'city_id' => $webCity['id']))->order('clicktimes desc,addtime desc')->limit(8)->select();
        }else{
			$redCar = M('Car')->where(array('recommend' => 2, 'status' => 1))->order('clicktimes desc,addtime desc')->limit(8)->select();
        }
        
        
        //最新车源
        $mapArr = empty($webCity) ? '' : ' and city_id ='.$webCity['id'];
        $newCartop8 = M('Car')->where('recommend = 1 and status =1'.$mapArr)->order('addtime desc,clicktimes desc')->limit(8)->select();
        //经济实惠
        $jingjiCartop8 = M('Car')->where('price < 10 and status =1'.$mapArr)->order('addtime desc')->limit(8)->select();
        //时尚实用
        $shishangCartop8 = M('Car')->where('price < 20 and price >10'.$webCity['id'])->order('addtime desc')->limit(8)->select();
        //精致豪华
        $haohuaCartop8 = M('Car')->where('price < 50 and price >30'.$webCity['id'])->order('addtime desc')->limit(8)->select();
        
        //捷和风采
        $redArticleJh = M('Article')->where('classid = 1')->order('addtime desc')->limit(3)->select();
        //捷和车讯
        $redArticleCx = M('Article')->where('classid = 2')->order('addtime desc')->limit(3)->select();
        //企业视频
        $redVideo = M('Advert')->where("module = 'video'")->order('sort desc')->limit(5)->select();
        $this->assign(array(
            'homeBanner' => $homeBanner,
            'Brand_Letter' => $Brand_Letter,
            'redBrand' => $redBrand,
            'redLevel' => $redLevel,
            'redPrice' => $redPrice,
            'serEsid'=> $serEsid,
            'redYears' => $redYears,
            'redCar'   => $redCar,
            'newCartop8' => $newCartop8,
            'jingjiCartop8' => $jingjiCartop8,
            'shishangCartop8' => $shishangCartop8,
            'haohuaCartop8' => $haohuaCartop8,
            'redArticleJh' => $redArticleJh,
            'redArticleCx' => $redArticleCx,
            'redVideo' => $redVideo
        ));
        $this->display();
    }
}