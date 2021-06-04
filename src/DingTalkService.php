<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/4 下午4:16
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk;


class DingTalkService
{
	/**
	 * @var array
	 */
	protected $_services;

	/**
	 * @param $serverName
	 * @return DingTalk
	 * @throws \Exception
	 */
	public function server($serverName)
	{
		if (!isset($this->_services[$serverName])) {
			$config = config('dingtalk.' . $serverName);
			if (empty($config))
				throw new \Exception('DingTalk server config is not exist.');
			$this->_services[$serverName] = new DingTalk($config);
		}
		return $this->_services[$serverName];
	}
}