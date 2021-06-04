<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/4 下午2:59
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk\Facades;


use Illuminate\Support\Facades\Facade;

class DingTalk extends Facade
{
	/**
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'DingTalk';
	}
}