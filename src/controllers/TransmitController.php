<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiInnerSystem\controllers;


use Exception;
use Yii;
use yii\filters\VerbFilter;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\system\models\Systems;
use YiiHelper\helpers\AppHelper;
use YiiHelper\helpers\client\SystemProxy;
use YiiInnerSystem\proxy\InnerProxy;
use Zf\Helper\Exceptions\CustomException;
use Zf\Helper\Exceptions\ProgramException;

/**
 * 控制器 : 系统转发
 *
 * Class TransmitController
 * @package YiiPortal\controllers
 */
class TransmitController extends RestController
{
    public function behaviors()
    {
        return [
            'verb' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    '*' => ['POST']
                ]
            ]
        ];
    }

    /**
     * @return mixed|\yii\console\Response|\yii\web\Response
     * @throws Exception
     */
    public function actionIndex()
    {
        if (($systemCode = AppHelper::app()->getSystemAlias()) === AppHelper::app()->id) {
            throw new CustomException("当前系统不需要经过转发");
        }
        // 调用频率太高，这里使用缓存获取，减少db的查询，无逻辑，这里不设置db依赖
        $system = Systems::getCacheSystem($systemCode);
        /* @var Systems $system */
        if ($system->type === Systems::TYPE_INNER) {
            $proxy = Yii::createObject([
                'class'  => InnerProxy::class,
                'system' => $system,
            ]);
        } else if (!Yii::$app->has($system->proxy)) {
            throw new ProgramException("未设置系统「{$systemCode}」代理「{$system->type}」");
        } else {
            $proxy = Yii::$app->get($system->proxy);
        }
        /* @var SystemProxy $proxy */
        // 请求获取响应信息
        $response   = $proxy->transmit();
        $statusCode = intval($response->getStatusCode());
        if ($statusCode >= 300 && $statusCode < 400) {
            return Yii::$app->getResponse()->redirect($response->headers->get('Location'), $statusCode);
        }
        Yii::$app->response->setStatusCode($statusCode);
        foreach ($response->headers as $name => $value) {
            $name = strtolower($name);
            if (strpos($name, 'x-') === 0 || strpos($name, 'content-') === 0) {
                Yii::$app->response->headers->set($name, $value);
            }
        }
        return $proxy->parseResponse($response);
    }
}