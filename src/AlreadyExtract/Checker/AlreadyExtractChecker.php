<?php

namespace AlreadyExtract\Checker;

use Alchemy\Zippy\Zippy;
use Symfony\Component\Filesystem\Filesystem;

class AlreadyExtractChecker
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var string
     */
    protected $archiveFile;

    /**
     * @var string
     */
    protected $extension;

    public function __construct()
    {
        $this->fs = new Filesystem();
        $this->extension = array('.rar', '.zip');
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
        $archive = Zippy::load();
        try {
            $readArchive = $archive->open($this->archiveFile);
            $archiveContent = $readArchive->getMembers();
        } catch (\Exception $e) {
            return 3;
        }

        foreach ($archiveContent as $file) {
            if ($file->isDir()) {
                continue;
            }

            if (!$this->fs->exists($path . $file->getLocation())) {
                return 2;
            }

            if (filesize($path . $file->getLocation()) != $file->getSize()) {
                return 1;
            }
        }
        return 0;
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
}
 