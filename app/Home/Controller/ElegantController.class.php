<?php

namespace Home\Controller;
use Think\Controller;

class ElegantController extends BaseController{
    
    public function index(){
        
        
        $picNews = M('Article')->where('is_red and is_hot=1')->order('addtime desc')->limit(3)->select();
       
        $redNews = M('Article')->where('is_red = 1')->order('addtime desc')->limit(3)->select();
        
        $redActive = M('Article')->where('classid = 3')->order('addtime desc')->limit(2)->select();
        
        $redHonour = M('Advert')->where("module = 'honour'")->order('sort desc')->select();
        
        $redCompany = M('Advert')->where("module = 'company'")->order('sort desc')->limit(5)->select();
        
        $redVideo = M('Advert')->where("module = 'video'")->order('sort desc')->limit(5)->select();
        
        $this->assign(array(
            'picNews'   =>  $picNews,
            'redNews'   =>  $redNews,
            'redActive' => $redActive,
            'redHonour' => $redHonour,
            'redCompany' =>  $redCompany,
            'redVideo'  =>  $redVideo
        ));
        
        $this->display();
    }
}
