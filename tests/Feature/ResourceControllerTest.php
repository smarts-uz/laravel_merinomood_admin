<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\Fixtures\ProductResource;
use Arniro\Admin\Tests\Fixtures\Tag;
use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Facades\Arniro\Admin\File;

class ResourceControllerTest extends IntegrationTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_store_resource()
    {
        $this->json('POST', 'admin/api/resources/user-resources', [
            'name' => 'Foo',
            'email' => 'foo@gmail.com',
            'password' => '123456'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Foo',
            'email' => 'foo@gmail.com',
        ]);
    }

    /** @test */
    public function its_fields_value_can_be_updated_before_storing_model_to_the_database()
    {
        $this->post('admin/api/resources/tag-resources', ['name' => 'foo']);

        $this->assertDatabaseHas('tags', ['name' => 'bar']);
    }

    /** @test */
    public function its_fields_value_can_be_updated_after_model_is_stored_to_the_database()
    {
        $this->post('admin/api/resources/tag-resources', ['name' => 'foo', 'note' => 'note']);

        $this->assertDatabaseHas('tags', ['note' => 'note' . Tag::latest()->first()->id]);
    }

    /** @test */
    public function its_fields_value_can_be_updated_before_updating_model_in_the_database()
    {
        $tag = factory(Tag::class)->create();
        $this->put("admin/api/resources/tag-resources/{$tag->id}", ['name' => 'foo']);

        $this->assertDatabaseHas('tags', ['name' => 'bar']);
    }

    /** @test */
    public function its_fields_action_is_called_before_deleting_model_in_the_database()
    {
        $this->withoutExceptionHandling();

        $tag = factory(Tag::class)->create([
            'note' => File::store('tags', UploadedFile::fake()->image('note.jpg'))
        ]);

        Storage::assertExists('tags/note.jpg');

        $this->delete("admin/api/resources/tag-resources/{$tag->id}", [
            'resource' => 'tag-resources'
        ]);

        Storage::assertMissing('tags/note.jpg');
    }

    /** @test */
    public function it_can_update_resource()
    {
        $user = factory(User::class)->create();

        $this->json('PUT', 'admin/api/resources/user-resources/'. $user->id, [
            'name' => 'Foo',
            'email' => 'foo@gmail.com',
            'password' => '123456'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Foo',
            'email' => 'foo@gmail.com',
        ])->assertDatabaseMissing('users', [
            'name' => $user->name
        ]);
    }

    /** @test */
    public function it_can_delete_resource()
    {
        $user = factory(User::class)->create();

        $this->json('DELETE', 'admin/api/resources/user-resources/' . $user->id, [
            'resource' => 'user-resources'
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /** @test */
    public function it_can_display_that_resources_should_be_orderable()
    {
        factory(Product::class, 2)->create();

        $this->assertFalse(Arr::get(ProductResource::fetch(), 'orderable'));

        ProductResource::$orderable = true;

        $this->assertTrue(Arr::get(ProductResource::fetch(), 'orderable'));

        ProductResource::$orderable = false;
    }

    /** @test */
    public function it_can_reorder_resources()
    {
        $products = factory(Product::class, 3)->create();

        $indexes = [2, 3, 1];

        $resources = $products->map(function ($product, $key) use ($indexes) {
            return [
                'id' => $product->id,
                'index' => $indexes[$key]
            ];
        });

        $this->post('admin/api/resources/product-resources/reorder', [
            'resources' =>  $resources->toArray()
        ]);

        $this->assertEquals(2, $products->first()->fresh()->index);
        $this->assertEquals(1, $products->last()->fresh()->index);
    }
}
