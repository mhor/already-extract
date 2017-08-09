<?php

namespace AlreadyExtract\Utils\ExtractorCommandDecoder;

use Symfony\Component\Process\Process;

/**
 * Created by PhpStorm.
 * User: mhor
 * Date: 02/08/17
 * Time: 23:32
 */
class UnrarCommandDecoder implements ExtractorCommandDecoderInterface
{
    /**
     *  {@inheritdoc}
     */
    public function getFiles($path)
    {
        $process = new Process(sprintf('unrar l "%s"', $path));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $files = [];
        $inFiles = false;
        $colSize = null;
        foreach (explode(PHP_EOL, trim($process->getOutput())) as $line) {

            if (trim($line) !== '' && strlen(str_replace([' ', '-'], '', $line)) === 0) {
                if ($inFiles === false) {
                    $colSize = $this->getColSize($line);
                }
                $inFiles = !$inFiles;

                continue;
            }

            if ($inFiles === false) {
                continue;
            }

            $files[] = (new File())
                ->setUnpackedSize((int) trim((substr($line, $colSize['length'], $colSize['date']))))
                ->setPath(trim((substr($line, $colSize['name']))))
            ;

        }

        return $files;
    }

    /**
     * @param $line
     * @return array
     */
    private function getColSize($line)
    {

        $cols = explode(' ',  preg_replace('~\s+~', ' ', $line));
        $attributes = strlen($cols[0]) + 1;
        $length = strlen($cols[1]) + $attributes + 2;
        $date = strlen($cols[2]) + $length + 1;
        $time = strlen($cols[3]) + $date + 2;
        return [
            'attributes' => 0,
            'length' => $attributes,
            'date' => $length,
            'time' => $date,
            'name' => $time,
        ];
    }
}