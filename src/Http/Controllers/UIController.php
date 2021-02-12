<?php

namespace Arniro\Admin\Http\Controllers;

use Arniro\Admin\UI;

class UIController extends Controller
{
    public function __invoke()
    {
        return [
            'variables' => UI::getVariables()
        ];
    }
}
