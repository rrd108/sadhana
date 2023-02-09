<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SadhanaFixture
 */
class SadhanaFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'sadhana';
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
                'user_id' => 'f83eae98-1f4e-4177-9bc5-42351545f843',
                'date' => '2023-02-09',
                'japaEarly' => 1,
                'japaMorning' => 1,
                'japaAfternoon' => 1,
                'japaNight' => 1,
                'mangala' => 1,
                'japa' => 1,
                'kirtana' => 1,
                'class' => 1,
                'gauraarati' => 1,
                'reading' => 1,
                'study' => 1,
                'murtiseva' => 1,
            ],
        ];
        parent::init();
    }
}
