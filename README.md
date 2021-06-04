# 钉钉推送机器人消息发送laravel扩展包

### 请先阅读 [钉钉官方文档](https://open-doc.dingtalk.com/microapp/serverapi2/qf2nxq)


# 介绍
robot-ding-talk 是一款钉钉机器人消息发送的Laravel扩展，您可以通过此扩展便捷的发送钉钉消息，进行监控和提醒操作

# 要求
- php版本:>=7.0
- laravel版本: Laravel5.5+
- composer版本: 2.0+


## Installation

通过Composer把这个包载入。

```js

    {
        "require": {
            "zhengcai/DingTalk": "1.*"
        }
    }

```

或者使用终端命令语句:
`composer require zhengcai/DingTalk`

然后复制配置文件

`php artisan vendor:publish --provider="Zhengcai\RobotDingTalk\DingTalkServiceProvider`

# 在laravel项目中使用

安装成功后执行
```php
php artisan vendor:publish --provider="DingNotice\DingNoticeServiceProvider"

```
会自动将`dingtalk.php`配置文件，添加到您项目的配置文件当中

# 相关配置

### 钉钉启用开关
(可选)默认为开启
```php
DING_ENABLED=true
```
### 钉钉的推送token
- (必选)发送钉钉机器人的token，即在您创建机器人之后的access_token
- 钉钉推送链接:https://oapi.dingtalk.com/robot/send?access_token=you-push-token
```php
token=you-push-token
```


### 多机器人配置
如果想要添加多个机器人，则在`ding.php`当中添加机器人名字和相关的配置即可

```php
return [

    'seavice1' => [
      'enabled'   => true,	// 是否要开启机器人，false则不再发送消息
      'token'     => '',  	// 机器人的access_token
      'timeOut'   => 2.0, 	// 钉钉请求的超时时间
      'sslVerify' => true,	// 是否开启SSL认证
      'secret'    => '',  	// 开启安全配置秘钥
    ],

    'seavice2' => [
        'enabled'   => true,	// 是否要开启机器人，false则不再发送消息
        'token'     => '',  	// 机器人的access_token
        'timeOut'   => 2.0, 	// 钉钉请求的超时时间
        'sslVerify' => true,	// 是否开启SSL认证
        'secret'    => '',  	// 开启安全配置秘钥
    ]

];
```
### Laravel 使用说明

```php

    use Zhengcai\RobotDingTalk\Facades\DingTalk;

    $response = DingTalk::server('ServerName')->text()
        ->send($api, $data);

```

### 发送纯文字消息
```php
  $response = DingTalk::server('ServerName')->text('测试数据')->send();
```


```php
  //发送过程以at
  $response = DingTalk::server('ServerName')->atMobiles(['xxxx','xxxx])->atUserId(['xxxx','xxxx])->atAll(true)->send();
```

### 发送链接类型的消息


```php
 
$title = "自定义机器人协议";
$text = "群机器人是钉钉群的高级扩展功能。群机器人可以将第三方服务的信息聚合到群聊中，实现自动化的信息同步。例如：通过聚合GitHub，GitLab等源码管理服务，实现源码更新同步；通过聚合Trello，JIRA等项目协调服务，实现项目信息同步。不仅如此，群机器人支持Webhook协议的自定义接入，支持更多可能性，例如：你可将运维报警提醒通过自定义机器人聚合到钉钉群。";
$picUrl = "";
$messageUrl = "https://open-doc.dingtalk.com/docs/doc.htm?spm=a219a.7629140.0.0.Rqyvqo&treeId=257&articleId=105735&docType=1";

$response = DingTalk::server('ServerName')->link($title,$text,$messageUrl,$picUrl)->send();
```

### 发送markdown类型的消息

```php
  $title = '北京天气';
  $markdown = "#### 北京天气  \n ".
            "> 39度，大风3级，空气良89，相对温度73%\n\n ".
            "> ###### 10点20分发布 [天气](http://www.thinkpage.cn/) ";
            
  DingTalk::server('ServerName')->markDown($title,$markdown)
  ->atMobiles(['xxxx','xxxx])->atUserId(['xxxx','xxxx])->atAll(true);
```

### 发送Action类型的消息

### 发送single类型的消息
```php
$title = "乔布斯 20 年前想打造一间苹果咖啡厅，而它正是 Apple Store 的前身";
$text = "![screenshot](@lADOpwk3K80C0M0FoA) \n".
    " #### 乔布斯 20 年前想打造的苹果咖啡厅 \n\n".
    " Apple Store 的设计正从原来满满的科技感走向生活化，而其生活化的走向其实可以追溯到 20 年前苹果一个建立咖啡馆的计划";

DingTalk::server('ServerName')->actionCard($title,$text,1)
    ->addSingle("阅读全文","https://www.dingtalk.com/")
    ->send()
```
### 发送btns类型的消息

```php
ding()->actionCard($title,$text,1)
    ->addButton("内容不错","https://www.dingtalk.com/")
    ->addButton("不感兴趣","https://www.dingtalk.com/")
    ->send();
```

### 发送Feed类型的消息

```php
$messageUrl = "https://mp.weixin.qq.com/s?__biz=MzA4NjMwMTA2Ng==&mid=2650316842&idx=1&sn=60da3ea2b29f1dcc43a7c8e4a7c97a16&scene=2&srcid=09189AnRJEdIiWVaKltFzNTw&from=timeline&isappinstalled=0&key=&ascene=2&uin=&devicetype=android-23&version=26031933&nettype=WIFI";
$picUrl = "https://www.dingtalk.com";
DingTalk::server('ServerName')->feedLink('时代的火车向前开',$messageUrl,$picUrl)
    ->feedLink('时代的火车向前开',$messageUrl,$picUrl)
    ->send();
```
