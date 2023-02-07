<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('users', ['id' => false]);
        $table->addColumn('id', 'uuid', [
            'default' => null,
            'limit' => null,
            'null' => false,
        ])
            ->addPrimaryKey(['id'])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 60,
                'null' => false,
            ])->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])->addColumn('token', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('token_expiration', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])->addColumn('last_login', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])->addColumn('created', 'datetime', [
                'default' => null,
                'null' => false,
            ])->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => false,
            ]);
        $table->addIndex(['email',], ['unique' => true]);
        $table->create();
    }
}
