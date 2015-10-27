<?php

namespace Home\Controller;
use Think\Controller;

class PublicController extends Controller{
    
    public function imgCode(){
        $Verify = new \Think\Verify();
        $config =    array(    
            'fontSize'=> 35,  
            'length'  => 4,
            'useCurve'=> false,
            'fontttf' => '1.ttf',
            'bg'      =>  array(243, 251, 254),
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();;
    }
    
    //加载车系 品牌id
    public function ajaxSeries(){
        if(IS_AJAX){
            $id = I('get.bid');
            $list = M('CarSubBrand')->where('bid = '.$id)->select();
            
            $str = '<option value="">选择车系</option>';
            foreach ($list as $key=>$value){
                $str .= '<optgroup label="=='.$value['title'].'=="></optgroup>';
                $str .= $this->getCarseries($value['id']);
            }
            echo $str;
        }
    }
    
    //获取二级品牌 品牌id
    protected function getCarseries($bid){
        $list = M('CarSeries')->where('sub_bid = '.$bid)->select();
        if($list){
            $str = '';
            foreach ($list as $key=>$value){
                $str .= '<option value="'.$value['title'].'">'.$value['title'].'</option>';;
            }
            return $str;
        }else{
            return false;
        }
    }
}
