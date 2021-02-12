<?php

namespace Tests\Unit\Fields;

use Arniro\Admin\Fields\Password;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Tests\Fixtures\User;
use Illuminate\Support\Facades\Hash;
use Orchestra\Testbench\TestCase;

class PasswordFieldTest extends TestCase
{
    protected $field;
    protected $user;
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = Password::make('Password', 'password');
        $this->user = new User;
        $this->request = resolve(AdminRequest::class);
    }

    /** @test */
    public function it_hashes_password_before_persisting_to_the_database()
    {
        $this->field->store($this->request->merge(['password' => '123']), $this->user);
        $this->assertTrue(Hash::check('123', $this->user->password));

        $this->field->update($this->request->merge(['password' => '123']), $this->user);
        $this->assertTrue(Hash::check('123', $this->user->password));
    }

    /** @test */
    public function it_can_be_set_with_confirmation()
    {
        $this->assertFalse($this->field->confirmation);

        $this->field->withConfirmation();
        $this->assertEquals('Password (confirmation)', $this->field->confirmation);

        $this->field->withConfirmation('confirmation');
        $this->assertEquals('confirmation', $this->field->confirmation);
    }
}
