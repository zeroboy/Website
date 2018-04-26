<?php
// +----------------------------------------------------------------------
// |  Author: MrZero
// +----------------------------------------------------------------------
// |  QQ : 2586073409
// +----------------------------------------------------------------------
// | Motto: People just walking shadow
// +----------------------------------------------------------------------
// | model: 评论管理
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;
class CommentMangerController extends BaseController {

	private $ArticleCommentModel;

	public function __construct(){
		
		parent::__construct();
		$this->ArticleCommentModel = M('communicate');
	}

	public function index(){
		
		$res = $this->GetArticleComment();
		$data = $this->FormatArticleComment($res);
		$this->assign("ArticleComment",$data);
		$this->display('CommentManger/index');
	}



	//获取文章评论
	private function GetArticleComment($page=1,$where=''){
		
		$where = strip_tags(htmlspecialchars_decode(I('session.ArticleComment_SqlCondition_Str')));;


		//排序
		$order = GetQueryOrder(I('session.ArticleComment_orderField',"id"),I('session.ArticleComment_orderDirection',"desc"));

		//分页
		$total = intval($this->ArticleCommentModel->where($where)->count());//总数
		$limit = GetQueryPage(I('session.ArticleComment_pageCurrent',1),I('session.ArticleComment_pageSize',10));
	

		$field = "id,mate_post_id,mate_thread_key,mate_author_name,mate_author_email,mate_message,create_time";

		$res = $this->ArticleCommentModel->field($field)->where($where)->order($order)->limit($limit['limit'])->select();

		$return = array(
			"Page"=>array(
				"total"=>$total,
				"current"=>$limit['current'],
				"size"=>$limit['size'],
				"limit"=>$limit['limit'],
			),
			"ArticleComment"=>$res,
		);
		return $return;

	}


	//处理

	private function FormatArticleComment($data){
		
		foreach($data["ArticleComment"] as $k=>$v){
			
			$data["ArticleComment"][$k]['mate_author_name'] = urldecode($v['mate_author_name']);
			$data["ArticleComment"][$k]['mate_author_email'] = ($v['mate_author_email'])?$v['mate_author_email']:"暂无";
			$data["ArticleComment"][$k]['mate_message'] = (mb_strlen(urldecode($v['mate_message']),'utf-8')>15)?mb_substr(urldecode($v['mate_message']),0,15,'utf-8')."...":urldecode($v['mate_message']);
			$data["ArticleComment"][$k]['create_time'] = date('Y-m-d',$v['create_time']);
			$keyarr = explode("_",$v['mate_thread_key']);
			$data["ArticleComment"][$k]['article_id'] = $keyarr[2];

		}

		return $data;
	}


	//字段排序
	public function DoOrder(){
		
		session('ArticleComment_orderField',I('post.orderField','id'));
		session('ArticleComment_orderDirection',I('post.orderDirection','desc'));
		session('ArticleComment_pageSize',I('post.pageSize'));
		session('ArticleComment_pageCurrent',I('post.pageCurrent'));

		$this->index();
	}


	//文章分类检索[检索处理层]
	public function DoSearch($data=''){
		
			$ArticleComment_SqlCondition_Str = '';
			foreach($_POST as $k=>$v){

				//检索记录
				session('condition_articlecomment.condition_'.$k,$v);

				//检索条件
				switch($k){
					case 'authorname':
						if($v)
							$ArticleComment_SqlCondition_Str .= "mate_author_name like '%".urlencode($v)."%' and ";
						break;
				}
			}
			$ArticleComment_SqlCondition_Str = rtrim($ArticleComment_SqlCondition_Str,"and ");
			session('ArticleComment_SqlCondition_Str',$ArticleComment_SqlCondition_Str);

			$returns = AjaxReturnArgs(200,"查询完毕！","Article",false);
			$this->ajaxReturn($returns);

	}


	//清空查询条件
	public function Doclear(){
		
		$ArticleComment_SqlCondition_Str = session('ArticleComment_SqlCondition_Str');
		$condition_articlecomment = session('condition_articlecomment');

		if(!empty($ArticleComment_SqlCondition_Str) || !empty($condition_articlecomment)){
			//清空检索条件
			session('ArticleComment_SqlCondition_Str',null);
			//清空检索记录
			session('condition_articlecomment',null);

			$returns = AjaxReturnArgs(200,"已成功清空查询！","Article",false);
		}else{
			$returns = AjaxReturnArgs(300,"检索条件已经为空,无需重复操作","Article",false);
		}

		$this->ajaxReturn($returns);
	}




}
?>