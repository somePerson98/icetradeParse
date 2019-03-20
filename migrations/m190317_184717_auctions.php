<?php

use yii\db\Migration;

/**
 * Class m190317_184717_auctions
 */
class m190317_184717_auctions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('auctions', [
            'id'=>$this->primaryKey()->notNull(),
            'number'=>$this->string()->notNull()
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('auctions');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190317_184717_auctions cannot be reverted.\n";

        return false;
    }
    */
}
