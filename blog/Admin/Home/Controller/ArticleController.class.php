<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 文章模块
 */

namespace Home\Controller;
use Think\Controller;

class ArticleController extends BaseController {

private $ArticleModel;
private $ArticleFaceModel;
private $ArticleTagObject;

public function __construct(){
	
	parent::__construct();

	$this->ArticleModel = D('Article'); 
	$this->ArticleFaceModel = M('article_faceimg');

	$this->ArticleTagObject = new TagController;
	
}

public function index(){

	$ArticleList = $this->GetArticleList();
	$ArticleList = $this->FillArticleList($ArticleList);
	$ArticleCate = new ArticleCategoryController();
	$ArticleCateList = $ArticleCate->GetArticleCateList(false);
	$ArticleCateList['ArticleCateList'] = $ArticleCate->FormatArticleCate($ArticleCateList['ArticleCateList']);
	$this->assign("ArticleCateList",$ArticleCateList['ArticleCateList']);

	$this->assign('ArticleList',$ArticleList);
	$this->display('Article/index');
}

//获取文章列表
private function GetArticleList($page=''){
	
	//检索
	$where = strip_tags(htmlspecialchars_decode(I('session.Article_SqlCondition_Str')));

	//排序
	$order = GetQueryOrder(I('session.Article_orderField',"article_id"),I('session.Article_orderDirection',"desc"));

	//分页
	$total = intval($this->ArticleModel->where($where)->count());//总数
	$limit = GetQueryPage(I('session.Article_pageCurrent',1),I('session.Article_pageSize',10));
	
	//数据
	$ArticleList = $this->ArticleModel->where($where)->order($order)->limit($limit['limit'])->select();
	
	$return = array(
		"Page"=>array(
			"total"=>$total,
			"current"=>$limit['current'],
			"size"=>$limit['size'],
			"limit"=>$limit['limit'],
		),
		"ArticleList"=>$ArticleList,
	);
	return $return;
}


public function AddShow(){
	
	$ArticleCate = new ArticleCategoryController();
	$ArticleCateList = $ArticleCate->GetArticleCateList(false);
	$ArticleCateList['ArticleCateList'] = $ArticleCate->FormatArticleCate($ArticleCateList['ArticleCateList']);	
	$this->assign("ArticleCateList",$ArticleCateList['ArticleCateList']);

	$sname = session_name();
	$sid = session_id();
	$this->assign("sname",$sname);
	$this->assign("sid",$sid);

	$ArticleTagData = $this->ArticleTagObject->GetArticleCateList(false);
	$this->assign("ArticleTagData",$ArticleTagData['ArticleTag']);
	$this->display('Article/AddShow');
}

public function DoAdd(){
	

	if($this->ArticleModel->create()){
		
			if($insertid=$this->ArticleModel->add()){

				//添加后续事件
				if(!$this->FollowEvent($insertid,"add")){
					$returns = AjaxReturnArgs(300,"添加文章封面失败！","Article",false);
					$this->ajaxReturn($returns);	
				}

				$returns = AjaxReturnArgs(200,"添加文章成功！","Article");
				$this->ajaxReturn($returns);
			}else{

				$returns = AjaxReturnArgs(300,"添加文章失败！","Article",false);
				$this->ajaxReturn($returns);
			}
	}else{

		$returns = AjaxReturnArgs(300,$this->ArticleModel->getError(),"Article",false);
		$this->ajaxReturn($returns);
	}
}



//文章列表参数处理
private function FillArticleList($data){

	
	$ArticleCate = new ArticleCategoryController();
	$ArticleCateList = $ArticleCate->GetArticleCateList(false);
	foreach($ArticleCateList['ArticleCateList'] as $v){
		$catearr[$v['articlecate_id']] = $v['articlecate_name'];
	}

	$UserModel = M('user');
	$UserInfo = $UserModel->select();
	foreach( $UserInfo as $k=>$v){
		$userarr[$v['user_id']] = $v['user_account'];
	}

	foreach($data["ArticleList"] as $k=>$v){
		$data["ArticleList"][$k]['article_create_time'] = date("Y-m-d H:i:s",$v['article_create_time']);
		$data["ArticleList"][$k]['article_cate_id_code'] = $v['article_cate_id'];
		$data["ArticleList"][$k]['article_cate_id'] = $catearr[$v['article_cate_id']];
		$data["ArticleList"][$k]['article_is_comment_c'] = ($v['article_is_comment']==1)?'是':'否';
		$data["ArticleList"][$k]['article_author_names'] = $userarr[$v['article_author_id']];
		//$content = (string)strip_tags(htmlspecialchars_decode($v['article_content']));
		$data["ArticleList"][$k]['article_content_format'] = strip_tags(htmlspecialchars_decode($v['article_content']));
		$data["ArticleList"][$k]['article_tags'] = str_replace("|",",",$v['article_tag']);
	}

	return $data;
}

//文章修改展示页
public function UpdateShow(){

	$ArticleCate = new ArticleCategoryController();
	$ArticleCateList = $ArticleCate->GetArticleCateList(false);
	$ArticleCateList['ArticleCateList'] = $ArticleCate->FormatArticleCate($ArticleCateList['ArticleCateList']);
	$this->assign("ArticleCateList",$ArticleCateList['ArticleCateList']);

	$where = "article_id = ".I('get.id');
	$res = $this->ArticleModel->where($where)->select();
	$arr = array("ArticleList"=>$res);
	$datas = $this->FillArticleList($arr);
//var_dump($datas);
//die;

	$this->assign('ArticleList',$datas['ArticleList'][0]);

	$ArticleTagData = $this->ArticleTagObject->GetArticleCateList(false);
	$this->assign("ArticleTagData",$ArticleTagData['ArticleTag']);
	$this->display('Article/AddShow');
}



public 
/**
 * 文章修改请求.
 */
function DoUpdate()
{
	if($this->ArticleModel->create()){
		 $article_id = I('post.article_id');
		 $where = " article_id = ".$article_id;
			if($this->ArticleModel->where($where)->save()){

				//更新后续事件
				if(!$this->FollowEvent($article_id,"save")){
					$returns = AjaxReturnArgs(300,"修改文章封面失败！","Article",false);
					$this->ajaxReturn($returns);	
				}

				$returns = AjaxReturnArgs(200,'修改文章成功',"Article");
				$this->ajaxReturn($returns);
			}else{

				$returns = AjaxReturnArgs(300,"修改文章失败！","Article");
				$this->ajaxReturn($returns);
			}
	}else{
		$returns = AjaxReturnArgs(300,$this->ArticleModel->getError(),"Article");
		$this->ajaxReturn($returns);
	}
} // end func


public 
/**
 * 文章删除.
 */
function DoDelete()
{
  $articleid = I('get.id');

  //判断此分组是否有子类
	$elementcount = 0;
  if($elementcount>0){

		$returns = AjaxReturnArgs(300,"对应文章存在评论，请先删除评论再删除文章！","Article",false);
		$this->ajaxReturn($returns);
  }else{
	  if($this->ArticleModel->where("article_id=".$articleid)->delete()){
	  
			//添加后续事件
			if(!$this->FollowEvent($articleid,"delete")){
				$returns = AjaxReturnArgs(300,"删除文章封面失败！","Article",false);
				$this->ajaxReturn($returns);	
			}

			$returns = AjaxReturnArgs(200,"删除文章成功！","Article",false);
			$this->ajaxReturn($returns);

		}else{
			$returns = AjaxReturnArgs(300,"删除文章失败！","Article",false);
			$this->ajaxReturn($returns);
	  }
  }
  
} // end func




//文章分类检索[检索处理层]
public function DoSearch($data=''){
	
		$Article_SqlCondition_Str = '';
		foreach($_POST as $k=>$v){

			//检索记录
			session('condition_article.condition_'.$k,$v);

			//检索条件
			switch($k){
				case 'article_cate':
					if($v)
						$Article_SqlCondition_Str .= "article_cate_id = ".$v." and ";
				    break;
				case 'article_title':
					if($v)
						$Article_SqlCondition_Str .= "article_title like '%".$v."%' and ";	
				    break;
				case 'publish_time':
					if($v)
						$Article_SqlCondition_Str .= " article_create_time >=".strtotime($v)." and ";
					break;
			}
		}
		$Article_SqlCondition_Str = rtrim($Article_SqlCondition_Str,"and ");
		session('Article_SqlCondition_Str',$Article_SqlCondition_Str);

		$returns = AjaxReturnArgs(200,"查询完毕！","Article",false);
		$this->ajaxReturn($returns);

}


//清空查询条件
public function Doclear(){
	
	$Article_SqlCondition_Str = session('Article_SqlCondition_Str');
	$condition_article = session('condition_article');

	if(!empty($Article_SqlCondition_Str) || !empty($condition_article)){
		//清空检索条件
		session('Article_SqlCondition_Str',null);
		//清空检索记录
		session('condition_article',null);

		$returns = AjaxReturnArgs(200,"已成功清空查询！","Article",false);
	}else{
		$returns = AjaxReturnArgs(300,"检索条件已经为空,无需重复操作","Article",false);
	}

	$this->ajaxReturn($returns);
}


//字段排序
public function DoOrder(){
	
	session('Article_orderField',I('post.orderField','article_id'));
	session('Article_orderDirection',I('post.orderDirection','desc'));
	session('Article_pageSize',I('post.pageSize'));
	session('Article_pageCurrent',I('post.pageCurrent'));

	$this->index();
}



//后续跟进事件
private function FollowEvent($id,$type){
	
	$uploaddata = session('uploaddata');
	$face_size = ($uploaddata["size"])?$uploaddata["size"]:0;
	$data1 = array(
			"face_id"=>	$id,
			"face_ext"=> $uploaddata['ext'],
			"face_originalname"=>$uploaddata["name"],
			"face_savename"=>$uploaddata["savename"],
			"face_savepath"=>$uploaddata["savepath"],
			"face_size"=>$face_size,
			"face_type"=>$uploaddata["type"],
			"face_createtime"=>time(),
	);

	switch($type){
		case 'add':
			$result = $this->ArticleFaceModel->data($data1)->add();
			break;
		case 'save':
			$result = $this->ArticleFaceModel->save($data1);
			break;
		case 'delete':

			$where = "face_id = ".$id;
			$articledata =  $this->ArticleFaceModel->where($where)->select();
			$path = C("UPLOAD_PATH").$articledata[0]['face_savepath'].$articledata[0]['face_savename'];
			unlink($path);
			$result = $this->ArticleFaceModel->where("face_id=".$id)->delete();
			break;

	}

	if($result){
			session('uploaddata',null);
	}

	return $result;
}




}