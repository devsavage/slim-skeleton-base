<?php

namespace App\Console\Commands\Generator;

use App\Console\Command;
use App\Console\Traits\Generatable;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerGeneratorCommand extends Command
{
    use Generatable;

    protected $command = "make:controller";

    protected $description = "Generate a new controller class";

    public function handle(InputInterface $input, OutputInterface $output)
    {
        $controllerBase = __DIR__ . "/../../../Http/Controllers";
        $path = $controllerBase . "/";
        $namespace = "App\\Http\\Controllers";

        $fileParts = explode("\\", trim($this->argument("name")));

        $fileName = array_pop($fileParts);

        $cleanPath = implode("/", $fileParts);

        if (count($fileParts) >= 1) {
            $path = $path . $cleanPath;

            $namespace = $namespace . "\\" . str_replace("/", "\\", $cleanPath);

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }

        $target = $path . "/" . $fileName . ".php";

        if (file_exists($target)) {
            return $this->error("Controller already exists!");
        }

        if($this->option("view")) {
            $stub = $this->generateStub("controller-view", [
                "DummyClass" => $fileName,
                "DummyNamespace" => $namespace,
                "DummyView" => $this->cleanPathName($this->option("view")),
            ]);
        } else {
            $stub = $this->generateStub("controller", [
                "DummyClass" => $fileName,
                "DummyNamespace" => $namespace,
            ]);
        }

        file_put_contents($target, $stub);

        if($this->option("view")) {
            $this->generateView($this->option("view"));
        }

        return $this->info("Controller generated!"); 
    }

    private function generateView($name) 
    {
        $viewBase = __DIR__ . "/../../../../../resources/views";
        $path = $viewBase . "/";

        $fileParts = explode("/", trim($name));

        $fileName = array_pop($fileParts);

        $cleanPath = implode("/", $fileParts);

        if (count($fileParts) >= 1) {
            $path = $path . $cleanPath;

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }

        $fileName = strtolower($fileName);

        $target = $path . "/" . $fileName . ".twig";

        if (file_exists($target)) {
            return $this->error("View already exists in base resources/views directory!");
        }

        file_put_contents($target, $this->generateStub("view", []));

        return $this->info("View has been generated!");
    }
    
    private function cleanPathName($name) 
    {
        return ltrim($name, "/");
    }

    protected function arguments(): array
    {
        return [
            ["name", InputArgument::REQUIRED, "The name of the controller to generate"]
        ];
    }

    protected function options() : array
    {
        return [
            ["view", "t", InputOption::VALUE_REQUIRED, "Generate a TWIG view with the provided name", null],
        ];
    }
}