<?php

namespace Tests\Unit\Fields;

use Arniro\Admin\Fields\Boolean;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase;

class BooleanFieldTest extends TestCase
{
    protected $field;
    protected $user;
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = Boolean::make('Active', 'active');
        $this->user = new User;

        $this->request = resolve(AdminRequest::class);
    }

    /** @test */
    public function it_casts_to_boolean_when_string_representation_of_boolean_is_provided()
    {
        $this->field->update($this->request->merge(['active' => 'true']), $this->user);
        $this->assertTrue($this->user->active);

        $this->field->store($this->request->merge(['active' => 'true']), $this->user);
        $this->assertTrue($this->user->active);

        $this->field->update($this->request->merge(['active' => 'false']), $this->user);
        $this->assertFalse($this->user->active);

        $this->field->store($this->request->merge(['active' => 'false']), $this->user);
        $this->assertFalse($this->user->active);
    }

    /** @test */
    public function it_casts_to_boolean_when_integer_is_provided()
    {
        $this->field->store($this->request->merge(['active' => 1]), $this->user);
        $this->assertTrue($this->user->active);

        $this->field->update($this->request->merge(['active' => 1]), $this->user);
        $this->assertTrue($this->user->active);

        $this->field->store($this->request->merge(['active' => 0]), $this->user);
        $this->assertFalse($this->user->active);

        $this->field->update($this->request->merge(['active' => 0]), $this->user);
        $this->assertFalse($this->user->active);
    }

    /** @test */
    public function it_casts_to_boolean_when_string_representation_of_integer_is_provided()
    {
        $this->field->store($this->request->merge(['active' => '1']), $this->user);
        $this->assertTrue($this->user->active);

        $this->field->update($this->request->merge(['active' => '1']), $this->user);
        $this->assertTrue($this->user->active);

        $this->field->store($this->request->merge(['active' => '0']), $this->user);
        $this->assertFalse($this->user->active);

        $this->field->update($this->request->merge(['active' => '0']), $this->user);
        $this->assertFalse($this->user->active);
    }
}
