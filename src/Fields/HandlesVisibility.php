<?php

namespace Arniro\Admin\Fields;

use Arniro\Admin\Http\Resources\ResourceView;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HandlesVisibility
{
    protected $show = [
        ResourceView::INDEX => true,
        ResourceView::DETAIL => true,
        ResourceView::CREATE => true,
        ResourceView::EDIT => true
    ];

    public function hideFromIndex($callback = null)
    {
        $this->hideFrom(ResourceView::INDEX, $callback);

        return $this;
    }

    public function hideFromDetail($callback = null)
    {
        $this->hideFrom(ResourceView::DETAIL, $callback);

        return $this;
    }

    public function hideWhenCreating($callback = null)
    {
        $this->hideFrom(ResourceView::CREATE, $callback);

        return $this;
    }

    public function hideWhenUpdating($callback = null)
    {
       $this->hideFrom(ResourceView::EDIT, $callback);

       return $this;
    }

    public function showOnIndex($callback = null)
    {
        $this->showOn(ResourceView::INDEX, $callback);

        return $this;
    }

    public function showOnDetail($callback = null)
    {
        $this->showOn(ResourceView::DETAIL, $callback);

        return $this;
    }

    public function showWhenCreating($callback = null)
    {
        $this->showOn(ResourceView::CREATE, $callback);

        return $this;
    }

    public function showWhenUpdating($callback = null)
    {
       $this->showOn(ResourceView::EDIT, $callback);

       return $this;
    }

    public function onlyOnIndex($callback = null)
    {
        $this->onlyOn(ResourceView::INDEX, $callback);

        return $this;
    }

    public function onlyOnDetail($callback = null)
    {
        $this->onlyOn(ResourceView::DETAIL, $callback);

        return $this;
    }

    public function onlyWhenCreating($callback = null)
    {
        $this->onlyOn(ResourceView::CREATE, $callback);

        return $this;
    }

    public function onlyWhenUpdating($callback = null)
    {
        $this->onlyOn(ResourceView::EDIT, $callback);

        return $this;
    }

    public function hideFromForms($callback = null)
    {
        $this->hideFrom(ResourceView::CREATE, $callback);
        $this->hideFrom(ResourceView::EDIT, $callback);

        return $this;
    }

    public function onlyOnForms($callback = null)
    {
        $this->hideFrom(ResourceView::INDEX, $callback);
        $this->hideFrom(ResourceView::DETAIL, $callback);
        $this->showOn(ResourceView::CREATE, $callback);
        $this->showOn(ResourceView::EDIT, $callback);

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @deprecated
     * @return $this
     */
    public function __call($name, $arguments)
    {
        [$page, $visibility] = $this->getParts($name);

        $callback = null;

        if (count($arguments) === 1) {
            $callback = $arguments[0];
        }

        if ($visibility === 'only') {
            $this->onlyOn($page, $callback);
        }

        if ($visibility === 'hide') {
            $this->hideFrom($page, $callback);
        }

        if ($visibility === 'show') {
            $this->showOn($page, $callback);
        }

        return $this;
    }

    /**
     * @param $name
     * @deprecated
     * @return array
     */
    protected function getParts($name)
    {
        $parts = explode('_', Str::snake($name));
        $page = $parts[2];
        $visibility = $parts[0];

        if (!in_array($visibility, ['only', 'hide', 'show'])) {
            throw new \BadMethodCallException(
                sprintf(
                    '%s method is not found in %s class',
                    $name,
                    get_class()
                )
            );
        }

        return [$page, $visibility];
    }

    protected function onlyOn($page, ?Callable $callback)
    {
        if ($callback && !app()->call($callback)) return;

        foreach ($this->show as $key => &$item) {
            $item = $key === $page;
        }
    }

    protected function hideFrom($page, ?Callable $callback): void
    {
        $this->show[$page] = $callback ? !app()->call($callback) : false;
    }

    protected function showOn($page, ?Callable $callback): void
    {
        $this->show[$page] = $callback ? app()->call($callback) : true;
    }

    public function getVisibility()
    {
        return $this->show;
    }

    public function canSee($callback)
    {
        if ($callback instanceof Closure ? app()->call($callback) : $callback) {
            return $this;
        }

        $this->show = array_map(function () {
            return false;
        }, $this->show);

        return $this;
    }

    public function canSeeWhen($action, $model)
    {
        return $this->canSee(function () use ($action, $model) {
            return auth()->user()->can($action, $model);
        });
    }

    public function hidden()
    {
        return $this->canSee(false);
    }

    public function isDisplayedFor($view)
    {
        return Arr::get($this->show, $view);
    }
}
