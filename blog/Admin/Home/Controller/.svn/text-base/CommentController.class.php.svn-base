<?php
/**
 * People Just Walking Dead.
 * 
 * @author  ZeroBoy 
 * @version 1.0
 * @model 评论模块
 */

 namespace Home\Controller;
use Think\Controller;
class CommentController extends Controller {
	
	private $ArticleCommentModel;

	public function __construct(){
		
		parent::__construct();
		$this->ArticleCommentModel = M('communicate');
	}


	//评论数据同步回调方法
	public function CommentDataSync(){
		
		//验签
		if ($this->check_signature($_POST)) {
		
			$data = $this->ArticleCommentModel->field("log_id")->select();

			$log_id = $data[0]['log_id'];

			$limit = C("COMMENT_GET_RANGE");
			$secret = C("COMMENT_SECRET");
			$short_name = C("COMMENT_SHORT_NAME");//多说二级域名[短名称]
			$last_log_id = ($log_id)?$log_id:0;

			$params = array(
				'short_name'=>$short_name,
				'secret' =>	$secret,
				'since_id'=>$last_log_id,
                'limit'  => $limit,
                'order'  => 'asc',		
            );

			$request_args = "?";

			foreach($params as $k=>$v){
				$request_args .= $k."=".$v."&";
			}

			$request_args = substr($request_args,0,strlen($request_args)-1);
            
			$Url = C("COMMENT_REQUEST_URL").$request_args;

			$response =  $this->HttpGetRequest($Url);
			$json_str = $response;
			$obj = json_decode($json_str,true);

			$this->writelog($response);



			foreach($obj['response'] as $k=>$v){

			switch($v['action']){

				case 'create':

						$handledata = array(
							"log_id"=>$v['log_id'],
							"site_id"=>$v['site_id'],
							"user_id"=>$v['user_id'],
							"action"=>$v['action'],
							"mate_thread_id"=>$v['meta']['thread_id'],
							"mate_thread_key"=>$v['meta']['thread_key'],
							"mate_author_id"=>$v['meta']['author_id'],
							"mate_author_key"=>$v['meta']['author_key'],
							"mate_author_name"=>urlencode($v['meta']['author_name']),
							"mate_author_email"=>$v['meta']['author_email'],
							"mate_author_url"=>$v['meta']['author_url'],
							"mate_ip"=>$v['meta']['ip'],
							"mate_created_at"=>$v['meta']['created_at'],
							"mate_message"=>urlencode($v['meta']['message']),
							"mate_status"=>$v['meta']['status'],
							"mate_type"=>$v['meta']['type'],
							"mate_parent_id"=>$v['meta']['parent_id'],
							"mate_agent"=>$v['meta']['agent'],
							"mate_date"=>$v['date'],
							"create_time"=>time()
						);

						$count = $this->ArticleCommentModel->where("mate_post_id=".$v['meta']['post_id'])->count();
						
						if($count>0){
							//$where = "mate_post_id=".$v['meta']['post_id'];
							//$this->ArticleCommentModel->where($where)->save($handledata);

						}else{
							
							$handledata["mate_post_id"] = $v['meta']['post_id'];
							$this->ArticleCommentModel->data($handledata)->add();
						}

				    break;
					case 'delete':

						$handledata = array(
							"action"=>$v['action'],
						);

						$where = "mate_post_id=".$v['meta'][0]." ";

						$this->ArticleCommentModel->where($where)->save($handledata);

					break;

				}
			}

		}else{
			die("非法操作！");
		}
	}



	//多说验签
	private function check_signature($input){

		$secret = C("COMMENT_SECRET");//密钥
		$short_name = C("COMMENT_SHORT_NAME");//多说二级域名[短名称]


		$signature = $input['signature'];
		unset($input['signature']);

		ksort($input);
		$baseString = http_build_query($input, null, '&');
		$expectSignature = base64_encode($this->hmacsha1($baseString, $secret));
		
		if($signature !== $expectSignature){
			
			$response = array(
				"status"=>false
			);		
		}else{
			$response = array(
				"status"=>true,
				"expectSignature"=>$expectSignature
			);
		}
		return $response;
	}


	private function hmacsha1($data, $key) {
		if (function_exists('hash_hmac'))
			return hash_hmac('sha1', $data, $key, true);

		$blocksize=64;
		if (strlen($key)>$blocksize)
			$key=pack('H*', sha1($key));
		$key=str_pad($key,$blocksize,chr(0x00));
		$ipad=str_repeat(chr(0x36),$blocksize);
		$opad=str_repeat(chr(0x5c),$blocksize);
		$hmac = pack(
				'H*',sha1(
						($key^$opad).pack(
								'H*',sha1(
										($key^$ipad).$data
								)
						)
				)
		);
		return $hmac;
	}



	// 模拟GET请求
	private function HttpGetRequest($Url) {
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $Url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		$data = curl_exec ( $curl );
		curl_close ( $curl );
		return $data;
	}


	//日志
	private function writelog($content){
		//数组拼接成字符串
		if(is_array($content)){
			foreach($content as $k => $v){
				$content_temp .= "[".$k."]"."=>[".$v."],";
			}
			$content = $content_temp ;
		}
		$file =  './logs/' . 'responselog_' .date("Ymd",time()).".log";
		$str  = date("Y-m-d H:i:s",time())."|";
		file_put_contents($file,  $str . "|记录数据内容:" . $content  . "\n",  FILE_APPEND);
	}

}
?>