<?php

namespace Home\Controller;
use Think\Controller;

class SpecialController extends BaseController{
    
   public function index(){
       
        $webCity = session('webcity');
        $map['status'] = 2;
        if(!empty($webCity)){
            $map['city_id'] = $webCity['id'];
        }
        $carList =  M('Car')->where($map)->order('addtime desc')->select();
        
        
        $this->assign(array(
            'carList' => $carList
        ));
        $this->display();
    }
    
}
