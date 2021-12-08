<?php

namespace YiiInnerSystem\models;

use yii\db\ActiveRecord;
use YiiHelper\abstracts\Model;
use YiiHelper\behaviors\DefaultBehavior;

/**
 * This is the model class for table "{{%oauth_user}}".
 *
 * @property int $id 自增ID
 * @property string $uuid 用户/系统标识
 * @property string $remark 备注
 * @property int $is_enable 启用状态
 * @property string|null $public_key 公钥
 * @property string|null $private_key 私钥
 * @property string $private_password openssl的私钥密码
 * @property string $expire_ip 有效IP地址
 * @property string $expire_begin_date 生效日期
 * @property string $expire_end_date 失效日期
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class OauthUser extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid'], 'required'],
            [['is_enable'], 'integer'],
            [['public_key', 'private_key'], 'string'],
            [['expire_begin_date', 'expire_end_date', 'created_at', 'updated_at'], 'safe'],
            [['uuid', 'private_password'], 'string', 'max' => 50],
            [['remark', 'expire_ip'], 'string', 'max' => 255],
            [['uuid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                => '自增ID',
            'uuid'              => '用户/系统标识',
            'remark'            => '备注',
            'is_enable'         => '启用状态',
            'public_key'        => '公钥',
            'private_key'       => '私钥',
            'private_password'  => 'openssl的私钥密码',
            'expire_ip'         => '有效IP地址',
            'expire_begin_date' => '生效日期',
            'expire_end_date'   => '失效日期',
            'created_at'        => '创建时间',
            'updated_at'        => '更新时间',
        ];
    }

    /**
     * 在验证前执行
     *
     * @return bool
     */
    public function beforeValidate()
    {
        if (is_array($this->expire_ip)) {
            $this->expire_ip = implode('|', $this->expire_ip);
        }
        return parent::beforeValidate();
    }

    /**
     * 绑定 behavior
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class'      => DefaultBehavior::class,
                'type'       => DefaultBehavior::TYPE_DATETIME,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['expire_begin_date', 'expire_end_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['expire_begin_date', 'expire_end_date'],
                ],
            ],
        ];
    }

    /**
     * 模型 toArray 时的属性
     *
     * @return array|false
     */
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['private_key']);
        return $fields;
    }
}
