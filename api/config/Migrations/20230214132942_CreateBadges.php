<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBadges extends AbstractMigration
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
        $table = $this->table('badges');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 24,
            'null' => false,
        ]);
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('icon', 'string', [
            'default' => null,
            'limit' => 36,
            'null' => false,
        ]);
        $table->addColumn('field', 'string', [
            'default' => null,
            'limit' => 48,
            'null' => false,
        ]);
        $table->addColumn('base', 'string', [
            'default' => null,
            'limit' => 6,
            'null' => false,
        ]);
        $table->addColumn('goal', 'integer', [
            'default' => null,
            'limit' => 6,
            'null' => false,
        ]);
        $table->create();
    }
}
