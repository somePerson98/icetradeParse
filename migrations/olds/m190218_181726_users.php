<?php

use yii\db\Migration;

/**
 * Class m190218_181726_users
 */
class m190218_181726_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('users', [
            'id'=>$this->primaryKey()->notNull(),
            'login'=>$this->string()->notNull(),
            'password'=>$this->string()->notNull(),
            'role'=>$this->integer()
        ]);

        $this->createTable('orders', [
            'id'=>$this->primaryKey()->notNull(),
            'name'=>$this->string(255)->notNull(),
            'text'=>$this->text(),
            'created_at'=>$this->integer()->notNull(),
            'notary_comment'=>$this->string(255),
            'notary_id'=>$this->integer(),
            'client_id'=>$this->integer(),
            'status'=>$this->integer()->notNull(),
        ]);

        $this->createTable('files', [
            'id'=>$this->primaryKey()->notNull(),
            'path'=>$this->string(255)->notNull(),
            'f_name'=>$this->string(255)->notNull(),
            'order_id'=>$this->integer()->notNull()
        ]);

        $this->addForeignKey(
        'fk_clients_orders',
        'orders',
        'client_id',
        'users',
        'id',
        'RESTRICT'
    );

        $this->addForeignKey(
            'fk_notary_orders',
            'orders',
            'notary_id',
            'users',
            'id',
            'RESTRICT'
        );



        $this->addForeignKey(
            'fk_files_orders',
            'files',
            'order_id',
            'orders',
            'id',
            'RESTRICT'
        );



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_files_orders',
            'files'
        );

        $this->dropForeignKey(
            'fk_notary_orders',
            'orders'
        );

        $this->dropForeignKey(
            'fk_clients_orders',
            'orders'
        );

        $this->dropTable(
            'files'
        );

        $this->dropTable(
            'orders'
        );

        $this->dropTable('users');
    }
}
