<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\Fixtures\ProductResource;
use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\Fixtures\UserResource;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminRequestTest extends IntegrationTest
{
    /** @var AdminRequest */
    protected $request;

    /**
     * @var integer
     */
    protected $userId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = resolve(AdminRequest::class);

        factory(Product::class)->create(['user_id' => $this->userId = User::first()->id]);
    }

    /** @test */
    public function it_can_get_any_value_from_request()
    {
        $this->request->merge([
            'foo' => 'bar'
        ]);

        $this->assertEquals('bar', $this->request->input('foo'));
    }

    /** @test */
    public function it_can_return_fully_qualified_path_to_the_resource_from_request()
    {
        $this->addResourceToRequest();

        $this->assertEquals(UserResource::class, $this->request->resourceClass());
    }

    /** @test */
    public function it_can_return_fully_qualified_path_to_the_resource_from_custom_parameter()
    {
        $this->assertEquals(UserResource::class, $this->request->resourceClass('user-resources'));
    }

    /** @test */
    public function it_can_return_resource_instance_from_request()
    {
        $this->addResourceToRequest();

        $this->assertInstanceOf(UserResource::class, $this->request->resource());
    }

    /** @test */
    public function it_can_return_resource_instance_from_custom_parameter()
    {
        $this->assertInstanceOf(UserResource::class, $this->request->resource('user-resources'));
    }

    /** @test */
    public function it_can_return_new_resource_model_instance_from_request()
    {
        $this->addResourceToRequest();

        $this->assertInstanceOf(User::class, $this->request->newModel());
    }

    /** @test */
    public function it_can_return_model_instance_from_custom_resource_parameter()
    {
        $model = $this->request->newModel('user-resources');

        $this->assertInstanceOf(User::class, $model);
    }

    /** @test */
    public function it_can_return_existing_resource_model_instance_from_request()
    {
        $this->addResourceToRequest();

        $model = $this->request->model();

        $this->assertInstanceOf(User::class, $model);
        $this->assertEquals($this->userId, $model->id);
    }

    /** @test */
    public function it_can_return_existing_model_instance_from_custom_resource_id_parameter()
    {
        $model = $this->request->model('user-resources', $this->userId);

        $this->assertEquals($this->userId, $model->id);
    }

    /** @test */
    public function it_can_determine_whether_resource_is_fetched_via_another_one_from_request()
    {
        $this->assertFalse($this->request->isViaResource());

        $this->addViaResourceToRequest();

        $this->assertTrue($this->request->isViaResource());

        $this->request->merge([
            'viaResource' => 'undefined'
        ]);

        $this->assertFalse($this->request->isViaResource());
    }

    /** @test */
    public function it_can_return_via_resource_instance_from_request()
    {
        $this->addViaResourceToRequest();

        $this->assertInstanceOf(ProductResource::class, $this->request->viaResource());
    }

    /** @test */
    public function it_can_return_existing_model_of_via_resource_from_request()
    {
        $this->addResourceToRequest();
        $this->addViaResourceToRequest();

        $this->assertInstanceOf(Product::class, $this->request->viaModel());
        $this->assertEquals($this->userId, $this->request->viaModel()->id);
    }

    /** @test */
    public function it_can_return_model_class_of_via_resource()
    {
        $this->addResourceToRequest();
        $this->addViaResourceToRequest();

        $this->assertEquals(Product::class, $this->request->viaModelClass());
    }

    /** @test */
    public function it_can_return_instance_of_via_relationship_from_request()
    {
        $this->addResourceToRequest();
        $this->addViaResourceToRequest();

        $this->assertInstanceOf(BelongsTo::class, $this->request->viaRelationship());
    }

    protected function addViaResourceToRequest()
    {
        $this->request->merge([
            'viaResource' => 'product-resources',
            'viaResourceId' => Product::first()->id,
            'viaRelationship' => 'user'
        ]);

        return $this;
    }

    protected function addResourceToRequest()
    {
        $this->request->merge([
            'resource' => 'user-resources',
            'resourceId' => $this->userId
        ]);

        return $this;
    }
}
