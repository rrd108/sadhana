<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class AddHomeToSadhanas extends AbstractMigration
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
        $table->addColumn('homeMangala', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('homeGuruPuja', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('homeGauraArati', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->update();
    }
}
