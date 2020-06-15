<?php

namespace DmitriiKoziuk\FakeRestApiModules\Blog\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_posts}}`.
 */
class m200602_111036_create_blog_posts_table extends Migration
{
    private string $blogPostsTableName = '{{%blog_posts}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->blogPostsTableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'body' => $this->text()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->blogPostsTableName);
    }
}
