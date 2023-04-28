<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCounsellorsCounsulees extends AbstractMigration
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
        $table = $this->table('counsellors_counsulees', ['collation' => 'utf8mb4_general_ci']);
        $table->addColumn('counsellor_id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('counsulee_id', 'uuid', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
