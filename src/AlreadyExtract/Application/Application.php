<?php

namespace AlreadyExtract\Application;

use AlreadyExtract\Command\AlreadyExtractCommand;
use Symfony\Component\Console\Application as ApplicationBase;
use Symfony\Component\Console\Input\InputInterface;

class Application extends ApplicationBase
{
    protected function getCommandName(InputInterface $input)
    {
        return 'already-exist';
    }

    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new AlreadyExtractCommand();
        return $defaultCommands;
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();
        return $inputDefinition;
    }
}
 