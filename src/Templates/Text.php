<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午4:22
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk\Templates;

/**
 * Class Text
 * 消息类型为文本形式
 * @package Zhengcai\RobotDingTalk\Templates
 * @Date    : 2021/6/3 下午4:23
 * @Author  :青山
 * @Email   :<yz_luck@163.com>
 */
class Text extends Base
{
	public function __construct($content)
	{
		$this->message = [
			'msgtype' => 'text',
			'text' => [
				'content' => $content //消息内容
			]
		];
	}
}