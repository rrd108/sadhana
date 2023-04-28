<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CounsellorsCounsuleesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CounsellorsCounsuleesTable Test Case
 */
class CounsellorsCounsuleesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CounsellorsCounsuleesTable
     */
    protected $CounsellorsCounsulees;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.CounsellorsCounsulees',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CounsellorsCounsulees') ? [] : ['className' => CounsellorsCounsuleesTable::class];
        $this->CounsellorsCounsulees = $this->getTableLocator()->get('CounsellorsCounsulees', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->CounsellorsCounsulees);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CounsellorsCounsuleesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
