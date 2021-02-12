<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends IntegrationTest
{
    use RefreshDatabase;

    /** @test */
    public function it_can_search_resource()
    {
        factory(Product::class, 10)->create(['name' => 'foo bar']);

        $this->get('admin/api/search/product-resources?q=bar')
            ->assertJsonCount(10, 'resources');

        $this->get('admin/api/search/product-resources?q=missing')
            ->assertJsonCount(0, 'resources');
    }

    /** @test */
    public function it_can_search_resource_by_several_columns()
    {
        factory(Product::class, 3)->create(['name' => 'foo bar 15']);
        factory(Product::class, 2)->create(['price' => 15]);
        factory(Product::class, 8)->create(['name' => 'missed', 'price' => 0]);

        $this->get('admin/api/search/product-resources?q=15')
            ->assertJsonCount(5, 'resources');
    }
}
