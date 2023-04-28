<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CounsellorsCounsuleesFixture
 */
class CounsellorsCounsuleesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'counsellor_id' => '0340d6d0-bcac-499e-945f-6859ed7487aa',
                'counsulee_id' => 'd0cdc739-6491-4062-8345-6fbbd7e22275',
            ],
        ];
        parent::init();
    }
}
