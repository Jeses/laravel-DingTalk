<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午2:31
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\DingTalk\Facades;

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