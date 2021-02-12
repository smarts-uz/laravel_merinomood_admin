<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\IntegrationTest;

class ResourceControllerValidationTest extends IntegrationTest
{
    /** @test */
    public function it_returns_validation_errors()
    {
        $this->json('POST', 'admin/api/resources/user-resources')
            ->assertJsonValidationErrors(['name', 'email', 'password']);

        factory(User::class)->create();

        $this->json('PUT', 'admin/api/resources/user-resources/2')
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function it_returns_validation_errors_for_translatable_attributes()
    {
        $this->json('POST', 'admin/api/resources/post-resources', [
            'name' => [
                'ru' => null,
                'kg' => null
            ],
        ])->assertJsonValidationErrors(['name.ru', 'name.kg']);
    }

    /** @test */
    public function it_returns_validation_errors_for_specific_translation()
    {
        $this->json('POST', 'admin/api/resources/post-resources', [
            'surname' => [
                'ru' => null,
                'kg' => null
            ],
        ])->assertJsonValidationErrors(['surname.ru'])
            ->assertJsonMissingValidationErrors(['surname.kg']);
    }

    /** @test */
    public function it_can_have_custom_validation_messages()
    {
        $this->json('POST', 'admin/api/resources/user-resources', [
            'email' => 'foo@gmail.com',
            'password' => '123456'
        ])->assertJsonValidationErrors([
            'name' => 'You need to provide the name while creating.'
        ]);

        factory(User::class)->create();

        $this->json('PUT', 'admin/api/resources/user-resources/2', [
            'email' => 'foo@gmail.com',
            'password' => '123456'
        ])->assertJsonValidationErrors([
            'name' => 'You need to provide the name while updating.'
        ]);
    }


    /** @test */
    public function validation_messages_use_attributes_with_lang_prefix_when_field_is_translatable()
    {
        $this->json('POST', 'admin/api/resources/post-resources', [
            'name' => [
                'ru' => null,
            ],
        ])->assertJsonValidationErrors(['name.ru' => 'This is required.']);
    }


    /** @test */
    public function it_can_have_custom_validation_messages_for_specific_translation()
    {
        $this->json('POST', 'admin/api/resources/post-resources', [
            'surname' => [
                'ru' => null,
                'kg' => null
            ],
        ])->assertJsonValidationErrors(['surname.ru' => 'This is required.'])
            ->assertJsonMissingValidationErrors(['surname.kg' => 'This is required.']);
    }
}
