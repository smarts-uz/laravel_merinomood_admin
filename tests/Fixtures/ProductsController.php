<?php

namespace Arniro\Admin\Tests\Fixtures;

use Arniro\Admin\Http\Controllers\Controller;
use Arniro\Admin\Http\Requests\AdminRequest;

class ProductsController extends Controller
{
    public function index()
    {
        return ProductResource::fetch();
    }

    public function show($product)
    {
        $product = Product::find($product);

        return new ProductResource($product);
    }

    public function create()
    {
        return new ProductResource;
    }

    public function store(ProductResource $resource, AdminRequest $request)
    {
        return $resource->store($request);
    }

    public function update($product, AdminRequest $request)
    {
        $product = Product::find($product);

        return (new ProductResource($product))->update($request);
    }
}
