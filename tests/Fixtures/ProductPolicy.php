<?php

namespace Arniro\Admin\Tests\Fixtures;

class ProductPolicy
{
    public function view(User $user, Product $product)
    {
        return $product->id === Product::first()->id + 1;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }
}
