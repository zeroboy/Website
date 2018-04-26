<?php
// +----------------------------------------------------------------------
// |  Author: MrZero
// +----------------------------------------------------------------------
// |  QQ : 2586073409
// +----------------------------------------------------------------------
// | Motto: People just walking shadow
// +----------------------------------------------------------------------
// | model: 上传
// +----------------------------------------------------------------------


namespace Home\Controller;
use Think\Controller;

class UploadifyController extends Controller{

	public function __construct(){
		
		 if (isset($_POST['authid'])) {
			session_id($_POST['authid']);
			session('[pause]');
			session('[start]');
		 }
	}


	public function index(){
		
		$this->display("Uploadify/index");
	}

	public function upload(){

		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
		$upload->savePath  =     ''; // 设置附件上传（子）目录

		//文件命名
		$upload->saveName = array('getname','__FILE__');

		// 上传文件 
		$info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			$this->ajaxReturn(array("status"=>-1,"msg"=>$upload->getError()));
		}else{
			//更新情况下清理源数据、文件
			$id =  I('post.id');
			if($id){
				
				$ArticleModel = M('article'); 
				$where = "article_id = ".$id;
				$articledata =  $ArticleModel->where($where)->select();
				$path = C("UPLOAD_PATH").$articledata[0]['article_face_path'];
				unlink($path);
			}

			// 上传成功
			session('uploaddata', $info['Filedata']);
			$this->ajaxReturn(array("status"=>1,"content"=>$info));
		}
	}

}



?>