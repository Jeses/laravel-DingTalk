<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午4:24
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk\Templates;

/**
 * Class Markdown
 * Markdown 类型消息
 * @package Zhengcai\DingTalk\Templates
 * @Date    : 2021/6/3 下午4:27
 * @Author  :青山
 * @Email   :<yz_luck@163.com>
 */
class Markdown extends Base
{
	public function __construct($title = '',$content = '')
	{
		$this->message  = [
			'msgtype' => 'markdown',
			'markdown' => [
				'title' => $title,	// 首屏会话透出的展示内容
				'text' => $content	// markdown格式的消息
			]
		];
	}
}