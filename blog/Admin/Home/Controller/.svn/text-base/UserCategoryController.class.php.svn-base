<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 用户分组模块
 */

namespace Home\Controller;
use Think\Controller;
class UserCategoryController extends BaseController {
	private $UserCateModel;

	public function __construct(){
		parent::__construct();
		$this->UserCateModel = D('Usercate');
		
	}
    public function index(){

	   $usercatelist = $this->UserCateList();
	   $this->assign("usercatelist", $usercatelist);
	   $this->display("UserCategory/index");
    }

	//用户分组列表
	public function UserCateList(){
	
		$usercatelist = $this->GetUserCateList();
		
		$usercatelist['UserCateList'] = $this->FillUserCateList($usercatelist['UserCateList']);

		return $usercatelist;
	}

	public 
	/**
	 * 获取用户分组列表.
	 * @param $isformat 是否返回处理数据 bool  
	 */
	function GetUserCateList($isformat=true)
	{


		if($isformat){
			//检索
			$where = strip_tags(htmlspecialchars_decode(session('UserCategory_SqlCondition_Str')));

			//排序
			$order = GetQueryOrder(I('session.UserCategory_orderField',"usercate_id"),I('session.UserCategory_orderDirection',"desc"));

			//分页
			$total = intval($this->UserCateModel->where($where)->count());//总数
			$limit = GetQueryPage(I('session.UserCategory_pageCurrent',1),I('session.UserCategory_pageSize',10));
		}

	    $data = $this->UserCateModel->where($where)->order($order)->limit($limit['limit'])->select();


		$return = array(
			"Page"=>array(
				"total"=>$total,
				"current"=>$limit['current'],
				"size"=>$limit['size'],
				"limit"=>$limit['limit'],
			),
			"UserCateList"=>$data,
		);
		return $return;
	} // end func


	//用户分组列表预处理
	private function FillUserCateList($data){
	
		foreach($data as $k=>$v){
		
			$data[$k]['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
		}
		return $data;
	}



	public  
	/**
	 * 用户分组添加展示.
	 */
	function AddShow(){
	    
		$this->display();
	} // end func


	public 
	/**
	 * 添加分组.
	 */
	function DoAdd()
	{
	   
		$data = array(
			"usercate_name"=>I('post.usercatename'),
			"usercate_model"=>I('post.usercate_model',1),
			"create_time"=>"",
		);

		//$res = $this->FillDoAddArgs($data);
		
		if($this->UserCateModel->create($data)){

				if($this->UserCateModel->add($res['content'])){
				 //$this->assign("jumpUrl",U('UserCategory/index'));
				 //$this->success("添加用户分组成功！");
					$returns = AjaxReturnArgs(200,"添加用户分组成功！","UserCategory");
					$this->ajaxReturn($returns);

				}else{
			
					//$this->assign("jumpUrl",U('UserCategory/AddShow'));
					//$this->error("添加用户分组失败! ");
					$returns = AjaxReturnArgs(300,"添加用户分组失败! ","UserCategory");
					$this->ajaxReturn($returns);
				}
		}else{
			//$this->assign("jumpUrl",U('UserCategory/AddShow'));
			//$this->error($res['msg']);
			$returns = AjaxReturnArgs(300,$this->UserCateModel->getError(),"UserCategory");
			$this->ajaxReturn($returns);			
		}
	} // end func


/*
private 
/**
 * 参数校验.
 *//*
function FillDoAddArgs($data)
{
    return array("status"=>1,"content"=>$data);
} // end func
*/


public 
/**
 * 用户分组编辑.
 */
function UpdateShow()
{
    $usercateid = I('get.id');
	$where  = " usercate_id = ".$usercateid;
	$usercateinfo = $this->UserCateModel->where($where)->select();
	$this->assign("usercateinfo",$usercateinfo[0]);
	$this->display("UserCategory/AddShow");
} // end func



//去更新
public function DoUpdate(){

	$usercateid = I('post.usercateid');
	$usercatename = I('post.usercatename');

	$data = array(
		"usercate_name"=>$usercatename,
	);

	//$datas =  $this->FillDoAddArgs($data);

	if($this->UserCateModel->create($data)){

		$where  = " usercate_id = ".$usercateid;
		if($this->UserCateModel->where($where)->save($datas['content'])){

				//$this->assign("jumpUrl",U('UserCategory/index','',false));
				//$this->error("修改用户分组成功! ");
				$returns = AjaxReturnArgs(200,"修改用户分组成功! ","UserCategory");
				$this->ajaxReturn($returns);
		}else{
				//$this->assign("jumpUrl",U('UserCategory/UpdateShow/id/'.$usercateid,'',false));
				//$this->error("修改用户分组失败! ");
				$returns = AjaxReturnArgs(300,"修改用户分组失败! ","UserCategory");
				$this->ajaxReturn($returns);
		}
	}else{

		//$this->assign("jumpUrl",U('UserCategory/UpdateShow/id/'.$usercateid,'',false));
		//$this->error($datas['msg']);	
		$returns = AjaxReturnArgs(300,$this->UserCateModel->getError(),"UserCategory");
		$this->ajaxReturn($returns);
	}

}



//去删除此条数据
public function DoDelete(){

	$usercateid = I('get.id');
	$where  = "user_cate_id=".$usercateid;
	$UserModel = M('user');
	$elementcount = $UserModel->where($where)->count();

	if($elementcount>0){
		//$this->assign("jumpUrl",U('UserCategory/index'));
		//$this->error('对应分组存在用户，请先删除对应用户再删除分组！');
		$returns = AjaxReturnArgs(300,'对应分组存在用户，请先删除对应用户再删除分组！',"UserCategory",false);
		$this->ajaxReturn($returns);
	}else{
	  if($this->UserCateModel->where("usercate_id=".$usercateid)->delete()){
	  
			 //$this->assign("jumpUrl",U('UserCategory/index'));
			 //$this->success("删除用户分组成功！");
			 $returns = AjaxReturnArgs(200,'删除用户分组成功！',"UserCategory",false);
			 $this->ajaxReturn($returns);
		}else{
			//$this->assign("jumpUrl",U('UserCategory/index'));
			//$this->error('删除用户分组失败！');
			 $returns = AjaxReturnArgs(300,'删除用户分组失败！',"UserCategory",false);
			 $this->ajaxReturn($returns);
	  }
	}


}






//文章分类检索[检索处理层]
public function DoSearch($data=''){
	
		//$this->ajaxReturn($_REQUEST);
		$UserCategory_SqlCondition_Str = '';

		foreach($_POST as $k=>$v){
			//检索记录
			session('condition_usercate.condition_'.$k,$v);

			//检索条件
			switch($k){
				case 'usercatename':
					if($v)
						$UserCategory_SqlCondition_Str .= "usercate_name like '%".$v."%' and ";
				    break;
				case 'publish_time':
					if($v)
						$UserCategory_SqlCondition_Str .= "create_time >= ".strtotime($v)." and ";
					break;
			}
		}

		$UserCategory_SqlCondition_Str = rtrim($UserCategory_SqlCondition_Str,"and ");
		session('UserCategory_SqlCondition_Str',$UserCategory_SqlCondition_Str);
		$returns = AjaxReturnArgs(200,"查询完毕！","UserCategory",false);
		//$returns['data'] = array(1,2,3,4);
		//$ArticleCateList = $this->GetArticleCateList();
		$returns['data'] = $UserCategory_SqlCondition_Str;
		$this->ajaxReturn($returns);
}


//清空查询条件
public function Doclear(){
	
	$UserCategory_SqlCondition_Str = session('UserCategory_SqlCondition_Str');
	$condition_articlecate = session('condition_usercate');

	if(!empty($UserCategory_SqlCondition_Str) || !empty($condition_articlecate)){
		//清空检索条件
		session('UserCategory_SqlCondition_Str',null);
		//清空检索记录
		session('condition_usercate',null);

		$returns = AjaxReturnArgs(200,"已成功清空查询！","UserCategory",false);

	}else{

		$returns = AjaxReturnArgs(300,"检索条件已经为空,无需重复操作","UserCategory",false);
	}
	$this->ajaxReturn($returns);
}


//字段排序
public function DoOrder(){
	
	session('UserCategory_orderField',I('post.orderField','usercate_id'));
	session('UserCategory_orderDirection',I('post.orderDirection','desc'));
	session('UserCategory_pageSize',I('post.pageSize'));
	session('UserCategory_pageCurrent',I('post.pageCurrent'));

	$this->index();
}




}





?>