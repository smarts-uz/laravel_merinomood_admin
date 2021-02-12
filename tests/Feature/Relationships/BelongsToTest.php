<?php

namespace Arniro\Admin\Tests\Feature\Relationships;

use Arniro\Admin\Fields\BelongsTo;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\Fixtures\ProductResource;
use Arniro\Admin\Tests\Fixtures\TagResource;
use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\Fixtures\UserResource;
use Arniro\Admin\Tests\IntegrationTest;

class BelongsToTest extends IntegrationTest
{
    /** @var AdminRequest */
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = resolve(AdminRequest::class);

        BelongsTo::clearCachedOptions();
    }

    /** @test */
    public function it_can_fetch_all_items_from_associate_resource()
    {
        factory(User::class, 5)->create();

        $this->assertCount(User::count(), $this->field()->options);
    }

    /** @test */
    public function it_can_fetch_all_items_from_specified_column_in_associate_resource()
    {
        factory(User::class)->create(['name' => 'foo']);
        factory(User::class)->create(['name' => 'bar']);

        $this->assertEquals(2, count(array_filter($this->field()->options, function ($option) {
            return in_array($option['label'], ['foo', 'bar']);
        })));
    }

    /** @test */
    public function it_can_be_filled_with_value_of_resource()
    {
        $emptyProductResource = new ProductResource(new Product);

        $field = $this->field()->fill($emptyProductResource);

        $this->assertNull($field->value);

        $productResource = new ProductResource(
            $product = factory(Product::class)->create()
        );

        $field->fill($productResource);

        $this->assertequals($product->user_id, $field->value);
    }

    /** @test */
    public function it_can_be_filled_with_value_of_via_resource_for_creation()
    {
        request()->merge([
            'viaResource' => 'user-resources',
            'viaResourceId' => $this->user->id,
            'viaRelationship' => 'products'
        ]);

        $productResource = new ProductResource;
        $field = $this->field()->fill($productResource);

        $this->assertEquals($this->user->id, $field->value);
        $this->assertTrue($field->disabled);

        $tagField = BelongsTo::make('Tag', 'tag', TagResource::class)
            ->fill($productResource);

        $this->assertNull($tagField->value);
        $this->assertFalse($tagField->disabled);
    }

    /** @test */
    public function it_can_add_foreign_key_of_relation_to_the_model()
    {
        $user = factory(User::class)->create();
        $data = ['user' => $user->id];

        $product = new Product;
        $this->field()->update($this->request->merge($data), $product);

        $this->assertEquals($user->id, $product->user_id);

        $product = factory(Product::class)->create();
        $this->field()->store($this->request->merge($data), $product);

        $this->assertEquals($user->id, $product->user_id);
    }


    /** @test */
    public function it_can_save_data_from_request()
    {
        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();

        $this->json('POST', 'admin/api/resources/product-resources', [
            'name' => 'Some product',
            'price' => 500,
            'user' => $firstUser->id
        ]);

        $this->assertCount(1, $firstUser->fresh()->products);

        $this->json('PUT', 'admin/api/resources/product-resources/' . Product::first()->id, [
            'name' => 'Some product',
            'price' => 500,
            'user' => $secondUser->id
        ]);

        $this->assertEmpty($firstUser->fresh()->products);
        $this->assertCount(1, $secondUser->fresh()->products);
    }

    /**
     * @return BelongsTo
     */
    protected function field()
    {
        return BelongsTo::make('Пользователь', 'user', UserResource::class);
    }
}
