<?php
// +----------------------------------------------------------------------
// |  Author: MrZero
// +----------------------------------------------------------------------
// |  QQ : 2586073409
// +----------------------------------------------------------------------
// | Motto: People just walking shadow
// +----------------------------------------------------------------------
// | model: 文章表模型
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

class ArticleModel extends Model{

	//字段映射
	//需要映射的表单名称如若与映射字段名字相同即无需映射
	protected $_map = array(	
		'article_cate'=>'article_cate_id',//分类
		'article_comment'=>'article_is_comment',//是否评论
		'max_commcount'=>'article_max_communicate_count',//评论限制数
		"tagstring"=>"article_tag",//标签
	);

	//自动完成
	protected $_auto = array ( 
			array("article_create_time",'time',1,'function'),  //创建时间
			array("article_update_time",'time',3,'function'),  //创建时间
			array("article_author_id",'getauthor',3,'callback'), //作者
			array("article_face_path",'getfacepath',3,'callback'), //封面
			array("article_face_path",'',2,'ignore'), //封面 为空时忽略
			array("article_tag",'gettagstring',3,'callback'), //封面
	);


	//获取作者
	protected function getauthor(){
		
		$userid = (session('userinfo.user_id'))?session('userinfo.user_id'):1;
		return $userid;
	}

	//获取封面
	protected function getfacepath(){
		$uploaddata = session('uploaddata');
		$path = $uploaddata["savepath"].$uploaddata["savename"];
		return $path;
	}

	//处理标签串
	protected function gettagstring($string){
	
		return rtrim($string,',');
	}


	//自动验证
    protected $_validate = array(

		array('article_cate_id','require','文章分类必选！'),
		array('article_title','require','文章标题必填！'),
		array('article_content','require','文章内容必填！'),
		array('article_iscomment','require','文章是否评论必选！'),
	);

	

}


?>