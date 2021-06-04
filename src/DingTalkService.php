<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午2:55
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
	 * @return mixed
	 * @throws \Exception
	 * @Date  : 2021/6/3 下午3:01
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function server($serverName)
	{
		if (!isset($this->_services[$serverName])) {
			$config = config('DingTalk.' . $serverName);
			if (empty($config))
				throw new \Exception('DingTalk server config is not exist.');
			$this->_services[$serverName] = new DingTalk($config);
		}
		return $this->_services[$serverName];
	}

}