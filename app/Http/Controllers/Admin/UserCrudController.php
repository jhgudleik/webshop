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
        $this->crud->setEntityNameStrings('Пользователь', 'Пользователи');

        $this->crud->setListView('admin.users.crud_list');
        $this->crud->setCreateView('admin.users.crud_create');
        $this->crud->setEditView('admin.users.crud_edit');
        $this->crud->setShowView('admin.users.crud_show');
    }

    protected function setupListOperation(): void
    {
        $this->crud->set('list.query', User::query()->orderBy('id'));

        $this->crud->addColumn(['name' => 'id', 'label' => 'ID', 'type' => 'number']);
        $this->crud->addColumn(['name' => 'name', 'label' => 'Имя', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'email', 'label' => 'Email', 'type' => 'email']);
        $this->crud->addColumn(['name' => 'created_at', 'label' => 'Создан', 'type' => 'datetime']);

        $this->crud->set('list.ajaxTable', false);
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => 'required|string|min:6|confirmed',
        ]);

        $this->crud->addField(['name' => 'name', 'type' => 'text', 'label' => 'Имя']);
        $this->crud->addField(['name' => 'email', 'type' => 'email', 'label' => 'Email']);
        $this->crud->addField(['name' => 'password', 'type' => 'password', 'label' => 'Пароль', 'hint' => 'Минимум 6 символов']);
        $this->crud->addField(['name' => 'password_confirmation', 'type' => 'password', 'label' => 'Подтвердите пароль']);
    }

    protected function setupUpdateOperation(): void
    {
        $userId = $this->crud->getCurrentEntryId() ?? $this->crud->entry->getKey();

        $this->crud->setValidation([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
        ]);

        $this->crud->addField(['name' => 'name', 'type' => 'text', 'label' => 'Имя']);
        $this->crud->addField(['name' => 'email', 'type' => 'email', 'label' => 'Email']);
        $this->crud->addField(['name' => 'password', 'type' => 'password', 'label' => 'Новый пароль', 'hint' => 'Оставьте пустым, чтобы не менять']);
        $this->crud->addField(['name' => 'password_confirmation', 'type' => 'password', 'label' => 'Подтвердите пароль']);
    }

    public function store()
    {
        return parent::store();
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
