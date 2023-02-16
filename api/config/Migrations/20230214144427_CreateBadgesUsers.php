<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBadgesUsers extends AbstractMigration
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
        $table = $this->table('badges_users');
        $table->addColumn('badge_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ])->addForeignKey('badge_id', 'badges', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT']);
        $table->addColumn('user_id', 'uuid', [
            'default' => null,
            'null' => false,
        ])->addForeignKey('user_id', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT']);
        $table->addColumn('created', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('accepted', 'boolean', [
            'default' => false,
            'null' => false,
        ]);
        $table->create();
    }
}
