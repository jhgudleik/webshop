<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Validation\Rule;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/users');
        $this->crud->setEntityNameStrings('пользователь', 'пользователи');
    }

    protected function setupListOperation(): void
    {
        $this->crud->addColumn([
            'name'  => 'id',
            'label' => 'ID',
            'type'  => 'number',
        ]);

        $this->crud->addColumn([
            'name'  => 'name',
            'label' => 'Имя',
            'type'  => 'text',
        ]);

        $this->crud->addColumn([
            'name'  => 'email',
            'label' => 'Email',
            'type'  => 'email',
        ]);

        $this->crud->addColumn([
            'name'  => 'created_at',
            'label' => 'Создан',
            'type'  => 'datetime',
        ]);
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => 'required|string|min:6|confirmed',
        ]);

        $this->crud->field('name')
            ->type('text')
            ->label('Имя')
            ->required();

        $this->crud->field('email')
            ->type('email')
            ->label('Email')
            ->required();

        $this->crud->field('password')
            ->type('password')
            ->label('Пароль')
            ->required()
            ->hint('Минимум 6 символов');

        $this->crud->field('password_confirmation')
            ->type('password')
            ->label('Подтвердите пароль')
            ->required();

        $this->crud->addField([
            'name'   => 'avatar',
            'type'   => 'upload',
            'label'  => 'Аватар',
            'upload' => true,
            'disk'   => 'public',
        ]);
    }

    protected function setupUpdateOperation(): void
    {
        $userId = $this->crud->getCurrentEntryId() ?? $this->crud->entry->getKey();

        $this->crud->setValidation([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
        ]);

        $this->crud->field('name')
            ->type('text')
            ->label('Имя')
            ->required();

        $this->crud->field('email')
            ->type('email')
            ->label('Email')
            ->required();

        $this->crud->field('password')
            ->type('password')
            ->label('Новый пароль')
            ->hint('Оставьте пустым, чтобы не менять')
            ->notRequired();

        $this->crud->field('password_confirmation')
            ->type('password')
            ->label('Подтвердите пароль')
            ->notRequired();

        $this->crud->addField([
            'name'   => 'avatar',
            'type'   => 'upload',
            'label'  => 'Аватар',
            'upload' => true,
            'disk'   => 'public',
        ]);
    }

    public function update()
    {
        if (! request('password')) {
            request()->request->remove('password');
            request()->request->remove('password_confirmation');
        }

        return parent::update();
    }
}
