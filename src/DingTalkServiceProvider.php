<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/4 下午4:17
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk;


use Illuminate\Support\ServiceProvider;

class DingTalkServiceProvider extends ServiceProvider
{
	/**
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/dingtalk.php' => config_path('dingtalk.php'),
		]);
	}

	/**
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('DingTalk', function () {
			return new DingTalkService();
		}
		);
	}

	/**
	 * @return array
	 */
	public function provides()
	{
		return ['DingTalk'];
	}
}