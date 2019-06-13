<?php

use yii\db\Migration;

/**
 * Class m190613_074212_extra_queries
 */
class m190613_074212_extra_queries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(
            'auctions',
            ['key_word'],
            [
                ['zh.d.'],
                ['put']
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190613_074212_extra_queries cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190613_074212_extra_queries cannot be reverted.\n";

        return false;
    }
    */
}
