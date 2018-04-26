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

class ToolController extends Controller{
     static public $treeList = array(); //存放无限分类结果如果一页面有多个无限分类可以使用 Tree::$treeList = array(); 清空
    /**
     * 无限级分类
     * access public
     * @param Array $data        //数据库里获取的结果集
     * @param Int $pid
     * @param Int $count        //第几级分类
     * return Array $treeList
     */
    static public function tree(&$data,$pid = 0, $count = 0) {
        foreach ($data as $key => $value){
            if($value['pid']==$pid){
                $value['level'] = $count;
                //对标题进行格式化
                $value['title'] = ($value['pid'] == 0)?'├':'├'.str_repeat('─', ($count-1)).'┴'.$value['title'];
                self::$treeList [] = $value;
                unset($data[$key]);
                self::tree($data,$value['id'],$count + 1);
            } 
        }
        return self::$treeList ;
    }


	public function index(){
		
		header("Content-type:text/html;charset=utf-8");
		$cate = M('test_cate');
		$limit = "0,5";
        $list = $cate->order('pid ASC,id ASC')->select();
		
		$row = self::tree($list);
		//var_dump($list);
		$limit_arr = explode(',',$limit);
		for($i=$limit_arr[0];$i<=($limit_arr[1]-1);$i++){
			if($row[$i]){
				$all[] = $row[$i];
			}
		}
		//var_dump($all);
//die;
		foreach($all as $v){
			echo $v['title'].$v['name'].'<br />';
		}
	}
    
}


?>