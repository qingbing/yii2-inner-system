<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiInnerSystem\backendControllers\interfaces;


use YiiHelper\services\interfaces\ICurdService;

/**
 * 接口: 授权账号管理
 *
 * Interface IOauthUserService
 * @package YiiInnerSystem\backendControllers\interfaces
 */
interface IOauthUserService extends ICurdService
{
    /**
     * 为授信账号重新分配秘钥
     *
     * @param array $params
     * @return mixed
     */
    public function changeKey(array $params);
}