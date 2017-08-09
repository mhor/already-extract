<?php

namespace AlreadyExtract\Utils\ExtractorCommandDecoder;

/**
 * Created by PhpStorm.
 * User: mhor
 * Date: 02/08/17
 * Time: 23:33
 */
class File
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $unpackedSize;

    /**
     * @return bool
     */
    public function isDir()
    {
        return $this->unpackedSize === 0;
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return $this->unpackedSize > 0;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnpackedSize()
    {
        return $this->unpackedSize;
    }

    /**
     * @param mixed $unpackedSize
     * @return File
     */
    public function setUnpackedSize($unpackedSize)
    {
        $this->unpackedSize = $unpackedSize;
        return $this;
    }
}