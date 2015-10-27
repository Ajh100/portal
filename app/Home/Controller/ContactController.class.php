<?php

namespace Home\Controller;
use Think\Controller;

class ContactController extends BaseController{
    
    public function index(){
        
        $SITE_STORE_LIST = S('SITE_STORE_LIST');
        if(!$SITE_STORE_LIST){
            $SITE_STORE_LIST = M('Store')->where('status = 0')->order('sort desc, id asc')->select();
            foreach ($SITE_STORE_LIST as $key => $value){
                $SITE_STORE_LIST[$key]['img'] = json_decode($value['imglist'], true);
            }
            S('SITE_STORE_LIST', $SITE_STORE_LIST);
        }  
        $this->assign(array(
            'SITE_STORE_LIST' => $SITE_STORE_LIST
        ));
        $this->display('index');
    }
}
