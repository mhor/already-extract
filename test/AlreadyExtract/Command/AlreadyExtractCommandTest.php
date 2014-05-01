<?php

namespace AlreadyExtract\Command;

use AlreadyExtract\Application\Application;
use Symfony\Component\Console\Tester\CommandTester;

class AlreadyExtractCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $archivesDir;

    public function __construct()
    {
        $this->archivesDir = __DIR__ . '/../../testArchive/';
    }

    public function testIfCommandHaveExpectedBehavior()
    {
        $application = new Application();

        $command = $application->find('already-extract');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('path' => $this->archivesDir));
        
        $this->assertRegExp('#Error: file (.*)/c.zip might be not extracted#', $commandTester->getDisplay());
        $this->assertRegExp('#Warning: file (.*)/b.zip looks weird#', $commandTester->getDisplay());
        $this->assertRegExp('#Warnings: 1 Errors: 1#', $commandTester->getDisplay());
    }

    /**
     * @expectedException \Exception
     */
    public function testIfCommandHaveExpectedBehaviorIfDirectoryIsBad()
    {
        $application = new Application();

        $command = $application->find('already-exist');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('path' => 'null'));
    }
}
 