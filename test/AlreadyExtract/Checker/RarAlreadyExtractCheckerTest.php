<?php

namespace AlreadyExtract\Checker;

class RarAlreadyExtractCheckerTest extends \PHPUnit_Framework_TestCase
{

    protected $archivesDir;

    protected $successArchive = 'a.rar';

    protected $warningArchive = 'b.rar';

    protected $errorArchive = 'c.rar';

    public function __construct()
    {
        $this->archivesDir = __DIR__ . '/../../fixtures/rar/';
    }

    public function testSuccessIfArchiveIsAlreadyExtract()
    {
        $checker = new RarAlreadyExtractChecker($this->archivesDir . $this->successArchive);
        $this->assertEquals(0, $checker->isAlreadyExtracted($this->archivesDir));
    }

    public function testWarningIfExtractedDirectoryIsLowerThanArchive()
    {
        $checker = new RarAlreadyExtractChecker($this->archivesDir . $this->warningArchive);
        $this->assertEquals(1, $checker->isAlreadyExtracted($this->archivesDir));
    }

    public function testErrorIfArchiveINotExtract()
    {
        $checker = new RarAlreadyExtractChecker($this->archivesDir . $this->errorArchive);
        $this->assertEquals(2, $checker->isAlreadyExtracted($this->archivesDir));
    }
}
 