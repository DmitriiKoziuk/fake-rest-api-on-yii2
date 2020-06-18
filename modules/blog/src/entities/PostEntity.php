<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Blog\entities;

use Yii;

/**
 * This is the model class for table "{{%blog_posts}}".
 *
 * @property int $id
 * @property string $title
 * @property string $body
 */
class PostEntity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%blog_posts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'required'],
            [['body'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'body' => 'Body',
        ];
    }
}
