<?php

namespace AlreadyExtract\Command;

use AlreadyExtract\Checker\ZipAlreadyExtractChecker;
use AlreadyExtract\Factory\AlreadyExtractFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class AlreadyExtractCommand extends Command
{
    /**
     * @var int
     */
    protected $countError = 0;

    /**
     * @var int
     */
    protected $countSuccess = 0;

    /**
     * @var int
     */
    protected $countWarning = 0;

    protected function configure()
    {
        $this
            ->setName('already-extract')
            ->setDescription('check if archive files are already extracted')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'path of directory to check'
            )
            ->addArgument(
                'path-extracted',
                InputArgument::OPTIONAL,
                'path to check extracted file'
            )
            ->addOption(
                'drop',
                'd',
                InputOption::VALUE_NONE,
                'Drop extracted archives'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $drop = false;
        if ($input->getOption('drop') !== false) {
            $drop = true;
        }

        $path = getcwd();
        if ($input->getArgument('path') !== null) {
            $path = rtrim($input->getArgument('path'), '/');
        }

        $pathExtracted = $path;
        if ($input->getArgument('path-extracted') !== null) {
            $pathExtracted = rtrim($input->getArgument('path-extracted'), '/');
        }

        $fs = new Filesystem();
        if (!$fs->exists($path)) {
            throw new \Exception('Directory doesn\'t exist');
        }

        if (!$fs->exists($pathExtracted)) {
            throw new \Exception('Extracted directory does\'t exist');
        }

        $finder = new Finder();
        $finder->files()->in($path)->name('*.zip')->name('*.rar');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $checker = AlreadyExtractFactory::create(
                $file->getRealPath(),
                $file->getExtension()
            );

            $result = $checker->isAlreadyExtracted($pathExtracted . str_replace($path, '', $file->getPath()) . '/');
            if ($result ===  0 && $drop === true) {
                $fs->remove($file->getRealPath());
            }

            $this->writeOutput(
                $file->getRealPath(),
                $output,
                $result
            );
        }
        $output->writeln("Success: " . $this->countSuccess . " Warnings: " . $this->countWarning . " Errors: " . $this->countError);
    }

    /**
     * @param int $level Severity of alert: 1=warning & 2=error
     * @param string $filePath
     * @param OutputInterface $output
     */
    protected function writeOutput($filePath, OutputInterface $output, $level = 0)
    {
        switch ($level) {
            case 0:
                //$output->writeln("<info>Success: file " . $filePath . " is extracted</info>");
                $this->countSuccess++;
                break;
            case 1:
                $output->writeln("<comment>Warning: file " . $filePath . " looks weird</comment>");
                $this->countWarning++;
                break;
            case 2:
                $output->writeln("<error>Error: file " . $filePath . " might be not extracted</error>");
                $this->countError++;
                break;
        }
    }
}
 