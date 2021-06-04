<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/4 下午3:00
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class DingTalk
{
	/**
	 * @var
	 */
	protected $client;
	/**
	 * @var string
	 */
	protected $hookUrl = "https://oapi.dingtalk.com/robot/send";

	/**
	 * @var string
	 */
	protected $accessToken;

	/**
	 * @var mixed
	 */
	protected $enable;

	/**
	 * @var float|mixed
	 */
	protected $timeOut;

	/**
	 * @var mixed
	 */
	protected $sslVerify;

	/**
	 * @var mixed
	 */
	protected $secret;

	/**
	 * 请求重试次数
	 * @var int
	 */
	protected $_retryTimes = 3;

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

	public function __construct(array $config)
	{
		$this->enable = $config['DING_ENABLED'];
		$this->accessToken = $config['DING_TOKEN'];
		$this->timeOut = $config['DING_TIME_OUT'] ?? 2.0;
		$this->sslVerify = $config['DING_SSL_VERIFY'];
		$this->secret = $config['DING_SECRET'];
	}

	/**
	 * 请求接口
	 * @param $api
	 * @param array $data
	 * @param string $method
	 * @param array $headers
	 * @return array
	 * @throws \Exception
	 */
	public function request($api, $data = [], $headers = [], $method = 'get')
	{
		$this->sign($data);
		// 用法：https://github.com/ixudra/curl
		$curl = Curl::to($this->hookUrl . '/' . trim($api, '/'));
		App::environment('local') && $curl->enableDebug(Storage::path('logs/ssapi-curl.log'));
		$response = $curl->withData($data)
			->withHeaders($headers)
			->asJson()
			->$method();
		if (empty($response))
			//throw new \Exception('request fail.');
		return $response;
	}


	/**
	 * @param $name
	 * @param $arguments
	 * @return array
	 * @throws \Exception
	 */
	public function __call($name, $arguments)
	{
		$name = strtolower($name);
		if (!in_array($name, ['get', 'post', 'put', 'patch', 'delete']))
			throw new \Exception('undefined method.');
		return retry($this->_retryTimes, function () use ($name, $arguments) {
			return $this->request($arguments[0], $arguments[1] ?? [], $name, $arguments[2] ?? []);
		}, 0);
	}

	/**
	 * 参数签名
	 * @param $data
	 * @return string
	 */
	public function sign(&$data)
	{
		if (isset($data['sign']))
			unset($data['sign']);

		$data['access_token'] = $this->accessToken;
		if (isset($this->secret) && $secret = $this->secret) {
			$timestamp = time() . sprintf('%03d', rand(1, 999));
			$sign = hash_hmac('sha256', $timestamp . "\n" . $secret, $secret, true);
			$data['timestamp'] = $timestamp;
			$data['sign'] = base64_encode($sign);
		}
	}

}