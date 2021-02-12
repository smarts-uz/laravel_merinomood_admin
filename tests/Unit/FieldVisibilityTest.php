<?php

namespace Arniro\Admin\Tests\Unit;

use Arniro\Admin\Fields\Text;
use Arniro\Admin\Http\Resources\ResourceView;
use Arniro\Admin\Tests\Fixtures\UserResource;
use Orchestra\Testbench\TestCase;

class FieldVisibilityTest extends TestCase
{
    protected $field;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = Text::make('Name', 'name');
    }

    /** @test */
    public function it_can_be_hidden_from_specific_page()
    {
        $this->field->hideFromDetail();

        $this->assertEquals([
            'index' => true,
            'create' => true,
            'edit' => true,
            'detail' => false
        ], $this->field->getVisibility());
    }

    /** @test */
    public function it_can_be_hidden_from_several_specific_pages()
    {
        $this->field->hideFromDetail();
        $this->field->hideWhenCreating();

        $this->assertEquals([
            'index' => true,
            'create' => false,
            'edit' => true,
            'detail' => false
        ], $this->field->getVisibility());
    }

    /** @test */
    public function it_can_be_visible_only_on_specific_page()
    {
        $this->field->onlyOnDetail();

        $this->assertEquals([
            'index' => false,
            'create' => false,
            'edit' => false,
            'detail' => true
        ], $this->field->getVisibility());
    }

    /** @test */
    public function it_can_be_set_visible_on_any_page_additionally()
    {
        $this->field->onlyOnDetail();
        $this->field->showWhenCreating();

        $this->assertEquals([
            'index' => false,
            'create' => true,
            'edit' => false,
            'detail' => true
        ], $this->field->getVisibility());
    }

    /** @test */
    public function it_can_accept_callback_for_visibility()
    {
        $this->field->hideFromDetail(function () {
            return 2 === 1;
        });

        $this->field->showOnIndex(function () {
            return 2 === 1;
        });

        $this->assertEquals([
            'index' => false,
            'create' => true,
            'edit' => true,
            'detail' => true
        ], $this->field->getVisibility());

        $this->field->onlyOnDetail(function () {
            return 2 !== 1;
        });

        $show = [
            'index' => false,
            'create' => false,
            'edit' => false,
            'detail' => true
        ];

        $this->assertEquals($show, $this->field->getVisibility());

        $this->field->onlyWhenCreating(function () {
            return 2 === 1;
        });

        $this->assertEquals($show, $this->field->getVisibility());
    }

    /** @test */
    public function it_can_change_visibility_for_all_pages_at_once()
    {
        $this->field->canSee(function () {
            return false;
        });

        $this->assertEquals([
            'index' => false,
            'create' => false,
            'edit' => false,
            'detail' => false
        ], $this->field->getVisibility());
    }

    /** @test */
    public function it_can_be_hidden_from_forms()
    {
        $this->field->hideFromForms();

        $this->assertEquals([
            'index' => true,
            'create' => false,
            'edit' => false,
            'detail' => true
        ], $this->field->getVisibility());
    }

    /** @test */
    public function it_can_be_visible_only_on_forms()
    {
        $this->field->onlyOnForms();

        $this->assertEquals([
            'index' => false,
            'create' => true,
            'edit' => true,
            'detail' => false
        ], $this->field->getVisibility());
    }

    /** @test */
    public function resolveUsing_method_is_called_only_for_specified_pages()
    {
        $userResource = \Mockery::mock((new UserResource)->forView(ResourceView::INDEX))
            ->shouldAllowMockingProtectedMethods();

        $called = null;
        $this->field->onlyOnIndex()->resolveUsing(function () use (&$called) {
            $called = true;
        });

        $userResource->shouldReceive('indexFields')
            ->andReturn([$this->field]);

        $userResource->getFields();

        $this->assertNull($called);
    }
}
