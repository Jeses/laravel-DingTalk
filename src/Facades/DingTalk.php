<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/4 下午4:15
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk\Facades;

use Illuminate\Support\Facades\Facade;

class DingTalk extends Facede
{
	/**
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'DingTalk';
	}
}