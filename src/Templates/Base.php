<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午3:51
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk\Templates;


abstract class Base
{
	/**
	 * @var array
	 */
	protected $message = [];

	/**
	 * @var array
	 */
	protected $atMobiles = [];

	/**
	 * @var array
	 */
	protected $atUserIds = [];

	/**
	 * @var bool
	 */
	protected $isAtAll = false;

	protected $atList = [];

	/**
	 * 获取消息
	 * @return array
	 * @Date  : 2021/6/3 下午3:56
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * 以手机号形式at人
	 * @param array $mobiles
	 * @return array
	 * @Date  : 2021/6/3 下午4:01
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function mobilesAt(array $mobiles)
	{
		return $this->atMobiles = array_merge($this->atMobiles, $mobiles);
	}

	/**
	 * 以用户ID形式at人
	 * @param array $uid
	 * @return array
	 * @Date  : 2021/6/3 下午4:06
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function userIdAt(array $uid)
	{
		return $this->atUserIds = array_merge($this->atMobiles, $uid);
	}

	/**
	 * at 所有人
	 * @param bool $atAll
	 * @return mixed
	 * @Date  : 2021/6/3 下午4:09
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function isAtAll($atAll = false)
	{
		return $this->isAtAll = eval("return $atAll;");
	}

	public function setAt($mobiles, $userIds, $isAll)
	{
		$this->atMobiles = $mobiles;
		$this->atUserIds = $userIds;
		$this->isAtAll = $isAll;
	}

	/**
	 * 组装at数据
	 * @return $this
	 * @Date  : 2021/6/3 下午4:18
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function makeAt()
	{
		$this->atList = [
			'at' => [
				'isAtAll' => $this->isAtAll,
			],
		];
		if (!empty($this->atMobiles))
			$this->atList['at']['atMobiles'] = $this->atMobiles;
		if (!empty($this->atUserIds))
			$this->atList['at']['atUserIds'] = $this->atUserIds;

		return $this;
	}

	public function justData()
	{
		$array = [];
		if (!empty($this->atMobiles)) $array = array_merge($array,$this->atMobiles);
		if (!empty($this->atUserIds)) $array = array_merge($array,$this->atUserIds);
		if ($this->message['msgtype'] == 'text' && !empty($array)) {
			foreach ($array as $user) {
				$this->message['text']['content'] .= ' @' . $user;
			}
		} elseif ($this->message['msgtype'] == 'markdown' && !empty($array)) {
			$this->message['markdown']['text'] .= "\n\n";
			foreach ($array as $user) {
				$this->message['markdown']['text'] .= ' @' . $user;
			}
			$this->message['markdown']['text'] .= "\n";
		}
	}

	/**
	 * 组装主要数据
	 * @return array
	 * @Date  : 2021/6/3 下午4:20
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function getBody()
	{
		$this->makeAt();
		$this->justData();
		return $this->message + $this->atList;
	}
}