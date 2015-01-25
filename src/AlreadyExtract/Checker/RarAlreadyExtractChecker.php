<?php

namespace AlreadyExtract\Checker;

use Symfony\Component\Filesystem\Filesystem;

class RarAlreadyExtractChecker implements AlreadyExtractCheckerInterface
{
    /**
     * @var float
     */
    private $maxTolerance = 1.45;

    /**
     * @var float
     */
    private $minTolerance = 0.1;

    private $extension = array('.rar');

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
     * @param string $path
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
}