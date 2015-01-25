<?php

namespace AlreadyExtract\Checker;

use Symfony\Component\Filesystem\Filesystem;

class RarAlreadyExtractChecker implements AlreadyExtractCheckerInterface
{
    /**
     * @param $archiveFile
     */
    public function __construct($archiveFile)
    {
        $this->archiveFile = $archiveFile;
        $this->fs = new Filesystem();
    }

    /**
     * Return a code to know status
     *  0 = No problem
     *  1 = One file on extracted archive maybe corrupted
     *  2 = One file is not extracted
     *  3 = Archive can't be open
     * @param string $path
     * @return int
     */
    public function isAlreadyExtracted($path)
    {
        return 2;
    }
}