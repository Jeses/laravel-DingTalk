<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午12:20
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

return [
	// 默认发送的机器人
	'default' => [
		'enabled'   => env('DING_ENABLED', true),   // 是否要开启机器人，关闭则不再发送消息
		'token'     => env('DING_TOKEN', ''),       // 机器人的access_token
		'timeOut'   => env('DING_TIME_OUT', 2.0),   // 钉钉请求的超时时间
		'sslVerify' => env('DING_SSL_VERIFY', true),// 是否开启ss认证
		'secret'    => env('DING_SECRET', false),   // 开启安全配置
	],
];