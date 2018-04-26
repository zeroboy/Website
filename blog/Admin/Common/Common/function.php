<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 公用函数 2015/12/1
 */



 /**
 * ajax参数返回.
* @param   statusCode   int 	必选。状态码(ok = 200, error = 300, timeout = 301)，可以在BJUI.init时配置三个参数的默认值。
* @param   message      string 	可选。信息内容。
* @param   tabid		string 	可选。待刷新navtab id，多个id以英文逗号分隔开，当前的navtab id不需要填写，填写后可能会导致当前navtab重复刷新。
* @param   dialogid     string 	可选。待刷新dialog id，多个id以英文逗号分隔开，请不要填写当前的dialog id，要控制刷新当前dialog，请设置dialog中表单的reload参数。
* @param   divid		string 	可选。待刷新div id，多个id以英文逗号分隔开，请不要填写当前的div id，要控制刷新当前div，请设置该div中表单的reload参数。
* @param   closeCurrent boolean 	可选。是否关闭当前窗口(navtab或dialog)。
* @param   forward      string 	可选。跳转到某个url
* @param   forwardConfirm    string 	可选。跳转url前的确认提示信息。
* @return				array
*/
function AjaxReturnArgs($statusCode,$message,$tabid,$closeCurrent=true,$dialogid="",$divid="",$forward="",$forwardConfirm=""){
	
	$data = array(
			"statusCode" => (int)$statusCode,
			"message"	=>trim($message),
			"tabid"	=>trim($tabid),
			"dialogid"=>trim($dialogid),
			"divid"=>trim($divid),
			"closeCurrent"=>$closeCurrent,
			"forward"=>$forward,
			"forwardConfirm"=>$forwardConfirm,
	);
	
	return $data;
}

/**
 * 处理拼接排序.
 * @param   
 * @return		string
 */
function GetQueryOrder($orderField,$orderDirection){
	
	$Order = $orderField." ".$orderDirection;

	return $Order;
}


/**
 * 处理拼接分页.
 * @param   current  当前页
 * @param   size	 单页条数  
 * @return	array 
 */
function GetQueryPage($current,$size){
	
	$offset = ($current-1)*$size;//偏移量
	$offset_length = $size;
	$limit = $offset.",".$offset_length;

	return array(
			"limit"=>$limit,
			"current"=>$current,
			"size"=>$size	
		);
}




function getname($file){
	
	$name = explode('.',$file);
	return md5($name[0].date("Ymd",time()));
}





?>