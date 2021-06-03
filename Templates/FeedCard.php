<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午4:31
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\DingTalk\Templates;

use Zhengcai\DingTalk\DingTalkService;

/**
 * Class FeedCard
 * FeedCard类型消息
 * @package Zhengcai\DingTalk\Templates
 * @Date    : 2021/6/3 下午4:41
 * @Author  :青山
 * @Email   :<yz_luck@163.com>
 */
class FeedCard extends Base
{
	protected $service;

	public function __construct()
	{
		$this->message = [
			'feedCard' => [
				'links' => []
			],
			'msgtype' => 'feedCard'
		];

	}

	/**
	 * 添加跳转链接
	 * @param string $title  单条信息文本
	 * @param string $messageUrl 点击单条信息到跳转链接
	 * @param string $picUrl 单条信息后面图片的URL
	 * @return $this
	 * @Date  : 2021/6/3 下午4:41
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function addLinks($title = '',$messageUrl = '',$picUrl = ''){
		$this->message['feedCard']['links'][] = [
			'title' => $title,
			'messageURL' => $messageUrl,
			'picURL' => $picUrl
		];
		return $this;
	}
}