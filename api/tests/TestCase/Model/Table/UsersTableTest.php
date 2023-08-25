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

    // public function testGetUserWithTopBadges()
    // {
    //     $user = UserFactory::make()->with('Badges', [
    //         ['name' => 'badge-1', 'level' => 1],
    //         ['name' => 'badge-1', 'level' => 2],
    //         ['name' => 'badge-2', 'level' => 1],
    //         ['name' => 'badge-2', 'level' => 4],
    //     ])->persist();

    //     $user = $this->UsersTable->get($user->id, ['contain' => ['Badges']]);
    //     $this->assertEquals(2, count($user->badges));

    //     $userBadges = collection($user->badges)->map(function ($badge) {
    //         return ['name' => $badge->name, 'level' => $badge->level];
    //     })->toArray();

    //     $this->assertContainsEquals(['name' => 'badge-1', 'level' => 2], $userBadges);
    //     $this->assertContainsEquals(['name' => 'badge-2', 'level' => 4], $userBadges);
    // }
}
