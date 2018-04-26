<?php
// +----------------------------------------------------------------------
// |  Author: MrZero
// +----------------------------------------------------------------------
// |  QQ : 2586073409
// +----------------------------------------------------------------------
// | Motto: People just walking shadow
// +----------------------------------------------------------------------
// | model: 用户分组表模型
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

class UsercateModel extends Model{


	//创建时间
	//private  $_createtime = time();


	//自动验证
    protected $_validate = array(

		array('usercate_name','require','用户分组名称必须填！'), //为空验证
		array('usercate_name','checklen','用户分组名超过10位以上',0,'callback'),
	);


	//自动完成

	protected $_auto = array ( 
			array('create_time','time',1,'function'),  // 新增的时候把status字段设置为1
	);

	//用户分组名 长度验证
	protected function checklen($data){
		if(strlen($data)>=10){
			return false;
		}else{
			return true;
		}
	}



	//自动映射




}