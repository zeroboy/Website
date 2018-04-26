<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 标签模块
 */

namespace Home\Controller;
use Think\Controller;

class TagController extends BaseController {

	private $ArticleTagModel;

	public function __construct(){
		
		parent::__construct();
		$this->ArticleTagModel = D('Articletag');

	}

	public function index(){
		
		$data = $this->GetArticleCateList();

		$this->assign("ArticleTag",$data);
		$this->display("Tag/index");
	}

	//
	public function AddShow(){

		$this->display("Tag/AddShow");  
	} // end func


	public function DoAdd(){
		
		if($this->ArticleTagModel->create()){
			
				if($insertid=$this->ArticleTagModel->add()){

					$returns = AjaxReturnArgs(200,"添加标签成功！","Tag");
					$this->ajaxReturn($returns);
				}else{

					$returns = AjaxReturnArgs(300,"添加标签失败！","Tag",false);
					$this->ajaxReturn($returns);
				}
		}else{

			$returns = AjaxReturnArgs(300,$this->ArticleTagModel->getError(),"Tag",false);
			$this->ajaxReturn($returns);
		}
	}

	//修改页面
	public function UpdateShow(){

		$where = " tag_id=".intval(I("get.id"));
		$data = $this->ArticleTagModel->where($where)->select();

		$this->assign("taginfo",$data[0]);
		$this->display("Tag/AddShow");  
	}

	//去修改
	public function DoUpdate(){
		if($this->ArticleTagModel->create()){
			 if(!is_numeric(I('post.tagid')))return;
			 $tag_id = intval(I('post.tagid'));
			 $where = " tag_id = ".$tag_id;
				if($this->ArticleTagModel->where($where)->save()){

					$returns = AjaxReturnArgs(200,"修改标签成功！","Tag");
					$this->ajaxReturn($returns);
				}else{

					$returns = AjaxReturnArgs(300,"修改标签失败！","Tag");
					$this->ajaxReturn($returns);
				}
		}else{
			$returns = AjaxReturnArgs(300,$this->ArticleTagModel->getError(),"Tag");
			$this->ajaxReturn($returns);
		}
	}

	public function GetArticleCateList($isformat=true){

	//检索
	if($isformat){
		$where = strip_tags(htmlspecialchars_decode(I('session.ArticleTag_SqlCondition_Str')));
	}
	
	//排序
	if($isformat){
		$order = GetQueryOrder(I('session.ArticleTag_orderField',"tag_id"),I('session.ArticleTag_orderDirection',"desc"));
		//$order = "articlecate_id desc";
	}

	//分页
	if($isformat){
		$total = intval($this->ArticleTagModel->where($where)->count());//总数
		if(empty($where)){
			$limit = GetQueryPage(I('session.ArticleTag_pageCurrent',1),I('session.ArticleTag_pageSize',10));
		}else{
			$limit = GetQueryPage(1,10);
		}
	}

	//数据
    $ArticleCateList =$this->ArticleTagModel->where($where)->order($order)->limit($limit['limit'])->select();

	$return = array(
		"Page"=>array(
			"total"=>$total,
			"current"=>$limit['current'],
			"size"=>$limit['size'],
			"limit"=>$limit['limit'],
			//"where"=>$where,
			//"a1"=>I('session.ArticleTag_pageCurrent',1),
		),
		"ArticleTag"=>$ArticleCateList,
	);

	return $return;
} // end func



//文章分类检索[检索处理层]
public function DoSearch($data=''){
	
		//$this->ajaxReturn($_REQUEST);
		$ArticleTag_SqlCondition_Str = '';

		foreach($_POST as $k=>$v){
			//检索记录
			session('condition_articletag.condition_'.$k,$v);

			//检索条件
			switch($k){
				case 'tagname':
					if($v)
						$ArticleTag_SqlCondition_Str .= "tag_name like '%".$v."%' and ";
				    break;
			}
		}

		$ArticleTag_SqlCondition_Str = rtrim($ArticleTag_SqlCondition_Str,"and ");
		session('ArticleTag_SqlCondition_Str',$ArticleTag_SqlCondition_Str);
		$returns = AjaxReturnArgs(200,"查询完毕！","Tag",false);
		//$returns['data'] = array(1,2,3,4);
		//$ArticleCateList = $this->GetArticleCateList();
		$returns['data'] = $ArticleTag_SqlCondition_Str;
		$this->ajaxReturn($returns);
}


//清空查询条件
public function Doclear(){
	
	$ArticleTag_SqlCondition_Str = session('ArticleTag_SqlCondition_Str');
	$condition_articletag = session('condition_articletag');

	if(!empty($ArticleTag_SqlCondition_Str) || !empty($condition_articletag)){
		//清空检索条件
		session('ArticleTag_SqlCondition_Str',null);
		//清空检索记录
		session('condition_articletag',null);

		$returns = AjaxReturnArgs(200,"已成功清空查询！","Tag",false);
	
	}else{
		$returns = AjaxReturnArgs(300,"检索条件已经为空,无需重复操作","Tag",false);
	}
	$this->ajaxReturn($returns);
}



//字段排序
public function DoOrder(){
	
	session('ArticleTag_orderField',I('post.orderField','tag_id'));
	session('ArticleTag_orderDirection',I('post.orderDirection','desc'));
	session('ArticleTag_pageSize',I('post.pageSize'));
	session('ArticleTag_pageCurrent',I('post.pageCurrent'));

	$this->index();
}



//去删除此条数据
public function DoDelete(){

	$tagid = intval(I('get.id'));
	if(!is_numeric($tagid))return;
	$where  = "tag_id=".$tagid;

	//判断是否占用
	$ArticleModel = D("Article");
	$article_tags = $ArticleModel->field("article_tag")->select();
	foreach($article_tags as $k=>$v){
	
		$arr = explode("|",$v['article_tag']);
		if(in_array ( $tagid ,  $arr )){
			$returns = AjaxReturnArgs(300,'此标签在部分文章仍被占用，暂时无法删除！',"Tag",false);
			$this->ajaxReturn($returns);
		}
	}

	  if($this->ArticleTagModel->where($where)->delete()){
	  
			 $returns = AjaxReturnArgs(200,'删除标签成功！',"Tag",false);
			 $this->ajaxReturn($returns);
		}else{

			 $returns = AjaxReturnArgs(300,'删除标签失败！',"Tag",false);
			 $this->ajaxReturn($returns);
	  }

}




}


?>