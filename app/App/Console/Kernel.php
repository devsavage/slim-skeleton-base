<?php

namespace App\Console;

class Kernel
{
    protected $defaultCommands = [
        \App\Console\Commands\Generator\ConsoleGeneratorCommand::class,
        \App\Console\Commands\Generator\ControllerGeneratorCommand::class,
    ];

    public function getCommands() 
    {
        return $this->defaultCommands;
    }
}