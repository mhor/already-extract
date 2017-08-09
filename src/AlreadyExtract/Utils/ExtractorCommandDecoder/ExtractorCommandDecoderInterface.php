<?php

namespace AlreadyExtract\Utils\ExtractorCommandDecoder;

/**
 * Created by PhpStorm.
 * User: mhor
 * Date: 02/08/17
 * Time: 23:32
 */
interface ExtractorCommandDecoderInterface
{
    /**
     * @param $path
     * @return File[]
     */
    public function getFiles($path);
}