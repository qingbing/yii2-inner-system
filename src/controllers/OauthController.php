<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiInnerSystem\controllers;


use Exception;
use yii\web\Application;
use YiiHelper\abstracts\RestController;
use YiiInnerSystem\components\OauthTokenManager;
use Zf\Helper\Exceptions\CustomException;

/**
 * 控制器 : 系统访问token控制
 * 作用域
 *      - 后管(控制token的生成)
 *
 * Class OauthController
 * @package YiiInnerSystem\controllers
 */
class OauthController extends RestController
{
    /**
     * @return array
     * @throws Exception
     */
    public function actionToken()
    {
        $token = $this->getToken();
        if (null === $token) {
            throw new CustomException('模块「' . $this->action->controller->module->id . '」没有配置token校验器');
        }
        return $this->success($token->generateToken(), '获取访问token');
    }

    /**
     * 获取使用于当前 module 的 token 验证组件
     *
     * @return OauthTokenManager|void
     * @throws \yii\base\InvalidConfigException
     */
    protected function getToken()
    {
        $module = $this->action->controller->module;
        while (true) {
            if ($module->has('token')) {
                return $module->get('token');
            }
            $module = $module->module;
            if ($module instanceof Application) {
                return;
            }
        }
    }
}