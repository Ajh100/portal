<?php

namespace Home\Model;
use Think\Model\ViewModel;
class ArticleViewModel extends ViewModel{
    public $viewFields = array(
        'article' => array('_table'=>'tc_article','id','title','classid','image','description','content','sort','is_red','is_hot','clicktimes','addtime'),
        'class' => array('_table'=>'tc_article_class','title'=>'classtitle','_on'=>'article.classid=class.id')
    );
}