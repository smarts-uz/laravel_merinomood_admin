<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\IntegrationTest;

abstract class ResourceBasicTest extends IntegrationTest
{
    protected $users;
    protected $userResource;
    protected $response;
    protected $products;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = factory(User::class, 5)->create();
        $this->products = factory(Product::class, 50)->create();
    }

    protected function data()
    {
        return $this->response->decodeResponseJson();
    }
}
