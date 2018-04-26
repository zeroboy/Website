<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 系统管理模块
 */


 namespace Home\Controller;
use Think\Controller;
class SystemController extends Controller {

	public  $config;
	public function __construct(){
	
		parent::__construct();
		
		if(session('userinfo.user_id') == 1){
			$this->config = C();
			foreach($this->config as $k=>$v){
				$this->config[$k] = htmlspecialchars($v);
			}
		}else{
			$this->assign("jumpUrl",U('Content/index','',false));
			$this->error('您对该模块暂无权限！');
		}
	}	

    public function index(){
       
	   
	   $this->assign('sys_config',$this->config);
	   $this->display();
    }


	public function UpdateShow(){
	
		$this->assign('sys_config',$this->config);
		$this->display();
	}

	public function DoUpdate(){
	
		$data = array(
			        //'DB_TYPE'   => I('post.DB_TYPE',''), // 数据库类型
					'DB_HOST'   => I('post.DB_HOST',''), // 服务器地址
					'DB_NAME'   => I('post.DB_NAME',''), // 数据库名
					'DB_PORT'   => I('post.DB_PORT',''), // 端口
					'DB_PREFIX' => I('post.DB_PREFIX',''), // 数据库表前缀 
					//'TMPL_L_DELIM'=>I('post.TMPL_L_DELIM',''),
					//'TMPL_R_DELIM'=>I('post.TMPL_R_DELIM',''),
		);
		
		$datas = $this->FillConfigArgs($data);

		if($datas['status']<0){
			$this->assign("jumpUrl",U('System/UpdateShow','',false));
			$this->error($datas['msg']);	
		}

		if($datas['content']){

			//var_dump($res);
			foreach($datas['content'] as $k=>$v){
				$res = file_get_contents("./Admin/Common/Conf/config.php");
				$vowels = trim($v);
				$check_res = str_replace(C($k), htmlspecialchars_decode($vowels), $res);
				file_put_contents("./Admin/Common/Conf/config.php",$check_res);
			}
			//$this->assign("jumpUrl",U('System/index','',false));
			//$this->success('配置修改成功！');
			$returns = AjaxReturnArgs(200,'配置修改成功！',"System");
			$this->ajaxReturn($returns);
		}
	}

	//配置参数过滤
	private function FillConfigArgs($data){
		
		foreach($data as $k=>$v){
				$data[$k] = htmlspecialchars_decode($v);
		}

		$return = array("status"=>1,"content"=>$data);
		return $return;
	}
}
?>