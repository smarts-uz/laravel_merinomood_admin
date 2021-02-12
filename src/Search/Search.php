<?php

namespace Arniro\Admin\Search;

use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Http\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class Search
{
    /** @var AdminRequest */
    protected $request;

    /** @var Builder */
    protected $builder;

    public function __construct(AdminRequest $request)
    {
        $this->request = $request;
    }

    public function perform($resource)
    {
        /** @var $resource Resource */
        $resource = $this->request->resource($resource);

        $this->setUpQueryBuilder($resource);

        if (! $q = $this->request->input('q')) {
            return $resource->fetch($this->builder);
        }

        $this->builder->where(function ($query) use ($resource, $q) {
            $this->buildWheres($query, $resource, $q);
        });

        return $resource->fetch($this->builder);
    }

    protected function setUpQueryBuilder(Resource $resource)
    {
        $this->builder = $this->request->isViaResource()
            ? $this->request->viaRelationship()
            : $resource->newModel()->newQuery();
    }

    protected function buildWheres(Builder $query, Resource $resource, $q)
    {
        foreach ($resource->getSearchColumns() as $key => $column) {
            $query->where($column, 'like', "%$q%", $this->queryOperator($key));
        }
    }

    protected function queryOperator($index)
    {
        return $index === 0 ? 'and' : 'or';
    }
}
