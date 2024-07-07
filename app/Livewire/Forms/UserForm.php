<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserForm extends Form
{
    public User $form;
    public $name;
    public $email;
    public $password;
    public $role;
    public $is_active = 1;

    public function setUser(User $user)
    {
        $this->fill($user->only("name", "email", "password", "role", "is_active"));
    }

    public function store()
    {
        return User::create(array_merge($this->only("name", "role", "is_active"), ["password" => Hash::make(Str::random(40))]));
    }
}
