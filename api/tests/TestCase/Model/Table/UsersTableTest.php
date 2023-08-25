<?php

declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    protected $UsersTable;


    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->UsersTable = $this->getTableLocator()->get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->UsersTable);
        parent::tearDown();
    }

    public function testFindTopBadges()
    {
        $user = UserFactory::make()->withBadges([
            ['name' => 'badge-1', 'level' => 1],
            ['name' => 'badge-1', 'level' => 2],
            ['name' => 'badge-2', 'level' => 1],
            ['name' => 'badge-2', 'level' => 4],
        ])->persist();

        $user = $this->UsersTable->find('topBadges', ['userId' => $user->id]);
        $badges = collection($user->first()->badges)->map(function ($badge) {
            return ['name' => $badge->name, 'maxLevel' => $badge->maxLevel];
        })->toArray();
        $this->assertEquals([
            ['name' => 'badge-2', 'maxLevel' => 4],
            ['name' => 'badge-1', 'maxLevel' => 2],
        ], $badges);
    }
}
