<?php

namespace Arniro\Admin\Http\Resources;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait ViewDetection
{
    protected $view;

    public function forView($view)
    {
        $this->view = $view;

        return $this;
    }

    protected function setCurrentView()
    {
        if (! request()->route() || $this->view)
            return $this;

        $controllerMethod = Str::after(request()->route()->getAction()['controller'], '@');

        if ($view = Arr::get($this->resourceViewMap(), $controllerMethod)) {
            $this->view = $view;
        }

        return $this;
    }

    protected function resourceViewMap()
    {
        return [
            'index' => ResourceView::INDEX,
            'show' => ResourceView::DETAIL,
            'create' => ResourceView::CREATE,
            'edit' => ResourceView::EDIT
        ];
    }
}
