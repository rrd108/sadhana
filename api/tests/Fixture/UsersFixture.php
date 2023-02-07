<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'id' => 'df092b66-d100-494d-aba9-6f48927b9e69',
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'token' => 'Lorem ipsum dolor sit amet',
                'token_expiration' => '2023-02-07 16:02:16',
                'last_login' => '2023-02-07 16:02:16',
                'created' => '2023-02-07 16:02:16',
                'modified' => '2023-02-07 16:02:16',
            ],
        ];
        parent::init();
    }
}
