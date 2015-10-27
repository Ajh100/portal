<?php

namespace Home\Controller;
use Think\Controller;

class JoinController extends Controller{
    
    public function index(){
        $this->display();
    }
    
    public function _empty($name){
        $this->showpage($name);
    }
    
    protected function showpage($name){
        $this->display($name);
    }
}
