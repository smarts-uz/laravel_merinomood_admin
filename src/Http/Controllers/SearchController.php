<?php

namespace Arniro\Admin\Http\Controllers;

use Arniro\Admin\Search\Search;

class SearchController extends Controller
{
    public function __invoke($resourceName, Search $search)
    {
        return $search->perform($resourceName);
    }
}
