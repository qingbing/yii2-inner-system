<?php

namespace YiiInnerSystem\models;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "{{%oauth_user}}".
 *
 * @property int $id 自增ID
 * @property string $uuid 用户/系统标识
 * @property string $flag 允许访问的系统标识,多个用|分割
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
            [['public_key', 'private_key'], 'string'],
            [['expire_begin_date', 'expire_end_date', 'created_at', 'updated_at'], 'safe'],
            [['uuid', 'private_password'], 'string', 'max' => 50],
            [['flag', 'expire_ip'], 'string', 'max' => 255],
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
            'flag'              => '允许访问的系统标识,多个用|分割',
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
}
