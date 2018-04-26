<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 用户模块
 */

namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {

	private $UserModel;
	public function __construct(){
		
		parent::__construct();
		$this->UserModel = M('user');
	
	}
    public function index(){
       
	   //var_dump($_SESSION['User_orderField']);
	   //die;
	   $userlist = $this->UserList();
	   $this->assign("userlist",$userlist);
	   $this->display("User/index");
    }

	private 
	/**
	 * 用户列表.
	 */
	function UserList()
	{
	   
		$userlist = $this->GetUserList();
		$userlist = $this->FillUserList($userlist);

		return $userlist;
	} // end func

	private 
	/**
	 * 获取用户列表.
	 * @param   type    $varname    description
	 * @return  type    description
	 * @access  public or private
	 * @static  makes the class property accessible without needing an instantiation of the class
	 */
	function GetUserList($page='')
	{

		//检索
		$where = strip_tags(htmlspecialchars_decode(I("session.User_SqlCondition_Str")));

		//排序
		$order = GetQueryOrder(I('session.User_orderField',"user_id"),I('session.User_orderDirection',"desc"));

		//分页
		$total = intval($this->UserModel->where($where)->count());//总数
		$limit = GetQueryPage(I('session.User_pageCurrent',1),I('session.User_pageSize',10));

		//数据
		$data = $this->UserModel->where($where)->order($order)->limit($limit['limit'])->select();

		$return = array(
		"Page"=>array(
			"total"=>$total,
			"current"=>$limit['current'],
			"size"=>$limit['size'],
			"limit"=>$limit['limit'],
		),
		"UserList"=>$data,
		);
		return $return;
	} // end func


	private 
	/**
	 * 用户列表数据过滤.
	 * @param   type    $varname    description
	 * @return  type    description
	 * @access  public or private
	 * @static  makes the class property accessible without needing an instantiation of the class
	 */
	function FillUserList($data)
	{

		$UserCateModel = M('usercate');
		$UserCateList = $UserCateModel->select();
		foreach( $UserCateList as $k=>$v){
			$usercatearr[$v['usercate_id']] = $v['usercate_name'];
		}
		foreach($data['UserList'] as $k=>$v){
		
			$data['UserList'][$k]['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
			$data['UserList'][$k]['usercate_names'] = $usercatearr[$v['user_cate_id']];
		}
	    return $data;
	} // end func


	//添加用户
	public function AddShow(){
	
		$UserCateModel = M('usercate');
		$UserCateList = $UserCateModel->select();
		$this->assign("UserCateList",$UserCateList);
		$this->display();
	}

	//去添加
	public function DoAdd(){
	
			$data = array(
				"user_cate_id"=> I("post.usercate")	,
				"user_account"=> I("post.username")	,
				"user_pwd"=> I("post.userpwd"),
			);

			$datas = $this->FillUserArgs($data);
			if($datas['status']>0){
				
				if($this->UserModel->data($datas['content'])->add()){
					//$this->assign("jumpUrl",U('User/index'));
					//$this->success('添加用户成功！');
					$returns = AjaxReturnArgs(200,"添加用户成功！","User");
					$this->ajaxReturn($returns);
				}else{
					//$this->assign("jumpUrl",U('User/AddShow'));
					//$this->error('添加用户失败！');
					$returns = AjaxReturnArgs(300,"添加用户失败！","User");
					$this->ajaxReturn($returns);
				}

			}else{
				//$this->assign("jumpUrl",U('User/AddShow'));
				//$this->error($datas['msg']);
				$returns = AjaxReturnArgs(300,$datas['msg'],"User");
				$this->ajaxReturn($returns);
			}

	}


	//参数过滤
	private function FillUserArgs($data){
		
		if(($data['user_cate_id'] == '')||($data['user_cate_id'] == null)||(strlen($data['user_cate_id']) == 0)){
			$return = array("status"=>-1,"msg"=>"用户分类不能为空");
			return $return;
		}

		if(($data['user_account'] == '')||($data['user_account'] == null)||(strlen($data['user_account']) == 0)){
			$return = array("status"=>-1,"msg"=>"用户名称不能为空");
			return $return;
		}

		if(($data['user_pwd'] == '')||($data['user_pwd'] == null)||(strlen($data['user_pwd']) == 0)){
			$return = array("status"=>-1,"msg"=>"用户密码不能为空");
			return $return;
		}

		if(strlen($data['user_account'])>20){
			$return = array("status"=>-1,"msg"=>"用户名长度不符");
			return $return;
		}

		if(!(strlen($data['user_pwd'])>6)&&(strlen($data['user_pwd'])<16)){
			$return = array("status"=>-1,"msg"=>"用户密码长度不符");
			return $return;
		}

		$data['user_pwd'] = md5(strtolower($data['user_pwd']));
		$data['create_time'] = time();
		$return = array("status"=>1,"content"=>$data);
		return $return;

	}

	//编辑
	public function UpdateShow(){
		//var_dump($_REQUEST);
		//die;
		$UserCateModel = M('usercate');
		$UserCateList = $UserCateModel->select();
		$this->assign("UserCateList",$UserCateList);

		$where = "user_id = ".I('get.id');
		$UserInfo = $this->UserModel->where($where)->select();
		$this->assign("UserInfo",$UserInfo[0]);

		$this->display("User/AddShow");
	}


	//修改
	public function DoUpdate(){
	
		$data = array(
			"user_cate_id"=> I("post.usercate")	,
			"user_account"=> I("post.username")	,
			"user_pwd"=> I("post.userpwd"),
		);

		$datas = $this->FillUserArgs($data);
				$user_id = I('post.user_id');
			if($datas['status']>0){
				 $where = " user_id = ".$user_id;
					if($this->UserModel->where($where)->save($datas['content'])){
						//$this->assign("jumpUrl",U('User/index','',false));
						//$this->success('修改用户成功！');
						$returns = AjaxReturnArgs(200,'修改用户成功！',"User");
						$this->ajaxReturn($returns);
					}else{
						//$this->assign("jumpUrl",U('User/UpdateShow','',false)."/id/".$user_id);
						//$this->error('修改用户失败！');
						$returns = AjaxReturnArgs(300,'修改用户失败！',"User");
						$this->ajaxReturn($returns);
					}
			}else{
				//$this->assign("jumpUrl",U('User/UpdateShow','',false)."/id/".$user_id);
				//$this->error($datas['msg']);
				$returns = AjaxReturnArgs(300,$datas['msg'],"User");
				$this->ajaxReturn($returns);
			}
	}


	//删除文章
	public function DoDelete(){
		
		 $user_id = I('get.id');

		  if($this->UserModel->where("user_id=".$user_id)->delete()){
		  
				// $this->assign("jumpUrl",U('User/index'));
				// $this->success("删除用户成功！");
				$returns = AjaxReturnArgs(200,'删除用户成功！',"User",false);
				$this->ajaxReturn($returns);
			}else{
				//$this->assign("jumpUrl",U('User/index'));
				//$this->error('删除用户失败！');
				$returns = AjaxReturnArgs(300,'删除用户失败！',"User",false);
				$this->ajaxReturn($returns);
		  }
	}



	//文章分类检索[检索处理层]
	public function DoSearch($data=''){
		
			$User_SqlCondition_Str = '';
			foreach($_POST as $k=>$v){

				//检索记录
				session('condition_user.condition_'.$k,$v);

				//检索条件
				switch($k){
					case 'username':
						if($v)
							$User_SqlCondition_Str .= "user_account like '%".$v."%' and ";	
						break;
					case 'publish_time':
						if($v)
							$User_SqlCondition_Str .= " create_time >=".strtotime($v)." and ";
						break;
				}
			}
			$User_SqlCondition_Str = rtrim($User_SqlCondition_Str,"and ");
			session('User_SqlCondition_Str',$User_SqlCondition_Str);

			$returns = AjaxReturnArgs(200,"查询完毕！","Article",false);
			$this->ajaxReturn($returns);

	}


	//清空查询条件
	public function Doclear(){
		
		$User_SqlCondition_Str = session('User_SqlCondition_Str');
		$condition_user = session('condition_user');
		if(!empty($User_SqlCondition_Str) || !empty($condition_user)){
			//清空检索条件
			session('User_SqlCondition_Str',null);
			//清空检索记录
			session('condition_user',null);

			$returns = AjaxReturnArgs(200,"已成功清空查询！","Article",false);
		}else{
			$returns = AjaxReturnArgs(300,"检索条件已经为空,无需重复操作","Article",false);
		}

		$this->ajaxReturn($returns);
	}


	//字段排序
	public function DoOrder(){
		
		session('User_orderField',I('post.orderField','user_id'));
		session('User_orderDirection',I('post.orderDirection','desc'));
		session('User_pageSize',I('post.pageSize'));
		session('User_pageCurrent',I('post.pageCurrent'));

		$this->index();
	}



}


?>