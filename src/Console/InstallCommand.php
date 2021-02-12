<?php

namespace Arniro\Admin\Console;

use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install admin panel';

    /**
     * The result of installing npm dependencies.
     *
     * @var bool
     */
    protected $npmInstalled = false;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Admin resources...');
        $this->callSilent('admin:publish');

        $this->comment('Registering service provider...');
        $this->registerServiceProvider();

        $this->comment('Registering routes...');
        if (!file_exists(base_path('routes/admin.php'))) {
            copy(__DIR__ . '/stubs/routes.stub', base_path('routes/admin.php'));
        }

        $this->createDirectory(app_path('Http/Controllers/Admin'));

        $this->comment('Scaffolding frontend assets...');
        $this->scaffoldFrontend();

        $this->copyTranslations();

        $this->createUserResource();

        $this->createGitignore();

        $this->comment('Installing npm dependencies...');
        $this->installNpmDependencies();

        $this->info('Admin panel has been successfully installed!');

        if (!$this->npmInstalled) {
            $this->warn(PHP_EOL . "Don't forget to install npm dependencies!");
        }
    }

    protected function installNpmDependencies()
    {
        $exitCode = $this->executeCommand('cd admin && npm set progress=false && npm install &>/dev/null');

        if ($exitCode != '1') {
            $this->npmInstalled = true;
        }
    }

    protected function executeCommand($command, $path = null)
    {
        if (!$path) {
            $path = getcwd();
        }

        $process = Process::fromShellCommandline($command, $path)->setTimeout(null);

        $exitCode = $process->run(function ($type, $line) {
            $this->output->write($line);
        });

        return $exitCode;
    }

    protected function registerServiceProvider()
    {
        file_put_contents(app_path('Providers/AdminServiceProvider.php'), str_replace(
            '{{DummyNamespace}}',
            app()->getNamespace(),
            file_get_contents(__DIR__ . '/stubs/service-provider.stub')
        ));

        $config = config_path('app.php');

        file_put_contents($config, str_replace(
            app()->getNamespace() . 'Providers\RouteServiceProvider::class,',
            str_replace(
                '{{Namespace}}',
                app()->getNamespace(),
                "App\Providers\RouteServiceProvider::class,\n\t\tApp\Providers\AdminServiceProvider::class,"
            ),
            file_get_contents($config)
        ));
    }

    protected function scaffoldFrontend()
    {
        $this->createJsFiles();
        $this->createSassFiles();
        $this->createViewFiles();
    }

    protected function createJsFiles()
    {
        $this->createDirectory(base_path('admin/resources/js/views'));

        copy(__DIR__ . '/stubs/Frontend/js/app.stub', base_path('admin/resources/js/app.js'));
        copy(__DIR__ . '/stubs/Frontend/js/routes.stub', base_path('admin/resources/js/routes.js'));
        copy(__DIR__ . '/stubs/Frontend/js/dashboard.stub', base_path('admin/resources/js/views/Dashboard.vue'));
    }

    protected function createSassFiles()
    {
        $this->createDirectory(base_path('admin/resources/sass'));

        file_put_contents(base_path('admin/resources/sass/app.scss'), '//');
    }

    protected function createViewFiles()
    {
        $this->createDirectory(base_path('admin/resources/views'));

        copy(__DIR__ . '/stubs/Frontend/views/logo.stub', base_path('admin/resources/views/logo.blade.php'));
        copy(__DIR__ . '/stubs/Frontend/views/nav.stub', base_path('admin/resources/views/nav.blade.php'));
        file_put_contents(base_path('admin/resources/views/header.blade.php'), '');
    }

    protected function createUserResource()
    {
        $this->callSilent('admin:resource', [
            'resource' => 'User'
        ]);

        $stub = file_get_contents(__DIR__ . '/stubs/Resource/user-resource.stub');

        $resource = str_replace(
            '{{DummyNamespace}}',
            $this->getDefaultModelNamespace(),
            $stub
        );

        $this->createDirectory(app_path('Admin'));

        file_put_contents(app_path('Admin/User.php'), $resource);
    }

    protected function createGitignore()
    {
        file_put_contents(base_path('admin/.gitignore'), '/node_modules');
    }

    protected function copyTranslations()
    {
        $this->files->copyDirectory(base_path('admin/vendor/lang'), base_path('admin/resources/lang'));
    }

    protected function getDefaultModelNamespace()
    {
        $namespace = app()->getNamespace();

        return is_dir(app_path('Models')) ? $namespace . '\\Models\\' : $namespace;
    }
}
