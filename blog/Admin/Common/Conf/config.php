<?php
return array(

	  //数据库配置信息
        'DB_TYPE'   => 'mysql', // 数据库类型
        'DB_HOST'   => '101.200.190.17', // 服务器地址
        'DB_NAME'   => 'bdm108519251_db', // 数据库名
        'DB_USER'   => 'zeroboy', // 用户名
        'DB_PWD'    => '2514782544sbsyg', // 密码
        'DB_PORT'   => 3306, // 端口
        'DB_PREFIX' => 'blog_', // 数据库表前缀 

		//默认Home
		'DEFAULT_MODULE'  =>  'Home',
		'MODULE_ALLOW_LIST'    =>    array('Home'),

		//模板定界符
		'TMPL_L_DELIM'=>'<!--{',
		'TMPL_R_DELIM'=>'}-->',
		
		//文章限制规则
		"Article_Rule2"=>"length[40~, true];",

		//文章分组添加层级上限
		"ARTICLECATE_ADD_MAX_TOPCLASS"=>6,

		//上传路径
		"UPLOAD_PATH"=>"./Uploads/",

		//多说配置

		//密钥
		"COMMENT_SECRET" => "85623db217b0a03d0e35fe61e813bb6d",
		//多说二级域名
		"COMMENT_SHORT_NAME" => "zeroboy",
		//获取数据范围
		"COMMENT_GET_RANGE" => 200,
		//评论获取请求地址
		"COMMENT_REQUEST_URL" => "http://api.duoshuo.com/log/list.json",


);