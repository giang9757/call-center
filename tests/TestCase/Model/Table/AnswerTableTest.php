<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnswerTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnswerTable Test Case
 */
class AnswerTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AnswerTable
     */
    public $Answer;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.answer'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Answer') ? [] : ['className' => AnswerTable::class];
        $this->Answer = TableRegistry::get('Answer', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Answer);

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
