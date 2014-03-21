<?php

namespace AlreadyExtract\Checker;

use Symfony\Component\Filesystem\Filesystem;

class AlreadyExtractChecker
{
    /**
     * @var float
     */
    protected $maxTolerance = 1.45;

    /**
     * @var float
     */
    protected $minTolerance = 0.1;

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

        $archiveSize = filesize($this->archiveFile);

        $maxSize = $archiveSize * $this->maxTolerance;
        $minSize = $archiveSize - ($archiveSize * $this->minTolerance);

        $extractDirSize = $this->getDirectorySize($guessedDirectory);
        if ($extractDirSize < $minSize || $extractDirSize > $maxSize) {
            return 1;
        }

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
     * @return float
     */
    public function getMaxTolerance()
    {
        return $this->maxTolerance;
    }

    /**
     * @param float $maxTolerance
     * @return AlreadyExtractChecker
     */
    public function setMaxTolerance($maxTolerance)
    {
        $this->maxTolerance = $maxTolerance;
        return $this;
    }

    /**
     * @return float
     */
    public function getMinTolerance()
    {
        return $this->minTolerance;
    }

    /**
     * @param float $minTolerance
     * @return AlreadyExtractChecker
     */
    public function setMinTolerance($minTolerance)
    {
        $this->minTolerance = $minTolerance;
        return $this;
    }
}
 