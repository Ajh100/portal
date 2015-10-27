<?php

namespace Home\Controller;
use Think\Controller;

class PageController extends BaseController{
    
    public function _empty($name) {
        $this->showPage($name);
    }
    
    protected function showPage($name){
        
        $pageData = M('Page')->where(array('module'=>$name))->find();
        
        if(!$pageData){
            $this->redirect('/');
        }
        
        $this->assign(array(
            'pageData' => $pageData
        ));
        $this->display('about');
    }
}
