<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午4:35
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk\Templates;

use Zhengcai\RobotDingTalk\DingTalkService;

/**
 * Class ActionCard
 * ActionCard类型消息
 * @package Zhengcai\RobotDingTalk\Templates
 * @Date    : 2021/6/3 下午4:40
 * @Author  :青山
 * @Email   :<yz_luck@163.com>
 */
class ActionCard extends Base
{
	protected $service;

	public function __construct($title, $markdown, $hideAvatar = 0, $btnOrientation = 0)
	{
		$this->message = [
			'msgtype' => 'actionCard',
			'actionCard' => [
				'title' => $title,
				'text' => $markdown,
				'hideAvatar' => $hideAvatar,
				'btnOrientation' => $btnOrientation
			]
		];
	}

	/**
	 * 点击singleTitle按钮触发的URL。
	 * @param string $title 按钮标题
	 * @param string $url 点击按钮触发的URL
	 * @return $this
	 * @Date  : 2021/6/3 下午4:38
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function single($title = '',$url = ''){
		$this->message['actionCard']['singleTitle'] = $title;
		$this->message['actionCard']['singleURL'] = $url;
		$this->service->setMessage($this);
		return $this;
	}

	/**
	 * 独立跳转按钮
	 * @param $title
	 * @param $url
	 * @return $this
	 * @Date  : 2021/6/3 下午4:39
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function addButtons($title,$url){
		$this->message['actionCard']['btns'][] = [
			'title' => $title,
			'actionURL' => $url
		];
		return $this;
	}
}