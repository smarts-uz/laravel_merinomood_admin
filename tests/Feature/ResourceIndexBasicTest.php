<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\Fixtures\UserResource;

class ResourceIndexBasicTest extends ResourceBasicTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->response = $this->getJson('admin/api/resources/user-resources');
    }

    /** @test */
    public function it_has_resource_name_as_a_string_in_plural_form_of_kebab_case()
    {
        $this->response->assertJson([
            'name' => 'user-resources'
        ]);
    }

    /** @test */
    public function it_has_label()
    {
        $this->response->assertJson([
            'label' => 'User Resources'
        ]);
    }

    /** @test */
    public function it_has_fields()
    {
        $usersResource = UserResource::collection(User::latest()->get());
        $fields = $usersResource['resources'][0]['fields'];

        $this->assertCount(count($fields), $this->data()['resources'][0]['fields']);

        $this->response->assertJson([
            'resources' => [
                [
                    'fields' => [
                        [
                            'component' => $fields[0]['component'],
                            'attribute' => $fields[0]['attribute'],
                            'label' => $fields[0]['label']
                        ]
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function resource_only_have_primitive_fields_on_index_view()
    {
        $usersResource = UserResource::collection(User::latest()->get());
        $fields = $usersResource['resources'][0]['fields'];

        $this->assertNull(collect($fields)->where('attribute', 'products')->first());
    }

    /** @test */
    public function it_can_list_data()
    {
        $this->response->assertStatus(200);
        $this->assertCount($this->users->count() + 1, $this->data()['resources']);

        $this->assertEquals($this->users->first()->id, $this->data()['resources'][1]['data']['id']);
        $this->assertEquals($this->users->first()->name, $this->data()['resources'][1]['data']['name']);
    }

    /** @test */
    public function it_can_paginate_data()
    {
        $response = $this->getJson('admin/api/resources/product-resources')
            ->assertJsonCount(10, 'resources')
            ->assertJson([
                'pagination' => [
                    'total' => 50,
                    'current_page' => 1,
                    'last_page' => 5,
                    'from' => 1,
                    'to' => 10
                ]
            ]);
    }
}
