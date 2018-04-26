<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 基类控制器
 */


namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {

 
/**
 * 初始化.
 * @param   
 * @return
 * @access  
 * @static  
 */
public function __construct(){
    
	parent::__construct();
	self::IsLogin();
	self::IsLoginTimeOut();
} // end func

private 
/**
 * 判断是否登录.
 */
function IsLogin()
{
	if(!I('session.userinfo')){
		$this->assign("jumpUrl",U('Login/index','',false));
		$this->error('您还未登录，请先登录！');
	}

} // end func


private 
/**
 * 检测是否登录超时.
 */
function IsLoginTimeOut()
{
    if(time()-I('session.tokentime')>3600){
		unset($_SESSION);
		$this->assign("jumpUrl",U('Login/index','',false));
		$this->error('登录超时，请重新登录！');
	}
} // end func

}
?>