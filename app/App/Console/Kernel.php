<?php

namespace App\Console;

use SlimSkeleton\Console\KernelInterface;

class Kernel implements KernelInterface
{
    protected $moduleCommands = [
        
    ];

    protected $defaultCommands = [
        \SlimSkeleton\Console\Commands\Generator\ConsoleGeneratorCommand::class,
        \SlimSkeleton\Console\Commands\Generator\ControllerGeneratorCommand::class,
    ];

    public function getCommands(): array
    {
        return array_merge($this->defaultCommands, $this->moduleCommands);
    }
}