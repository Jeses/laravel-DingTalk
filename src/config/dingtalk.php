<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/4 下午4:14
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

return [
	// 默认发送的机器人
	'default' => [
		'enabled'   => true,	// 是否要开启机器人，false则不再发送消息
		'token'     => '',  	// 机器人的access_token
		'timeOut'   => 2.0, 	// 钉钉请求的超时时间
		'sslVerify' => true,	// 是否开启SSL认证
		'secret'    => '',  	// 开启安全配置秘钥
	],
];