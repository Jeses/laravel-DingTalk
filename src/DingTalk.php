<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/3 下午2:36
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Ixudra\Curl\Facades\Curl;
use Zhengcai\RobotDingTalk\Templates\ActionCard;
use Zhengcai\RobotDingTalk\Templates\FeedCard;
use Zhengcai\RobotDingTalk\Templates\Link;
use Zhengcai\RobotDingTalk\Templates\Markdown;
use Zhengcai\RobotDingTalk\Templates\Text;

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

		$this->client = $this->creatClient();
	}

	/**
	 * 创建客户端请求
	 * @return Client
	 * @Date  : 2021/6/3 下午3:10
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	protected function creatClient()
	{
		$clent = new Client([
			'timeout' => $this->timeOut,
		]);
		return $clent;
	}

	/**
	 * 文本消息
	 * @param string $content
	 * @return $this
	 * @Date  : 2021/6/3 下午5:02
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function text($content = '')
	{
		$this->message = new Text($content);
		return $this;
	}

	/**
	 * link消息类型
	 * @param string $title      消息标题
	 * @param string $text       消息内容。如果太长只会部分展示
	 * @param string $messageUrl 点击消息跳转的URL
	 * @param string $picUrl     图片URL
	 * @return $this
	 * @Date  : 2021/6/3 下午6:09
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function link($title, $text, $messageUrl, $picUrl = '')
	{
		$this->message = new Link($title, $text, $messageUrl, $picUrl);
		return $this;
	}

	/**
	 * 消息类型 固定为：markdown
	 * @param string $title    首屏会话透出的展示内容
	 * @param string $markdown markdown格式的消息
	 * @return $this
	 * @Date  : 2021/6/3 下午6:08
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function markDown($title, $markdown)
	{
		$this->message = new Markdown($title, $markdown);
		return $this;
	}

	/**
	 * 消息类型为固定actionCard
	 * @param string $title    首屏会话透出的展示内容
	 * @param string $markdown markdown格式的消息
	 * @param int    $hideAvatar
	 * @param int    $btnOrientation
	 * @return $this
	 * @Date  : 2021/6/3 下午6:03
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function actionCard($title, $markdown, $hideAvatar = 0, $btnOrientation = 0)
	{
		$this->message = new ActionCard($title, $markdown, $hideAvatar, $btnOrientation);
		return $this;
	}

	/**
	 * 消息类型为固定actionCard 增加按钮使用
	 * @param string $title 按钮标题
	 * @param string $url   点击按钮触发的URL
	 * @return $this
	 * @Date  : 2021/6/3 下午6:02
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function actionSingle($title = '', $url = '')
	{
		if (!empty($this->message)) $this->message->single($title, $url);
		return $this;
	}

	/**
	 * 此消息类型为固定feedCard
	 * @param string $title      单条信息文本
	 * @param string $messageUrl 点击单条信息到跳转链接
	 * @param string $picUrl     单条信息后面图片的UR
	 * @return $this
	 * @Date  : 2021/6/3 下午6:00
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function feedLink($title = '', $messageUrl = '', $picUrl = '')
	{
		if (empty($this->message)) $this->message = new FeedCard();
		$this->message->addLinks($title, $messageUrl, $picUrl);
		return $this;
	}

	/**
	 * 是否 AT 全部人
	 * @param bool $all true|false
	 * @return $this
	 * @Date  : 2021/6/3 下午5:58
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function isAll($all = false)
	{
		if ($this->message) {
			$this->message->isAtAll($all);
		} else {
			$this->isAtAll = eval("return $all;");
		}
		return $this;
	}

	/**
	 * 以钉钉用户ID AT人
	 * @param array $userId 用户ID 数组
	 * @return $this
	 * @Date  : 2021/6/3 下午5:57
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function atUserId($userId = [])
	{
		if ($this->message) {
			$this->message->userIdAt($userId);
		} else {
			$this->atUserIds = $userId;
		}
		return $this;
	}

	/**
	 * 以手机AT
	 * @param array $mobile 手机号数组
	 * @return $this
	 * @Date  : 2021/6/3 下午5:57
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function atMobiles($mobile = [])
	{
		if ($this->message) {
			$this->message->mobilesAt($mobile);
		} else {
			$this->atMobiles = $mobile;
		}
		return $this;
	}

	/**
	 * 请求接口
	 * @param string $method
	 * @param array  $headers
	 * @return array
	 * @throws \Exception
	 */
	public function request($headers = ['Content-Type' => 'application/json'], $method = 'post')
	{
		$params = $this->message->getBody();
		$this->sign($params);
		$request = $this->client->$method($this->hookUrl, [
			'body'    => json_encode($params),
			'headers' => $headers,
			'verify'  => $this->sslVerify,
		]);

		$result = $request->getBody()->getContents();
		return json_decode($result, true) ?? [];
	}

	/**
	 * 如果开启签名加密则执行
	 * @param $data
	 * @Date  : 2021/6/3 下午3:31
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
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

	public function __call($name, $arguments)
	{
		$name = strtolower($name);
		if (!in_array($name, ['get', 'post', 'put', 'patch', 'delete']))
			throw new \Exception('undefined method.');
		return retry($this->_retryTimes, function () use ($name, $arguments) {
			return $this->request($arguments[0], $name);
		}, 0);
	}
}