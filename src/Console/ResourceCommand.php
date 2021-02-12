<?php

namespace Arniro\Admin\Console;

use Illuminate\Support\Str;

class ResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:resource {resource} {--fields=}';

    protected $fields = [];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate admin resource';

    protected $viewsPath;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->fields = $this->option('fields') ?
            explode(',', $this->option('fields')) : [];

        $this->createResource();
        $this->updateFrontendRoutes();
        $this->createFrontendViews();

        $this->info('Admin resource has been successfully created!');
    }

    protected function createResource()
    {
        $resourceContents = file_get_contents(__DIR__ . '/stubs/Resource/resource.stub');

        $model = $this->getDefaultModelNamespace() . $this->resource();
        $fields = implode(",\n\t\t\t", array_map(function ($field) {
            $label = Str::ucfirst($field);

            return "Text::make('{$label}', '{$field}')";
        }, $this->fields));

        $resourceContents = str_replace(
            ['{{DummyNamespace}}', 'DummyClass', 'DummyModel', '{{ fields }}'],
            [app()->getNamespace(), $this->resource(), $model, $fields ?: '//'],
            $resourceContents
        );

        $this->createDirectory(app_path('Admin'));

        file_put_contents(app_path("Admin/{$this->resource()}.php"), $resourceContents);
    }

    protected function updateFrontendRoutes()
    {
        $routes = file_get_contents($routesPath = $this->jsPath('routes.js'));

        file_put_contents($routesPath, str_replace(
            '...Router',
            "...Router\n\t\t.add('{$this->resourceName()}')",
            $routes
        ));
    }

    protected function createFrontendViews()
    {
        $this->viewsPath = $this->jsPath('views/' . $this->pluralResource());

        $this->createDirectory($this->viewsPath);

        $views = ['index', 'detail', 'create', 'edit'];

        foreach ($views as $view) {
            $this->createView($view);
        }

        file_put_contents(
            base_path('admin/resources/views/nav.blade.php'),
            sprintf(
                PHP_EOL . '<nav-link to="/%s" icon="list">%s</nav-link>',
                $this->resourceName(),
                $this->pluralResource()
            ),
            FILE_APPEND
        );
    }

    protected function resource()
    {
        return $this->argument('resource');
    }

    protected function pluralResource()
    {
        return Str::plural($this->resource());
    }

    protected function resourceName()
    {
        return Str::snake($this->pluralResource(), '-');
    }

    protected function createView($name)
    {
        $lowerResource = Str::camel($this->resource());
        $viewContents = file_get_contents(__DIR__ . "/stubs/Resource/{$name}-view.stub");
        $viewName = Str::ucfirst($name);
        $resourceName = Str::camel($this->pluralResource());

        file_put_contents("{$this->viewsPath}/{$viewName}.vue", str_replace(
            ['resourceName', 'lowerResource', 'snakedResourceName'],
            [$resourceName, $lowerResource, Str::snake($resourceName, '-')],
            $viewContents
        ));
    }

    /**
     * Get the path to the directory with js files.
     *
     * @param string $path
     * @return string
     */
    protected function jsPath($path)
    {
        return base_path('admin/resources/js/' . $path);
    }

    protected function getDefaultModelNamespace()
    {
        $namespace = rtrim(app()->getNamespace(), '\\');

        return is_dir(app_path('Models')) ? $namespace . '\\Models\\' : $namespace;
    }
}
