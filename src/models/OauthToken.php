<?php

namespace YiiInnerSystem\models;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "{{%oauth_token}}".
 *
 * @property int $id 自增ID
 * @property string $uuid 用户/系统标识
 * @property string $access_token 访问token
 * @property string $expire_at 有效时间
 * @property string $created_at 创建时间
 */
class OauthToken extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expire_at'], 'required'],
            [['expire_at', 'created_at'], 'safe'],
            [['uuid'], 'string', 'max' => 50],
            [['access_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => '自增ID',
            'uuid'         => '用户/系统标识',
            'access_token' => '访问token',
            'expire_at'    => '有效时间',
            'created_at'   => '创建时间',
        ];
    }
}
