<?php

use yii\db\Migration;

/**
 * Class m190430_155203_default
 */
class m190430_155203_default extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('auctions', 'key_word', 'VARCHAR(64) AFTER number');

        $this->batchInsert(
            'auctions',
            ['key_word'],
            [
                ['дорож'],
                ['переезд'],
                ['стрел'],
                ['перевод'],
                ['шпал']
            ]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190430_155203_default cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190430_155203_default cannot be reverted.\n";

        return false;
    }
    */
}
