<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiInnerSystem\proxy;


use Exception;
use Yii;
use yii\caching\CacheInterface;
use yii\di\Instance;
use yii\httpclient\Response;
use YiiHelper\features\system\models\Systems;
use YiiHelper\helpers\client\Client;
use YiiHelper\helpers\client\SystemProxy;
use YiiHelper\helpers\Req;
use Zf\Helper\Crypt\Openssl;
use Zf\Helper\Exceptions\CustomException;
use Zf\Helper\Exceptions\ParameterException;

/**
 * 代理类 : 内部系统转发代理组件
 *
 * Class InnerProxy
 * @package YiiHelper\helpers\client
 */
class InnerProxy extends SystemProxy
{
    /**
     * @var bool 是否开启token验证
     */
    public $enableToken = false;
    /**
     * @var string 请求访问 UUID
     */
    public $uuid;
    /**
     * @var string 加密 token 时需要的 openssl_public_key
     */
    public $publicKey;
    /**
     * @var int 接受服务区url的有效时间
     */
    public $urlExpireTtl = 120;
    /**
     * @var string 开启token 时获取 token 的url
     */
    public $tokenUrl = 'oauth/token';
    /**
     * @var CacheInterface
     */
    public $cache = 'cache';
    /**
     * @var Client | array client的配置或实例
     */
    public $client = [
        'class'                 => Client::class,
        'translateHeaderPrefix' => 'x-',
        'unTranslateHeaders'    => [
            'x-system', // InnerClient 中处理
            'x-from-system', // InnerClient 中处理
            'x-trace-id', // InnerClient 中处理
            'x-access-uuid', // 组件自处理
            'x-access-token', // 组件自处理
        ],
    ];

    /**
     * @inheritDoc
     *
     * @throws ParameterException
     * @throws Exception
     */
    public function init()
    {
        parent::init();
        // 确保缓存组件
        $this->cache = Instance::ensure($this->cache, CacheInterface::class);
        // 添加透传的 header
        $this->client->setHeaders([
            'x-forwarded-for'   => Req::getUserIp(),
            'x-portal-is-guest' => Req::getIsGuest(),
            'x-portal-uid'      => Req::getUid(),
        ]);
    }

    /**
     * 设置系统模型
     *
     * @param mixed $system
     * @return $this
     * @throws ParameterException
     */
    public function setSystem($system)
    {
        if (is_string($system) && !empty($system)) {
            $system = Systems::getCacheSystem($system);
        }
        if (!$system instanceof Systems) {
            throw new ParameterException("设置系统参数错误");
        }
        $this->system = $system;
        // 设置client的相关信息
        $this->client->baseUrl    = $system->uri_prefix;
        $this->client->systemCode = $system->code;
        $this->enableToken        = boolval($this->system->getExtValueByKey('enableToken', false));
        $this->uuid               = $this->system->getExtValueByKey('uuid');
        $this->publicKey          = $this->system->getExtValueByKey('publicKey');
        $this->urlExpireTtl       = $this->system->getExtValueByKey('urlExpireTtl', 120);
        $this->tokenUrl           = $this->system->getExtValueByKey('tokenUrl', 'oauth/token');
        return $this;
    }

    /**
     * 转发系统，获取响应结果
     *
     * @param bool $parsed
     * @return Response
     * @throws Exception
     */
    public function transmit()
    {
        $this->client->setHeader('x-access-uuid', $this->uuid);
        // 添加访问token
        if ($this->enableToken) {
            $this->client->setHeader('x-access-token', $this->getToken());
        }
        if ($this->isFormData()) {
            // 可以文件上传
            $files    = $this->getUploadedFiles();
            $response = $this->send($this->getPathInfo(), $this->getParams(), 'POST', $files);
            $this->unlinkUploadedFiles($files);
        } else {
            $response = $this->send($this->getPathInfo(), $this->getParams(), $this->getMethod());
        }
        return $response;
    }

    /**
     * 获取系统访问 token
     *
     * @return mixed
     * @throws Exception
     */
    protected function getToken()
    {
        $cacheKey = "innerProxy:token:" . Yii::$app->id . ":{$this->system->code}:{$this->uuid}";
        if (false === ($token = $this->cache->get($cacheKey))) {
            $this->client->setHeader('x-access-uuid', $this->uuid);
            $response = $this->send($this->tokenUrl, [
                'sign' => Openssl::encrypt($this->publicKey, [
                    'timestamp'    => time(),
                    'urlExpireTtl' => $this->urlExpireTtl,
                ]),
            ]);
            $data     = $this->parseResponse($response);
            $token    = $data['data']['token'];
            $this->cache->set($cacheKey, $token, $data['data']['expireTtl'] - 300);
        }
        return $token;
    }

    /**
     * 解析请求响应
     *
     * @param Response $response
     * @return mixed
     * @throws CustomException
     */
    public function parseResponse(Response $response)
    {
        $data = $response->getData();
        if (0 == $data['code']) {
            return $data;
        }
        throw new CustomException($data['msg'], $data['code']);
    }
}