<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Fields\Custom;
use Arniro\Admin\Fields\Text;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Tests\Fixtures\Post;
use Arniro\Admin\Tests\Fixtures\PostResource;
use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\Fixtures\UserResource;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Support\Arr;

class FieldTest extends IntegrationTest
{
    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = Text::make('Name', 'name');
    }

    /** @test */
    public function it_has_a_label()
    {
        $this->assertEquals('Name', $this->field->label);
    }

    /** @test */
    public function it_has_an_attribute()
    {
        $this->assertEquals('name', $this->field->attribute);
    }

    /** @test */
    public function it_has_a_component()
    {
        $this->assertEquals('text-field', $this->field->component);
    }

    /** @test */
    public function it_can_return_value()
    {
        $user = factory(User::class)->create(['name' => 'Foo bar']);
        $resource = new UserResource($user);

        $this->assertEquals('Foo bar', $this->field->getValue($resource));
    }

    /** @test */
    public function it_returns_nullable_value_when_empty_model_is_provided()
    {
        $resource = new UserResource(new User);

        $this->assertNull($this->field->getValue($resource));
    }

    /** @test */
    public function it_returns_value_translations_if_the_attribute_is_translatable()
    {
        $post = factory(Post::class)->create([
            'name' => [
                'ru' => 'foo',
                'en' => 'bar'
            ],
            'slug' => 'foo'
        ]);
        $resource = new PostResource($post);

        $slugField = Text::make('Slug', 'slug');

        $this->assertEquals('foo', $slugField->getValue($resource));

        $this->assertEquals([
            'ru' => 'foo',
            'en' => 'bar'
        ], $this->field->getValue($resource));

        $resource = new PostResource(new Post);
        $this->assertEquals([
            'ru' => null,
            'en' => null
        ], $this->field->getValue($resource));
    }

    /** @test */
    public function it_can_return_public_properties_for_response()
    {
        $user = factory(User::class)->create(['name' => 'Foo Bar']);
        $resource = new UserResource($user);

        $response = $this->field->toResponse($resource);

        $this->assertEquals('Name', Arr::get($response, 'label'));
        $this->assertEquals('text-field', Arr::get($response, 'component'));
        $this->assertEquals('Foo Bar', Arr::get($response, 'value'));
    }

    /** @test */
    public function it_marked_as_translatable_if_the_attribute_is()
    {
        $post = factory(Post::class)->create([
            'name' => [
                'ru' => 'foo',
                'en' => 'bar'
            ],
            'slug' => 'foo'
        ]);
        $resource = new PostResource($post);

        $this->assertTrue(Arr::get($this->field->toResponse($resource), 'translatable'));
    }

    /** @test */
    public function it_returns_value_with_translations_for_response_if_the_attribute_is_translatable()
    {
        $post = factory(Post::class)->create([
            'name' => [
                'ru' => 'foo',
                'en' => 'bar'
            ],
            'slug' => 'foo'
        ]);
        $resource = new PostResource($post);

        $this->assertEquals([
            'ru' => 'foo',
            'en' => 'bar'
        ], Arr::get($this->field->toResponse($resource), 'value'));
    }

    /** @test */
    public function it_shouldnt_use_fallback_locale_for_not_set_values_in_translatable_attributes()
    {
        $post = factory(Post::class)->create([
            'name' => [
                'en' => 'foo',
                'ru' => null
            ],
            'slug' => 'foo'
        ]);
        $resource = new PostResource($post);

        $this->assertEquals([
            'en' => 'foo',
            'ru' => null
        ], Arr::get($this->field->toResponse($resource), 'value'));
    }

    /** @test */
    public function its_value_can_be_overridden()
    {
        $this->field->resolveUsing(function () {
            return 'custom';
        });

        $this->field->fill(
            new UserResource(
                factory(User::class)->create()
            )
        );

        $this->assertEquals('custom', $this->field->value);
    }


    /** @test */
    public function it_can_be_sortable()
    {
        $this->assertFalse($this->field->sortable);

        $this->field->sortable();

        $this->assertTrue($this->field->sortable);
    }

    /** @test */
    public function it_can_be_aligned()
    {
        $this->field->align('right');

        $this->assertEquals('right', $this->field->align);
    }

    /** @test */
    public function it_has_rules()
    {
        $this->field->rules('required', 'max:255');

        $this->assertEquals(['required', 'max:255'], $this->field->getCreationRules());

        $this->field->rules(['required', 'max:255']);

        $this->assertEquals(['required', 'max:255'], $this->field->getCreationRules());
    }

    /** @test */
    public function specific_rules_is_merged_with_basic_rules()
    {
        $this->field->rules('required')->creationRules('max:255');
        $this->assertEquals(['required', 'max:255'], $this->field->getCreationRules());

        $this->field->rules('required')->updateRules('max:255');
        $this->assertEquals(['required', 'max:255'], $this->field->getUpdateRules());
    }

    /** @test */
    public function resource_id_is_substituted_in_update_rules()
    {
        $this->field->updateRules('unique:users,email,{{resourceId}}');

        $this->assertEquals(['unique:users,email,' . $this->user->id], $this->field->getUpdateRules($this->user));
    }

    /** @test */
    public function it_can_have_custom_validation_rules_messages()
    {
        $message = 'You need to fill this field.';

        $this->field->rules([
            'required' => $message,
            'max:255'
        ]);

        $this->assertContains('required', $this->field->getCreationRules());
        $this->assertContains('max:255', $this->field->getCreationRules());

        $this->assertEquals([
            'name.required' => $message
        ], $this->field->getCreationMessages());
    }

    /** @test */
    public function specific_validation_messages_is_merged_with_basic_ones()
    {
        $firstMessage = 'Some message.';
        $secondMessage = 'Another message.';

        $this->field->rules([
            'required' => $firstMessage
        ]);

        $this->field->creationRules([
            'max:255' => $secondMessage
        ]);

        $this->assertEquals([
            'name.required' => $firstMessage,
            'name.max:255' => $secondMessage
        ], $this->field->getCreationMessages());

        $this->field->rules([
            'required' => $firstMessage
        ]);

        $this->field->updateRules([
            'max:255' => $secondMessage
        ]);

        $this->assertEquals([
            'name.required' => $firstMessage,
            'name.max:255' => $secondMessage
        ], $this->field->getUpdateMessages());
    }

    /** @test */
    public function it_can_store_model_using_custom_callback()
    {
        $user = new User;

        $field = Custom::make('Custom', 'custom')->updateVia(function ($data, $user) {
            $user->name = 'Changed';
        });

        $field->update(resolve(AdminRequest::class), $user);

        $this->assertEquals('Changed', $user->name);
    }

    /** @test */
    public function it_can_update_model_using_custom_callback()
    {
        $user = factory(User::class)->create();

        $field = Custom::make('Custom', 'custom')->updateVia(function ($data, $user) {
            $user->name = 'Changed';
        });

        $field->update(resolve(AdminRequest::class), $user);

        $this->assertEquals('Changed', $user->name);
    }

    /** @test */
    public function it_can_disable_locales_display()
    {
        auth()->logout();

        $this->field->canSeeLocales(function () {
            return auth()->user();
        });
        $this->assertFalse($this->field->showLocales);

        $this->actingAs(User::first());

        $this->field->canSeeLocales(function () {
            return auth()->user();
        });
        $this->assertTrue($this->field->showLocales);
    }
}
