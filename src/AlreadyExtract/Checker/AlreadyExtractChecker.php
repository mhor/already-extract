<?php

namespace AlreadyExtract\Checker;

use Symfony\Component\Filesystem\Filesystem;

class AlreadyExtractChecker
{
    /**
     * @var int
     */
    protected $averageTolerance = 20;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var string
     */
    protected $archiveFile;

    /**
     * @var array
     */
    protected $extension;

    public function __construct()
    {
        $this->fs = new Filesystem();
        $this->extension = array('.rar', '.zip');
    }

    /**
     * @return int
     */
    public function isAlreadyExtracted()
    {
        $guessedDirectory = str_replace($this->extension, '', $this->archiveFile);

        if (!$this->fs->exists($guessedDirectory)) {
            return 2;
        }

        /*
         * TODO Not Implemented -----------------------------------------------
         */
        $archiveSize = $this->archiveFile;

        $maxSize = "0";
        $minSize = "1";

        $extractDirSize = $this->getDirectorySize($guessedDirectory);
        if ($extractDirSize < $minSize || $extractDirSize > $maxSize) {
            return 1;
        }

        //---------------------------------------------------------------------

        return 0;
    }

    /**
     * @param string path
     * @return integer total size in bit of a directory
     */
    protected function getDirectorySize($path)
    {
        $totalBytes = 0;
        $path = realpath($path);
        if ($path!==false) {
            foreach (
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
                ) as $object
            ) {
                $totalBytes += $object->getSize();
            }
        }
        return $totalBytes;
    }

    /**
     * @return string
     */
    public function getArchiveFile()
    {
        return $this->archiveFile;
    }

    /**
     * @param string $archiveFile
     * @return AlreadyExtractChecker
     */
    public function setArchiveFile($archiveFile)
    {
        $this->archiveFile = $archiveFile;
        return $this;
    }

    /**
     * @return int
     */
    protected function getAverageTolerance()
    {
        return $this->averageTolerance;
    }

    /**
     * @param int $averageTolerance
     * @return AlreadyExtractChecker
     */
    public function setAverageTolerance($averageTolerance)
    {
        $this->averageTolerance = $averageTolerance;
        return $this;
    }
}
 