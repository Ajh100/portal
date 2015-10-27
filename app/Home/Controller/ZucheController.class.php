<?php

namespace Home\Controller;
use Think\Controller;

class ZucheController extends BaseController{

    public function index(){
        $carBrand = F('CarBrand');
        if(!$carBrand){
            $carBrand = M('CarBrand')->where(array(
                'is_lock' => 0
            ))->order('letter asc, sort desc')->select();
            F('CarBrand',$carBrand);
        }
        
        $this->assign(array(
            'carBrand' => $carBrand
        ));
        $this->display();
    }

    public function addinfo(){

    	
        if(empty($_GET['imgcode'])){
            echo '请输入验证码';
            exit();
        }
        if(!check_verify($_GET['imgcode'])){
            echo '验证码错误';
            exit();
        }
        if(empty($_GET['brand'])){
            echo '请选择品牌';
            exit();
        }
        if(empty($_GET['series'])){
            echo '请选择车系';
            exit();
        }
        if(empty($_GET['use_time'])){
            echo '请输选择用车时间';
            exit();
        }
        if(empty($_GET['telphone'])){
            echo '请输入手机号码';
            exit();
        }
        $model = M();
        $count = $model->query('select * from tc_zuche where ip = \''.get_client_ip().'\' and to_days(FROM_UNIXTIME(addtime, \'%Y-%c-%d\')) = to_days(now())');
        if(count($count)>3){
            echo '您今天已经提交三次';
            exit();
        }
        $ip = get_client_ip();
        $ipInfo = new \Org\Net\IpLocation('UTFWry.dat'); 
        $area = $ipInfo->getlocation($ip); 
        
        $carBrand = M('CarBrand')->find(I('get.brand'));
               
        $result = M('Zuche')->add(array(
            'carinfo' => $carBrand['title'].' '.I('series'),
            'use_time' => I('get.use_time'),
            'phone' => I('get.telphone'),
            'use_area' => I('get.use_area'),
            'use_type' => I('get.use_type'),
            'userinfo' => $area["country"].'-'.$area["area"],
            'ip' => $ip,
            'addtime' => time()
        ));
        
        if($result !== false){
            echo 1;
        }else{
            echo '发布失败请稍后重试……';
        }
    }
}
