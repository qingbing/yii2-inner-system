<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiInnerSystem\backendControllers\services;


use Exception;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\Pager;
use YiiInnerSystem\backendControllers\interfaces\IOauthUserService;
use YiiInnerSystem\models\OauthUser;
use Zf\Helper\Crypt\Openssl;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Exceptions\UnsupportedException;
use Zf\Helper\Util;

/**
 * 服务: 授权账号管理
 *
 * Class OauthUserService
 * @package YiiInnerSystem\backendControllers\services
 */
class OauthUserService extends Service implements IOauthUserService
{
    /**
     * 授权账号列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = OauthUser::find()
            ->orderBy('id DESC');
        // 等于查询
        $this->attributeWhere($query, $params, [
            'uuid',
            'is_enable',
        ]);
        // like 查询
        $this->likeWhere($query, $params, ['remark']);
        // 是否有效查询
        if (isset($params['isExpire'])) {
            $this->expireWhere($query, $params['isExpire'], 'expire_begin_date', 'expire_end_date');
        }
        // 分页查询
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加授权账号
     *
     * @param array $params
     * @return bool
     * @throws \Zf\Helper\Exceptions\Exception
     * @throws \Zf\Helper\Exceptions\ProgramException
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new OauthUser();
        $model->setFilterAttributes($params);
        if (empty($model->uuid)) {
            $model->uuid = Util::randomString(16, 3);
        }
        $privatePassword = Util::randomString(32, 13);
        // 生成openssl公私钥
        $secrets = Openssl::generateSecrets($privatePassword, Openssl::PRIVATE_KEY_BIT_1024, OPENSSL_KEYTYPE_RSA, true);
        // 属性赋值
        $model->private_password = $secrets['pass'];
        $model->private_key      = $secrets['private_key'];
        $model->public_key       = $secrets['public_key'];
        return $model->saveOrException();
    }

    /**
     * 编辑授权账号
     *
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['id']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除授权账号
     *
     * @param array $params
     * @return bool
     * @throws \Throwable
     * @throws Exception
     */
    public function del(array $params): bool
    {
        throw new UnsupportedException("该功能未开通，建议使用禁用功能");
    }

    /**
     * 查看授权账号详情
     *
     * @param array $params
     * @return mixed|OauthUser
     * @throws Exception
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 为授信账号重新分配秘钥
     *
     * @param array $params
     * @return bool|mixed
     * @throws Exception
     */
    public function changeKey(array $params)
    {
        $model           = $this->getModel($params);
        $privatePassword = Util::randomString(32, 13);
        // 生成openssl公私钥
        $secrets = Openssl::generateSecrets($privatePassword, Openssl::PRIVATE_KEY_BIT_1024, OPENSSL_KEYTYPE_RSA, true);
        // 属性赋值
        $model->private_password = $secrets['pass'];
        $model->private_key      = $secrets['private_key'];
        $model->public_key       = $secrets['public_key'];
        return $model->saveOrException();
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return OauthUser
     * @throws Exception
     */
    protected function getModel(array $params): OauthUser
    {
        $model = OauthUser::findOne([
            'id' => $params['id'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("授权账号不存在");
        }
        return $model;
    }
}