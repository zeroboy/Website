<?php
// +----------------------------------------------------------------------
// |  Author: MrZero
// +----------------------------------------------------------------------
// |  QQ : 2586073409
// +----------------------------------------------------------------------
// | Motto: People just walking shadow
// +----------------------------------------------------------------------
// | model: 
// +----------------------------------------------------------------------


namespace Home\Controller;
use Think\Controller;

class TreeController extends Controller{

    public $i =-1;
	public $infos;
	public function __construct(){
	
		parent::__construct();
		//$this->infos = array(array("id"=>1,'name'=>"中国","category_id"=>0),array("id"=>2,'name'=>"北京","category_id"=>1),array("id"=>3,'name'=>"上海","category_id"=>1),array("id"=>4,'name'=>"美国","category_id"=>0),array("id"=>5,'name'=>"纽约","category_id"=>4),array("id"=>6,'name'=>"华盛顿","category_id"=>4),array("id"=>7,'name'=>"加州福尼亚","category_id"=>4),array("id"=>8,'name'=>"北京市通州区","category_id"=>2));


		$articlecatemodel = new ArticleCategoryController;
		$ArticleCateList = $articlecatemodel->GetArticleCateList(false);
		$ArticleCateList['ArticleCateList'] = $this->FormartArticleCate($ArticleCateList['ArticleCateList']);
		$this->infos = $ArticleCateList['ArticleCateList'];
/*
		$this->infos = array(
				array("id"=>1,"name"=>"PHP","pid"=>0,"sort"=>1),
				array("id"=>2,"name"=>"Javascript","pid"=>0,"sort"=>2),
				array("id"=>3,"name"=>"MySQL","pid"=>0,"sort"=>3),
				array("id"=>4,"name"=>"php类","pid"=>1,"sort"=>1),
				array("id"=>5,"name"=>"smarty","pid"=>1,"sort"=>2),
				array("id"=>6,"name"=>"私有方法","pid"=>4,"sort"=>1),
				array("id"=>7,"name"=>"jQuery","pid"=>2,"sort"=>1),

		);*/

	}

	public function index(){
		header("content-type:text/html;charset=utf-8;");
		//$this->index2($this->infos);

		$tree = self::index4($this->infos);
		//var_dump($this->infos);
		dump($tree);
	}

     public function index2($array){
         $this->i++;
         if($array[$this->i]['category_id']==0){
             echo "├".$array[$this->i]["articlecate_name"]."<br />";
             for($j=0;$j<  count($array);$j++){
                if($array[$j]['pid']==$array[$this->i]['articlecate_id']){     //查看所属分类
                     echo "├─┴".$array[$j]["articlecate_name"]."<br />";
                }
             }
         }

         foreach($array as $v){
             if($v['pid']==0)
             $ary[]=$v['pid'];        //获取几个顶级栏目，来确定递归几次
         }
         
         if($this->i<=count($ary))            
              $this->index($array);            //递归
     }



	 static public function sortOut($cate,$pid=0,$level=0,$html='—'){
         $tree = array();
         foreach($cate as $v){
             if($v['pid'] == $pid){
                  $v['level'] = $level + 1;
				  if($v['pid'] == '0'){
						$v['html'] = "├";
				  }else{
						$v['html'] = "├".str_repeat('', $level)."┴";
				  }
                  $tree[] = $v;
                  $tree = array_merge($tree, self::sortOut($cate,$v['articlecate_id'],$level+1,$html));
             }
         }
         return $tree;
   }


   static public function index4(&$list,$pid=0,$level=0,$html='─'){
	
    static $tree = array();
    foreach($list as $v){
        if($v['pid'] == $pid){
            $v['sort'] = $level;
            if($v['pid'] == '0'){
					$v['html'] = "├";
			  }else{
					$v['html'] = "├".str_repeat($html, $level)."┴";
			  }
            $tree[] = $v;
            self::index4($list,$v['articlecate_id'],$level+1);
        } 
    }
    return $tree;

   }


   private function FormartArticleCate($data){
	
		foreach($data as $k=>$v){
			
			$mark_arr = explode('-',$v['articlecate_parent_mark']);
			$data[$k]['pid'] = $mark_arr[count($mark_arr)-1];
			$data[$k]['sort'] = count($mark_arr);
			//unset($data[$k]['articlecate_parent_mark']);
			unset($data[$k]['sort']);
		}

		return $data;
   }


}
 
?>