<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 文章分组模块
 */

namespace Home\Controller;
use Think\Controller;

class ArticleCategoryController extends BaseController {

private $ArticleCateModel;

public function __construct()
{
    parent::__construct();
	$this->ArticleCateModel = M("articlecate");
} // end func

public function index(){


	$ArticleCateList = $this->GetArticleCateList();
	$ArticleCateList['ArticleCateList'] = $this->FormatArticleCate($ArticleCateList['ArticleCateList']);

	//无限极别 分页处理
	/**/
	$limit_arr = explode(',',$ArticleCateList['Page']['limit']);
	for($i=$limit_arr[0];$i<=($limit_arr[1]+$limit_arr[0]-1);$i++){
			if($ArticleCateList['ArticleCateList'][$i]){
				$all[] = $ArticleCateList['ArticleCateList'][$i];
			}
	}
	$ArticleCateList['ArticleCateList'] = $all;


//var_dump($ArticleCateList);
//die;
	$this->assign('ArticleCateList',$ArticleCateList);
	$this->display('ArticleCategory/index');
}


public 
/**
 * 添加---显示页面.
 */
function AddShow()
{

	$ArticleCateList = $this->GetArticleCateList(false);
	$ArticleCateList['ArticleCateList'] = $this->FormatArticleCate($ArticleCateList['ArticleCateList']);
	
	$this->assign('ArticleCateList',$ArticleCateList);
	$this->display('addshow');  
} // end func



public 
/**
 * 添加数据.
 */
function DoAdd()
{
    
	$CateName = I('post.catename');
	$data['CateName'] = $CateName;
	$data['ParentMark'] = I('post.articlecate_parent_mark');

	$datas = $this->FillArticleCategory($data);
	
	if($datas['status']>0){
		 
			$adddata = array(
				"articlecate_name"=>$datas['content']['CateName'],
				"articlecate_parent_mark" => $datas['content']['ParentMark'],
			);
			$insertid = $this->ArticleCateModel->data($adddata)->add();
			if($insertid){
				//更新顶级目录父级标记
				/*
				$newdata = $this->ArticleCateModel->where("articlecate_id=".$insertid)->select();
				if($newdata[0]['articlecate_parent_mark'] == '0'){
						$updatedata = array(
							"articlecate_parent_mark"=>"0".'-'.$insertid,
						);
						$this->ArticleCateModel->where("articlecate_id=".$insertid)->save($updatedata);
				}*/
				 //$this->assign("jumpUrl",U('ArticleCategory/index'));
				 //$this->success("添加文章分组成功！");

				 $returns = AjaxReturnArgs(200,"添加文章分组成功！","ArticleCategory");
				 $this->ajaxReturn($returns);
			}else{
				//$this->assign("jumpUrl",U('ArticleCategory/AddShow'));
				//$this->error('添加文章分组失败！');
				$returns = AjaxReturnArgs(300,"添加文章分组失败！","ArticleCategory",false);
				$this->ajaxReturn($returns);
			}
	}else{
		 //$this->assign("jumpUrl",U('ArticleCategory/AddShow'));
		 //$this->error($datas['msg']);	
		 $returns = AjaxReturnArgs(300,$datas['msg'],"ArticleCategory",false);
		$this->ajaxReturn($returns);
	}

} // end func


private 
/**
 * 分组添加数据过滤.
 * @param   分组数据
 * @return  过滤后的分组数据或抛出错误
 */
function FillArticleCategory($data)
{
	if(($data['CateName']=='')||(strlen($data['CateName'])==0)||($data['CateName']==null)){
		return array("status"=>-1,"msg"=>"分组名称不可为空！");
	}
	
	$ParentMark = explode('-',$data['ParentMark']);
	if(count($ParentMark)>C("ARTICLECATE_ADD_MAX_TOPCLASS")){
		return array("status"=>-1,"msg"=>"添加的文章分组已超过层级设置上限！");
	}

	return array("status"=>1,"content"=>$data);
} // end func

public 
/**
 * 文章分组信息显示.
 */
function UpdateShow()
{
	$CateID =  I('get.id');
	$res =$this->ArticleCateModel->where("articlecate_id=".$CateID)->select();
	$resarr = explode('-',$res[0]['articlecate_parent_mark']);
	$res[0]['pid'] = $resarr[count($resarr)-1];
	$this->assign('CateUpdateInfo',$res[0]);

	$ArticleCateList = $this->GetArticleCateList(false);
	$ArticleCateList['ArticleCateList'] = $this->FormatArticleCate($ArticleCateList['ArticleCateList']);

	$this->assign('ArticleCateList',$ArticleCateList);

	$this->display("addshow");  
} // end func


public 
/** * 修改文章分组信息.
 */
function DoUpdate()
{
	$cateid = I('post.cateid');
	$catename = I('post.catename');
	$parentmark = I('post.articlecate_parent_mark');
	$data['CateID'] = $cateid;
	$data['CateName'] = $catename;
	$data['ParentMark'] = $parentmark;
	$datas = $this->FillArticleCategory($data);
	if($datas['status']>0){
		$updatedata = array(
			"articlecate_name"	=>$datas['content']['CateName'],
			"articlecate_parent_mark" =>$datas['content']['ParentMark'],
		);

		 if($this->ArticleCateModel->where("articlecate_id=".$datas['content']['CateID'])->save($updatedata)){
				//$this->assign("jumpUrl",U('ArticleCategory/index'));
				//$this->success("修改文章分组成功！");
				 $returns = AjaxReturnArgs(200,"修改文章分组成功！","ArticleCategory");
				 $this->ajaxReturn($returns);
			}else{
				//$this->assign("jumpUrl",U('ArticleCategory/UpdateShow','',false)."/id/".$datas['content']['CateID']);
				//$this->error('修改文章分组失败！');
				$returns = AjaxReturnArgs(300,"修改文章分组失败！","ArticleCategory");
				$this->ajaxReturn($returns);
		 }  

	}else{
		$this->assign("jumpUrl",U('ArticleCategory/UpdateShow','',false)."/id/".$cateid);
		 $this->error($datas['msg']);		
	}
 
} // end func


public 
/**
 * 删除文章分组.
 */
function DoDelete()
{
  $cateid = I('get.id');

  //判断此分组是否有文章
  $ArticleModel = M('article');
  $where = "article_cate_id=".$cateid;
  $elementcount = $ArticleModel->where($where)->count();
  if($elementcount>0){
		$returns = AjaxReturnArgs(300,"对应分组存在文章，请先删除文章再删除分组！","ArticleCategory",false);
		$this->ajaxReturn($returns);
  }

  //找到删除项子类
  //$ArticleCateList =$this->ArticleCateModel->where("articlecate_id=".$cateid)->select();
  $CateSon = $this->ArticleCateModel->field('articlecate_id,articlecate_parent_mark')->select();
  $CateSon = $this->FormatArticleCate($CateSon);
  foreach($CateSon as $v){
	  $arr = explode('-',$v['articlecate_parent_mark']);
	  if(in_array($cateid,$arr)){
		$CateSon1[] =  $v;
	  }
  }

  //验证子类是否存在文章
  if($CateSon1){
	  foreach($CateSon1 as $v){
		$elementcount2 = $ArticleModel->where("article_cate_id=".$v['articlecate_id'])->count();
		if($elementcount2>0){
			$returns = AjaxReturnArgs(300,"对应分组子类存在文章，请先删除子类文章再删除分组！","ArticleCategory",false);
			$this->ajaxReturn($returns);
		}
	  }
  }

  //删除当前分组	
  if($this->ArticleCateModel->where("articlecate_id=".$cateid)->delete()){
		//删除子类分组
		if($CateSon1){
			foreach($CateSon1 as $v){
				$this->ArticleCateModel->where("articlecate_id=".$v['articlecate_id'])->delete();
			}
		}
		$returns = AjaxReturnArgs(200,"删除文章分组成功！","ArticleCategory",false);
		$this->ajaxReturn($returns);
	}else{

		$returns = AjaxReturnArgs(300,"删除文章分组失败！","ArticleCategory",false);
		$this->ajaxReturn($returns);
  }
  
} // end func

public 
/**
 * 获取文章分组列表.
 * @param   $isformat bool  查询值是否格式化
 * @return  
 */
function GetArticleCateList($isformat=true)
{
	//检索
	if($isformat){
		$where = strip_tags(htmlspecialchars_decode(I('session.ArticleCategory_SqlCondition_Str')));
	}
	
	//排序
	if($isformat){
		$order = GetQueryOrder(I('session.ArticleCategory_orderField',"articlecate_id"),I('session.ArticleCategory_orderDirection',"desc"));
		//$order = "articlecate_id desc";
	}

	//分页
	if($isformat){
		$total = intval($this->ArticleCateModel->where($where)->count());//总数
		$limit = GetQueryPage(I('session.ArticleCategory_pageCurrent',1),I('session.ArticleCategory_pageSize',10));
	}

	//数据
    $ArticleCateList =$this->ArticleCateModel->where($where)->order($order)->select();

	$return = array(
		"Page"=>array(
			"total"=>$total,
			"current"=>$limit['current'],
			"size"=>$limit['size'],
			"limit"=>$limit['limit'],
		),
		"ArticleCateList"=>$ArticleCateList,
	);

	return $return;
} // end func



//文章分类列表数据格式化

public function FormatArticleCate($data){
	
	foreach($data as $k=>$v){
		$mark_arr = explode('-',$v['articlecate_parent_mark']);
		$data[$k]['pid'] = $mark_arr[count($mark_arr)-1];
		
	}

	$tree = self::GetTree($data,0);

//var_dump($data);
//die;
//var_dump($data);
//die;
	return $tree;
	  
}

//文章分类检索[检索处理层]
public function DoSearch($data=''){
	
		//$this->ajaxReturn($_REQUEST);
		$ArticleCategory_SqlCondition_Str = '';

		foreach($_POST as $k=>$v){
			//检索记录
			session('condition_articlecate.condition_'.$k,$v);

			//检索条件
			switch($k){
				case 'catename':
					if($v)
						$ArticleCategory_SqlCondition_Str .= "articlecate_name like '%".$v."%' and ";
				    break;
				case 'cateid':
					if($v)
						$ArticleCategory_SqlCondition_Str .= "articlecate_id= ".$v." and ";	
				    break;
			}
		}

		$ArticleCategory_SqlCondition_Str = rtrim($ArticleCategory_SqlCondition_Str,"and ");
		session('ArticleCategory_SqlCondition_Str',$ArticleCategory_SqlCondition_Str);
		$returns = AjaxReturnArgs(200,"查询完毕！","ArticleCategory",false);
		//$returns['data'] = array(1,2,3,4);
		//$ArticleCateList = $this->GetArticleCateList();
		$returns['data'] = $ArticleCategory_SqlCondition_Str;
		$this->ajaxReturn($returns);
}


//清空查询条件
public function Doclear(){
	
	$ArticleCategory_SqlCondition_Str = session('ArticleCategory_SqlCondition_Str');
	$condition_articlecate = session('condition_articlecate');

	if(!empty($ArticleCategory_SqlCondition_Str) || !empty($condition_articlecate)){
		//清空检索条件
		session('ArticleCategory_SqlCondition_Str',null);
		//清空检索记录
		session('condition_articlecate',null);

		$returns = AjaxReturnArgs(200,"已成功清空查询！","ArticleCategory",false);
	
	}else{
		$returns = AjaxReturnArgs(300,"检索条件已经为空,无需重复操作","ArticleCategory",false);
	}
	$this->ajaxReturn($returns);
}



//字段排序
public function DoOrder(){
	
	session('ArticleCategory_orderField',I('post.orderField','articlecate_id'));
	session('ArticleCategory_orderDirection',I('post.orderDirection','desc'));
	session('ArticleCategory_pageSize',I('post.pageSize'));
	session('ArticleCategory_pageCurrent',I('post.pageCurrent'));

	$this->index();
}


//生成树
static public function GetTree(&$list,$pid=0,$level=0,$html='─'){
	
    static $tree = array();
    foreach($list as $v){
        if($v['pid'] == $pid){
            $v['sort'] = $level;//枝杈阶数
			$v['html'] = ($v['pid'] == '0')?"├":"├".str_repeat($html, $level)."┴";
            $tree[] = $v;
            self::GetTree($list,$v['articlecate_id'],$level+1);
        }
    }
    return $tree;
}



}