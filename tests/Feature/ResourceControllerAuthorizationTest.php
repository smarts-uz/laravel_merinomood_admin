<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\Fixtures\ProductPolicy;
use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Support\Facades\Gate;

class ResourceControllerAuthorizationTest extends IntegrationTest
{
    /** @test */
    public function it_can_be_restricted_via_policy()
    {
        Gate::policy(Product::class, ProductPolicy::class);

        $this->json('GET', 'admin/api/resources/product-resources/create')
            ->assertOk();

        $this->json('GET', 'admin/api/resources/product-resources')
            ->assertForbidden();
    }

    /** @test */
    public function it_can_be_restricted_via_gate()
    {
        Gate::policy(Product::class, ProductPolicy::class);

        Gate::define('manage-product-resources', function (User $user, Product $product) {
            return true;
        });

        $this->json('GET', 'admin/api/resources/product-resources')
            ->assertOk();
    }
}
