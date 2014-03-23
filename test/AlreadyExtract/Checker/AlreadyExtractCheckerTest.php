<?php

namespace AlreadyExtract\Checker;


class AlreadyExtractCheckerTest extends \PHPUnit_Framework_TestCase
{

    protected $archivesDir;

    protected $successArchive = 'a.zip';

    protected $warningArchive = 'b.zip';

    protected $errorArchive = 'c.zip';

    protected $tooBigArchive = 'd.zip';

    public function __construct()
    {
        $this->archivesDir = __DIR__ . '/../../testArchive/';
    }

    public function testSuccessIfArchiveIsAlreadyExtract()
    {
        $checker = new AlreadyExtractChecker();
        $checker->setArchiveFile($this->archivesDir . $this->successArchive);
        $this->assertEquals(0, $checker->isAlreadyExtracted());
    }

    public function testWarningIfExtractedDirectoryIsLowerThanArchive()
    {
        $checker = new AlreadyExtractChecker();
        $checker->setArchiveFile($this->archivesDir . $this->warningArchive);
        $this->assertEquals(1, $checker->isAlreadyExtracted());
    }

    public function testErrorIfArchiveINotExtract()
    {
        $checker = new AlreadyExtractChecker();
        $checker->setArchiveFile($this->archivesDir . $this->errorArchive);
        $this->assertEquals(2, $checker->isAlreadyExtracted());
    }

    public function testSuccessIfToleranceIsLow()
    {
        $checker = new AlreadyExtractChecker();
        $checker->setMinTolerance(0.99);
        $checker->setArchiveFile($this->archivesDir . $this->warningArchive);
        $this->assertEquals(0, $checker->isAlreadyExtracted());
    }

    public function testSuccessIfMaxToleranceIsHigh()
    {
        $checker = new AlreadyExtractChecker();
        $checker->setMaxTolerance(2);
        $checker->setArchiveFile($this->archivesDir . $this->tooBigArchive);
        $this->assertEquals(0, $checker->isAlreadyExtracted());
    }
}
 