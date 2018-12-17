<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MemocallerTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MemocallerTable Test Case
 */
class MemocallerTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MemocallerTable
     */
    public $Memocaller;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.memocaller'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Memocaller') ? [] : ['className' => MemocallerTable::class];
        $this->Memocaller = TableRegistry::get('Memocaller', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Memocaller);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
