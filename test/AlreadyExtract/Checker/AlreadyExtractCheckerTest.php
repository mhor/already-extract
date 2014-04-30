<?php

namespace AlreadyExtract\Checker;

class AlreadyExtractCheckerTest extends \PHPUnit_Framework_TestCase
{

    protected $archivesDir;

    protected $successArchive = 'a.zip';

    protected $warningArchive = 'b.zip';

    protected $errorArchive = 'c.zip';

    public function __construct()
    {
        $this->archivesDir = __DIR__ . '/../../testArchive/';
    }

    public function testSuccessIfArchiveIsAlreadyExtract()
    {
        $checker = new AlreadyExtractChecker();
        $checker->setArchiveFile($this->archivesDir . $this->successArchive);
        $this->assertEquals(0, $checker->isAlreadyExtracted($this->archivesDir));
    }

    public function testWarningIfExtractedDirectoryIsLowerThanArchive()
    {
        $checker = new AlreadyExtractChecker();
        $checker->setArchiveFile($this->archivesDir . $this->warningArchive);
        $this->assertEquals(1, $checker->isAlreadyExtracted($this->archivesDir));
    }

    public function testErrorIfArchiveINotExtract()
    {
        $checker = new AlreadyExtractChecker();
        $checker->setArchiveFile($this->archivesDir . $this->errorArchive);
        $this->assertEquals(2, $checker->isAlreadyExtracted($this->archivesDir));
    }
}
 