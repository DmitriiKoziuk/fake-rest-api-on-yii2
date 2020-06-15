<?php declare(strict_types=1);

namespace DmitriiKoziuk\FakeRestApiModules\Auth\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_user_api_keys}}`.
 */
class m200603_083125_create_auth_user_api_keys_table extends Migration
{
    private string $authUserApiKeysTable = '{{%auth_user_api_keys}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->authUserApiKeysTable, [
            'user_id' => $this->primaryKey(),
            'api_key' => $this->string(255)->notNull(),
        ]);
        $this->addForeignKey(
            'auth_user_api_keys_fk_user_id',
            $this->authUserApiKeysTable,
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('auth_user_api_keys_fk_user_id', $this->authUserApiKeysTable);
        $this->dropTable($this->authUserApiKeysTable);
    }
}
