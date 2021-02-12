<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\Fixtures\ProductPolicy;
use Arniro\Admin\Tests\Fixtures\ProductResource;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class AuthorizationTest extends IntegrationTest
{
    /** @test */
    public function only_authorized_users_can_access_admin()
    {
        auth()->logout();

        $this->get('admin/api/resources/user-resources')
            ->assertRedirect('admin/login');

        $this->actingAs($this->user);

        $this->get('admin/api/resources/user-resources')
            ->assertStatus(200);
    }

    /** @test */
    public function some_actions_can_be_denied()
    {
        $resource = new ProductResource(factory(Product::class)->create());

        $this->assertEquals(true, $resource::authorizedToCreate());

        Gate::policy(Product::class, ProductPolicy::class);

        $this->assertEquals(true, $resource::authorizedToCreate());

        $this->assertEquals([
            'view' => false,
            'create' => true,
            'update' => true,
            'delete' => true,
        ], $resource->detailAbilities());
    }

    /** @test */
    public function collection_is_authorized_if_at_least_on_item_is()
    {
        Gate::policy(Product::class, ProductPolicy::class);

        $resources = ProductResource::collection(factory(Product::class, 10)->create());

        $this->assertTrue(Arr::get($resources, 'can.view'));
        $this->assertTrue(Arr::get($resources, 'can.create'));
    }
}
