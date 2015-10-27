<?php

namespace Home\Controller;
use Think\Controller;

class NewjhcxzxsController extends BaseController{
    
    public function index(){
        $this->newslist();
        $this->display();
    }
    
    protected function newslist($map = array()){
        $modelNews = D('ArticleView');
        $count = $modelNews->where($map)->count();
        $page  = new \Think\Page($count,5);
        $show  = $page->show();
        $listNews = $modelNews->where($map)->order('id desc, sort desc')->limit($page->firstRow.','.$page->listRows)->select();
       
        $this->assign(array(
            'listNews' => $listNews,
            'page' => $show
        ));
    }


    protected function read($id){
        $dataNews = D('ArticleView')->find($id);
        $cookieUser = explode('-', cookie('user_news'));
        
        if(!in_array($id, $cookieUser)){
            M('Article')->where(array('id'=>$id))->setInc('clicktimes',1);
            cookie('user_news', $id.'-'.cookie('user_news'), 3600);
        }
        
        $this->assign(array(
            'dataNews' => $dataNews
        ));
        $this->display('read');
    }
    

    public function _empty($id) {
        if(is_numeric($id)){
            $this->read($id);
        }else{
            $arr = explode('-', $id);
            $map = array('classid'=>$arr[1]);
            $this->newslist($map);
            $this->display('index');
        }
    }
}
