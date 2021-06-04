<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午4:28
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk\Templates;

/**
 * Class Link
 * link类型 消息
 * @package Zhengcai\RobotDingTalk\Templates
 * @Date    : 2021/6/3 下午4:29
 * @Author  :青山
 * @Email   :<yz_luck@163.com>
 */
class Link extends Base
{
	public function __construct($title,$text,$messageUrl,$picUrl = '')
	{
		$this->message  = [
			'msgtype' => 'link',
			'link' => [
				'text' => $text,	// 消息内容
				'title' => $title,  // 消息标题
				'picUrl' => $picUrl,// 图片URL
				'messageUrl' => $messageUrl //点击消息跳转的URL
			]
		];
	}
}