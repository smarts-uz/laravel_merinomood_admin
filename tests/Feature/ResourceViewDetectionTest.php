<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Http\Resources\ResourceView;
use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Support\Arr;

class ResourceViewDetectionTest extends IntegrationTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_detect_resource_view_on_index()
    {
        factory(User::class)->create();

        $response = $this->json('GET', 'admin/api/resources/user-resources')
            ->json();

        $this->assertEquals(ResourceView::INDEX, Arr::get($response, 'resources.0.view'));
    }

    /** @test */
    public function it_can_detect_resource_view_on_create()
    {
        factory(User::class)->create();

        $response = $this->json('GET', 'admin/api/resources/user-resources/create')
            ->json();

        $this->assertEquals(ResourceView::CREATE, Arr::get($response, 'resource.view'));
    }

    /** @test */
    public function it_can_detect_resource_view_on_detail()
    {
        $user = factory(User::class)->create();

        $response = $this->json('GET', 'admin/api/resources/user-resources/' . $user->id)
            ->json();

        $this->assertEquals(ResourceView::DETAIL, Arr::get($response, 'resource.view'));
    }

    /** @test */
    public function it_can_detect_resource_view_on_edit()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $response = $this->json('GET', 'admin/api/resources/user-resources/' . $user->id . '/edit')
            ->json();

        $this->assertEquals(ResourceView::EDIT, Arr::get($response, 'resource.view'));
    }
}
