<?php

namespace AlreadyExtract\Command;

use AlreadyExtract\Checker\AlreadyExtractChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class AlreadyExtractCommand extends Command
{
    /**
     * @var int
     */
    protected $countError = 0;

    /**
     * @var int
     */
    protected $countWarning = 0;

    /**
     * @var array
     */
    protected $extensions = array('zip', 'rar');

    protected function configure()
    {
        $this
            ->setName('already-exist')
            ->setDescription('check if archive files are already extracted')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'path of directory to check'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $path = getcwd();
        if ($input->getArgument('path') !== null) {
            $path = $input->getArgument('path');
        }

        $fs = new Filesystem();
        if (!$fs->exists($path)) {
            throw new \Exception('Directory doesn\'t exist');
        }

        $checker = new AlreadyExtractChecker();

        $finder = new Finder();
        $finder->files()->in($path)->name('*.zip')->name('*.rar');
        foreach ($finder as $file) {
            $checker->setArchiveFile($file->getRealPath());
            $this->writeOutput(
                $file->getRealPath(),
                $output,
                $checker->isAlreadyExtracted()
            );
        }
        $output->writeln("Warnings: " . $this->countWarning . " Errors: " . $this->countError);
    }

    /**
     * @param int $level Severity of alert: 1=warning & 2=error
     * @param string $filePath
     * @param OutputInterface $output
     */
    protected function writeOutput($filePath, OutputInterface $output, $level=0)
    {
        switch ($level) {
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
 