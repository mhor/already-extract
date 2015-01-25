<?php

namespace AlreadyExtract\Factory;

use AlreadyExtract\Checker\AlreadyExtractCheckerInterface;
use AlreadyExtract\Checker\RarAlreadyExtractChecker;
use AlreadyExtract\Checker\ZipAlreadyExtractChecker;

class AlreadyExtractFactory
{
    const ZIP = 'zip';
    const RAR = 'rar';

    /**
     * @param $path
     * @param $type
     * @return AlreadyExtractCheckerInterface
     * @throws \Exception
     */
    public static function create($path, $type)
    {
        switch ($type) {
            case self::ZIP:
                return new ZipAlreadyExtractChecker($path);
            case self::RAR:
                return new RarAlreadyExtractChecker($path);
            default:
                throw new \Exception('Unknown archive type');
        }
    }
} 