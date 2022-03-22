<?php

namespace App\Console;

use Slim\App;
use Symfony\Component\Console\Application;

class Console extends Application
{
    protected $app;

    public function __construct(App $app, $version)
    {
        parent::__construct("Slim Skeleton Base", $version);

        $this->app = $app;
    }

    public function boot(Kernel $kernel) 
    {
        foreach($kernel->getCommands() as $command) {
            $this->add(new $command($this->getApp()->getContainer()));
        }
    }

    private function getApp()
    {
        return $this->app;
    }
}