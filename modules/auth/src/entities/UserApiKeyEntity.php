<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\entities;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%auth_user_api_keys}}".
 *
 * @property int $user_id
 * @property string $api_key
 *
 * @property User $user
 */
class UserApiKeyEntity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auth_user_api_keys}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['api_key'], 'required'],
            [['api_key'], 'string', 'max' => 255],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'api_key' => 'Api Key',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
