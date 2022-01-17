<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiInnerSystem\backendControllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\validators\SecurityOperateValidator;
use YiiInnerSystem\backendControllers\interfaces\IOauthUserService;
use YiiInnerSystem\backendControllers\services\OauthUserService;
use YiiInnerSystem\models\OauthUser;

/**
 * 控制器: 授权账号管理
 *
 * Class OauthUserController
 * @package YiiInnerSystem\backendControllers
 *
 * @property-read IOauthUserService $service
 */
class OauthUserController extends RestController
{
    public $serviceInterface = IOauthUserService::class;
    public $serviceClass     = OauthUserService::class;

    /**
     * 授权账号列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['uuid', 'string', 'label' => '用户标识'],
            ['remark', 'string', 'label' => '备注'],
            ['is_enable', 'boolean', 'label' => '启用状态'],
            // 有效期规则
            ['isExpire', 'boolean', 'label' => '是否有效'],
        ], null, true);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '授权账号列表');
    }

    /**
     * 添加授权账号
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['remark', 'required'],
            ['is_enable', 'boolean', 'label' => '是否有效'],
            ['uuid', 'string', 'label' => '用户标识'],
            ['remark', 'string', 'label' => '描述'],
            [
                'expire_ip',
                'each',
                'label' => '有效IP地址',
                'rule'  => [
                    'ip'
                ]
            ],
            ['expire_begin_date', 'datetime', 'label' => '生效日期', 'format' => 'php:Y-m-d'],
            ['expire_end_date', 'datetime', 'label' => '失效日期', 'format' => 'php:Y-m-d'],
        ], null, false, ['expire_ip'], '|');
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加授权账号成功');
    }

    /**
     * 编辑授权账号
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '授权账号ID', 'targetClass' => OauthUser::class, 'targetAttribute' => 'id'],
            ['is_enable', 'boolean', 'label' => '是否有效'],
            ['remark', 'string', 'label' => '描述'],
            [
                'expire_ip',
                'each',
                'label' => '有效IP地址',
                'rule'  => [
                    'ip'
                ]
            ],
            ['expire_begin_date', 'datetime', 'label' => '生效日期', 'format' => 'php:Y-m-d'],
            ['expire_end_date', 'datetime', 'label' => '失效日期', 'format' => 'php:Y-m-d'],
        ], null, false, ['expire_ip'], '|');
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑授权账号成功');
    }

    /**
     * 删除授权账号
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '授权账号ID', 'targetClass' => OauthUser::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除授权账号成功');
    }

    /**
     * 查看授权账号详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '授权账号ID', 'targetClass' => OauthUser::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '查看授权账号');
    }

    /**
     * 为授信账号重新分配秘钥
     *
     * @return array
     * @throws Exception
     */
    public function actionChangeKey()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id', 'securityPassword'], 'required'],
            ['id', 'exist', 'label' => '授权账号ID', 'targetClass' => OauthUser::class, 'targetAttribute' => 'id'],
            ['securityPassword', SecurityOperateValidator::class, 'label' => '操作密码'],
        ]);
        // 业务处理
        $res = $this->service->changeKey($params);
        // 渲染结果
        return $this->success($res, '重新分配秘钥成功');
    }
}