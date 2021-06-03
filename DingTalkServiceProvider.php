<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午2:53
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\DingTalk;

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
			__DIR__ . '/config/DingTalk.php' => config_path('DingTalk.php'),
		]);
	}

	/**
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('DingTalk', function () {
			return new DingTalkService();
		});
	}

	/**
	 * @return array
	 */
	public function provides()
	{
		return ['DingTalk'];
	}
}