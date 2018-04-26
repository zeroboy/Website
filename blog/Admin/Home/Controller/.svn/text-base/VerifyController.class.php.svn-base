<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 验证码 
 */


namespace Home\Controller;
use Think\Controller;
class VerifyController extends Controller {
	
	private static $BaseVerifyObject;

	public function __construct(){
	
		parent::__construct();
		self::$BaseVerifyObject = new \Think\Verify();
	}

	//创建验证码
	public function CreateVerify(){
	
		self::$BaseVerifyObject->length  = 5;
		self::$BaseVerifyObject->fontttf = '5.ttf'; 
		self::$BaseVerifyObject->useNoise = true;
		self::$BaseVerifyObject->entry(I('get.unique'));
	}

	//验证验证码
	public function CheckVerify($verify,$unique){
	
		if(!self::$BaseVerifyObject->check($verify,$unique)){  
			$this->error("亲，验证码输错了哦！",$this->site_url,9);  
		} 
	}



}

?>