<?php

namespace Arniro\Admin\Console;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Filesystem\Filesystem;

class Command extends BaseCommand
{
    /**
     * @var Filesystem
     */
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    protected function createDirectory($dir)
    {
        return $this->files->makeDirectory($dir, 0755, true, true);
    }
}
