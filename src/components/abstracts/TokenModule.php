<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiInnerSystem\components\abstracts;


use yii\base\Module;
use yii\web\Application;
use YiiInnerSystem\components\OauthTokenManager;
use Zf\Helper\Exceptions\CustomException;

/**
 * 抽象类 : 带有token检查的基类，继承该类的模块必须配置 token 组件
 *
 * Class TokenModule
 * @package YiiInnerSystem\components\abstracts
 */
abstract class TokenModule extends Module
{
    private $_token;

    /**
     * 获取当前模块的token检验组件
     *
     * @return OauthTokenManager|null
     * @throws CustomException
     * @throws \yii\base\InvalidConfigException
     */
    protected function getToken()
    {
        if (null === $this->_token) {
            $module = $this;
            while (true) {
                if ($module->has('token')) {
                    return $module->get('token');
                }
                $module = $module->module;
                if ($module instanceof Application) {
                    throw new CustomException('模块「' . $this->action->controller->module->id . '」没有配置token校验器');
                }
            }
        }
        return $this->_token;
    }

    /**
     * 模块初始化后执行函数
     *
     * @throws CustomException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->getToken()->checking();
    }
}