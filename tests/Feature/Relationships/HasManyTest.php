<?php

namespace Arniro\Admin\Tests\Feature\Relationships;

use Arniro\Admin\Fields\HasMany;
use Arniro\Admin\Http\Resources\ResourceView;
use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\Fixtures\ProductResource;
use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\Fixtures\UserResource;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

class HasManyTest extends IntegrationTest
{
    use RefreshDatabase;

    /** @test */
    public function it_adds_associate_resources_to_a_response()
    {
        $products = factory(Product::class, 5)->create([
            'user_id' => $this->user->id
        ]);

        $field = HasMany::make('products', ProductResource::class);

        $resource = new UserResource($this->user);

        $field->fill($resource);

        $this->assertCount(5, $field->value['resources']);

        $this->assertEquals($products->first()->name, Arr::get($field->value, 'resources.0.data.name'));
    }

    /** @test */
    public function resources_have_index_view()
    {
        factory(Product::class)->create();

        $field = HasMany::make('products', ProductResource::class);
        $resource = new UserResource($this->user);

        $field->fill($resource);

        $this->assertEquals(ResourceView::INDEX, Arr::get($field->value, 'resources.0.view'));
    }

    /** @test */
    public function it_fetches_resources_belonging_to_a_parent_resource()
    {
        factory(Product::class, 5)->create([
            'user_id' => $this->user->id
        ]);

        $john = factory(User::class)->create();

        factory(Product::class, 10)->create([
            'user_id' => $john->id
        ]);

        $this->get($this->url())
            ->assertJsonCount(5, 'resources');
    }

    /** @test */
    public function it_paginates_collection_by_its_own_request_key()
    {
        factory(Product::class, 20)->create([
            'user_id' => $this->user->id
        ]);

        $this->get($this->url(['products_page' => 2]))
            ->assertJson([
                'pagination' => [
                    'current_page' => 2
                ]
            ]);
    }

    /** @test */
    public function it_can_search_resources_that_belong_to_another_resource()
    {
        factory(Product::class, 5)->create([
            'user_id' => $this->user->id,
            'name' => 'foo bar'
        ]);

        $john = factory(User::class)->create();
        factory(Product::class, 3)->create([
            'user_id' => $john->id,
            'name' => 'foo bar'
        ]);

        $this->get($this->url(
            ['q' => 'bar'],
            'admin/api/search/product-resources'
        ))->assertJsonCount(5, 'resources');

        $this->get('admin/api/search/product-resources?q=missing')
            ->assertJsonCount(0, 'resources');
    }

    protected function url($params = [], $url = 'admin/api/resources/product-resources')
    {
        $params = array_merge([
            'viaResource' => 'user-resources',
            'viaResourceId' => $this->user->id,
            'viaRelationship' => 'products'
        ], $params);

        foreach ($params as $key => &$param) {
            $param = "$key=$param";
        }

        return sprintf('%s?%s', $url, implode('&', array_values($params)));
    }
}
