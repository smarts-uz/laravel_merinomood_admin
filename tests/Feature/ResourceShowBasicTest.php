<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Tests\Fixtures\UserResource;

class ResourceShowBasicTest extends ResourceBasicTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->response = $this->getJson('admin/api/resources/user-resources/' . $this->users->first()->id);
        $this->userResource = (new UserResource($this->users->first()))->toResponse(request());
    }

    /** @test */
    public function it_can_show_data()
    {
        $this->response->assertStatus(200);

        $this->response->assertJson([
            'resource' => [
                'data' => [
                    'id' => $this->users->first()->id,
                    'name' => $this->users->first()->name
                ]
            ]
        ]);
    }

    /** @test */
    public function it_has_fields()
    {
        $fields = $this->userResource['resource']['fields'];

        $this->assertCount(count($fields), $this->data()['resource']['fields']);

        $this->response->assertJson([
            'resource' => [
                'fields' => [
                    [
                        'component' => 'text-field',
                        'attribute' => 'name',
                        'label' => 'Name',
                        'value' => $this->users->first()->name
                    ]
                ]
            ]
        ]);
    }
}
