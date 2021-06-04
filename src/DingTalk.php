<?php
/**
 * Created by PhpStorm.
 * @Date  : 2021/6/4 下午4:16
 * @Author:青山
 * @Email :<yz_luck@163.com>
 */

namespace Zhengcai\RobotDingTalk;


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
	 * @var string
	 */
	protected $apiUrl = 'https://oapi.dingtalk.com/robot/send';

	/**
	 * 是否开启推送
	 * @var bool|mixed
	 */
	protected $enabled;
	/**
	 * @var string
	 */
	protected $token;

	/**
	 * 超时时间
	 * @var float|mixed
	 */
	protected $timeOut;
	/**
	 * @var string
	 */
	protected $sslVerify;
	/**
	 * @var string
	 */
	protected $appSecret;
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

	/**
	 * 请求重试次数
	 * @var int
	 */
	protected $_retryTimes = 3;

	public function __construct(array $config)
	{
		$this->enabled = $config['enabled'] ?? true;
		$this->token = $config['token'] ?? '';
		$this->timeOut = $config['timeOut'] ?? 2.0;
		$this->sslVerify = $config['sslVerify'] ?? false;
		$this->appSecret = $config['secret'] ?? false;
	}

	/**
	 * 请求接口
	 * @param array $headers
	 * @param string $method
	 * @return array
	 * @throws \Exception
	 */
	public function send($headers = ['Content-Type' => 'application/json'], $method = 'post')
	{
		$method = strtolower($method);
		if (!in_array($method, ['get', 'post', 'put', 'patch', 'delete']))
			throw new \Exception('undefined method.');

		//组装参数
		$params = $this->message->getBody();
		$this->sign($params);

		// 用法：https://github.com/ixudra/curl
		$curl = Curl::to($this->apiUrl.'?access_token='.$this->token);
		App::environment('local') && $curl->enableDebug(storage_path('logs/ssapi-curl.log'));
		$response = $curl->withData($params)
			->withHeaders($headers)
			->asJson()
			->$method();
		if (empty($response))
			throw new \Exception('request fail.');
		return $response;
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
	 * 消息类型固定为FeedCard类型
	 * @return $this
	 * @Date  : 2021/6/4 下午5:09
	 * @Author:青山
	 * @Email :<yz_luck@163.com>
	 */
	public function feedCard()
	{
		$this->message = new FeedCard();
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
	public function atAll($all = false)
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
	 * 参数签名
	 * @param $data
	 * @return string
	 */
	public function sign(&$data)
	{
		if (isset($data['sign']))
			unset($data['sign']);

		$data['access_token'] = $this->token;
		if (isset($this->secret) && $secret = $this->secret) {
			$timestamp = time() . sprintf('%03d', rand(1, 999));
			$sign = hash_hmac('sha256', $timestamp . "\n" . $secret, $secret, true);
			$data['timestamp'] = $timestamp;
			$data['sign'] = base64_encode($sign);
		}
	}

}