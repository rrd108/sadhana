<?php

declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BadgesTable;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BadgesTable Test Case
 */
class BadgesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BadgesTable
     */
    protected $BadgesTable;


    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Badges') ? [] : ['className' => BadgesTable::class];
        $this->BadgesTable = $this->getTableLocator()->get('Badges', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BadgesTable);
        parent::tearDown();
    }

    public function testGetBadgeWithTopBadges()
    {
        $user = UserFactory::make()->with('Badges', [
            ['name' => 'badge-1', 'level' => 1],
            ['name' => 'badge-1', 'level' => 2],
            ['name' => 'badge-2', 'level' => 1],
            ['name' => 'badge-2', 'level' => 4],
        ])->persist();

        $badges = $this->BadgesTable->getTopBadges($user->id);
        $this->assertEquals(2, $badges->count());


        $badges = collection($badges)->map(function ($badge) {
            return ['name' => $badge->name, 'level' => $badge->level];
        })->toArray();

        $this->assertContainsEquals(['name' => 'badge-1', 'level' => 2], $badges);
        $this->assertContainsEquals(['name' => 'badge-2', 'level' => 4], $badges);
    }
}
