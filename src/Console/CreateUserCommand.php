<?php

namespace Arniro\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CreateUserCommand extends Command
{
    /**
     *
     * @var string
     */
    protected $signature = 'admin:user';

    /**
     *
     * @var string
     */
    protected $description = 'Create User';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->display(
            $this->fillUser($this->user())
        );
    }

    protected function fillUser(Model $user)
    {
        $user->name = $this->ask('Name');
        $user->email = $this->ask('Email');

        while ($emailError = $this->isValidEmail($user->email)) {
            $this->error($emailError);

            $user->email = $this->ask('Email');
        }

        $user->password = bcrypt($this->secret('Password'));

        return tap($user)->save();
    }

    protected function display(Model $user)
    {
        $headers = ['Name', 'Email'];
        $fields = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        $this->info('User created successfully!');

        $this->table($headers, [$fields]);
    }

    protected function isValidEmail(string $email)
    {
        $data = ['email' => $email];
        $validator = Validator::make($data, [
            'email' => 'unique:users,email'
        ]);

        return $validator->errors()->first('email');
    }

    protected function user()
    {
        $class = $this->getDefaultNamespace() . 'User';

        return new $class;
    }

    protected function getDefaultNamespace()
    {
        $namespace = rtrim(app()->getNamespace(), '\\');

        return is_dir(app_path('Models')) ? $namespace . '\\Models\\' : $namespace;
    }
}

