<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSadhanas extends AbstractMigration
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
        $table = $this->table('sadhanas');
        $table->addColumn('user_id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('date', 'date', [
            'null' => false,
        ]);
        $table->addColumn('japaEarly', 'smallinteger', [
            'default' => 0,
            'signed' => false,
            'null' => false,
        ]);
        $table->addColumn('japaMorning', 'smallinteger', [
            'default' => 0,
            'signed' => false,
            'null' => false,
        ]);
        $table->addColumn('japaAfternoon', 'smallinteger', [
            'default' => 0,
            'signed' => false,
            'null' => false,
        ]);
        $table->addColumn('japaNight', 'smallinteger', [
            'default' => 0,
            'signed' => false,
            'null' => false,
        ]);
        $table->addColumn('mangala', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('japa', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('kirtana', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('class', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('gauraarati', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('reading', 'smallinteger', [
            'default' => 0,
            'signed' => false,
            'null' => false,
        ]);
        $table->addColumn('study', 'smallinteger', [
            'default' => 0,
            'signed' => false,
            'null' => false,
        ]);
        $table->addColumn('murtiseva', 'smallinteger', [
            'default' => 0,
            'signed' => false,
            'null' => false,
        ]);
        $table->addForeignKey('user_id', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT']);
        $table->addIndex(['user_id', 'date'], ['unique' => true]);
        $table->create();
    }
}
