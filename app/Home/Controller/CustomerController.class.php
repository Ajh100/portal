<?php

namespace Home\Controller;
use Think\Controller;

class CustomerController extends BaseController{
    
    //卖车
    public function sellcar(){        
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
    
    public function addSellCar(){
        
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
        if(empty($_GET['province'])){
            echo '请选择省份';
            exit();
        }
        if(empty($_GET['city'])){
            echo '请选择城市';
            exit();
        }
        if(empty($_GET['miles'])){
            echo '请输入行驶里程';
            exit();
        }
        if(empty($_GET['buytime'])){
            echo '请输选择购车时间';
            exit();
        }
        if(empty($_GET['telphone'])){
            echo '请输入手机号码';
            exit();
        }
        $model = M();
        $count = $model->query('select * from tc_car_sell where ip = \''.get_client_ip().'\' and to_days(FROM_UNIXTIME(addtime, \'%Y-%c-%d\')) = to_days(now())');
        if(count($count)>3){
            echo '您今天已经提交三次';
            exit();
        }
        $ip = get_client_ip();
        $ipInfo = new \Org\Net\IpLocation('UTFWry.dat'); 
        $area = $ipInfo->getlocation($ip); 
        
        $carBrand = M('CarBrand')->find(I('get.brand'));
               
        $result = M('CarSell')->add(array(
            'title' => $carBrand['title'].' '.I('series'),
            'province' => I('get.province'),
            'city' => I('get.city'),
            'miles' => I('get.miles'),
            'buytime' => I('get.buytime'),
            'phone' => I('get.telphone'),
            'ipaddress' => $area["country"],
            'ip' => $ip,
            'network' => $area["area"],
            'addtime' => time()
        ));
        
        if($result !== false){
            echo 1;
        }else{
            echo '发布失败请稍后重试……';
        }
        
    }
    
    public function quickservice(){
        //quickPhone=18575691564&quickCity=%25u6DF1%25u5733&quickType=%25u5BC4%25u552E%25u4EE3%25u5356
        
        if(IS_AJAX){
            if(empty($_POST['quickPhone'])){
                echo '电话号码不能为空';
                exit();
            }
            if(empty($_POST['quickCity'])){
                echo '城市不能为空';
                exit();
            }
            if(empty($_POST['quickType'])){
                echo '服务类型不能为空';
                exit();
            }
            $model = M();
            $count = $model->query('select * from tc_quick_service where ip = \''.get_client_ip().'\' and to_days(FROM_UNIXTIME(addtime, \'%Y-%c-%d\')) = to_days(now())');
            if(count($count)>3){
                echo '您今天已经提交三次';
                exit();
            }
            $ip = new \Org\Net\IpLocation('UTFWry.dat');
            $area = $ip->getlocation(get_client_ip());
            $data = array(
                'title' => urldecode(I('post.quickType')),
                'city' => urldecode(I('post.quickCity')),
                'phone' => urldecode(I('post.quickPhone')),
                'addtime' => time(),
                'area' => $area['country'].'-'.$area['area'],
                'ip' => get_client_ip()
            );
            $result = M('QuickService')->add($data);
            if($result !== false){
                echo '1';
            }else{
                echo '2';
            }
        }
        exit();
    }
    
    //车型定制
    public function customized(){
        $carBrand = F('CarBrand');
        if(!$brand){
            $data = M('CarBrand')->where(array(
                'is_lock' => 0
            ))->order('letter asc, sort desc')->select();
            F('CarBrand',$data);
        }
        
        $this->assign(array(
            'carBrand' => $carBrand
        ));
        $this->display();
    }
    
    public function addcustomized(){
        if(IS_AJAX){
            if(empty($_POST['imgcode'])){
                echo '请输入验证码';
                exit();
            }
            if(!check_verify($_POST['imgcode'])){
                echo '验证码错误';
                exit();
            }
            if(empty($_POST['brand'])){
                echo '请选择品牌';
                exit();
            }
            if(empty($_POST['series'])){
                echo '请选择车系';
                exit();
            }
            if(empty($_POST['province'])){
                echo '请选择省份';
                exit();
            }
            if(empty($_POST['city'])){
                echo '请选择城市';
                exit();
            }
            if(empty($_POST['pricemin']) || empty($_POST['pricemax'])){
                echo '请输填写价格';
                exit();
            }
            if(empty($_POST['buytime'])){
                echo '请输选择预计购车时间';
                exit();
            }
            if(empty($_POST['telphone'])){
                echo '请输入手机号码';
                exit();
            }
            
            $model = M();
            $count = $model->query('select * from tc_car_buy where ip = \''.get_client_ip().'\' and to_days(FROM_UNIXTIME(addtime, \'%Y-%c-%d\')) = to_days(now())');
            if(count($count)>3){
                echo '您今天已经提交三次';
                exit();
            }
            
            $ip = get_client_ip();
            $ipInfo = new \Org\Net\IpLocation('UTFWry.dat'); 
            $area = $ipInfo->getlocation($ip); 

            $carBrand = M('CarBrand')->find(I('get.brand'));

            $result = M('CarBuy')->add(array(
                'title' => $carBrand['title'].' '.I('post.series'),
                'province' => I('post.province'),
                'city' => I('post.city'),
                'price' => I('post.pricemin').'-'.I('post.pricemax'),
                'buytime' => I('post.buytime'),
                'phone' => I('post.telphone'),
                'ipaddress' => $area["country"],
                'ip' => $ip,
                'network' => $area["area"],
                'addtime' => time()
            ));

            if($result !== false){
                echo 1;
            }else{
                echo '发布失败请稍后重试……';
            }
        }
    }
}
