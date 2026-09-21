<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        $this->crud->setModel(Product::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/products');
        $this->crud->setEntityNameStrings('товар', 'Товары');

        $this->crud->setListView('admin.products.crud_list');
        $this->crud->setCreateView('admin.products.crud_create');
        $this->crud->setEditView('admin.products.crud_edit');
        $this->crud->setShowView('admin.products.crud_show');
    }

    protected function setupListOperation(): void
    {
        $this->crud->set('list.query', Product::query()->with('category')->orderBy('id'));

        $this->crud->addColumn(['name' => 'id', 'label' => 'ID', 'type' => 'number']);
        $this->crud->addColumn(['name' => 'title', 'label' => 'Название', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'slug', 'label' => 'Slug', 'type' => 'text']);
        $this->crud->addColumn(['name' => 'price', 'label' => 'Цена', 'type' => 'number']);
        $this->crud->addColumn(['name' => 'quantity', 'label' => 'Кол-во', 'type' => 'number']);
        $this->crud->addColumn(['name' => 'active', 'label' => 'Активен', 'type' => 'boolean']);
        $this->crud->addColumn(['name' => 'created_at', 'label' => 'Создан', 'type' => 'datetime']);

        $this->crud->set('list.ajaxTable', false);
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation([
            'category_id' => 'nullable|exists:categories,id',
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:200|unique:products,slug',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'active'      => 'boolean',
            'image'       => 'nullable|image|max:2048',
        ]);

        $this->crud->addField(['name' => 'title', 'type' => 'text', 'label' => 'Название']);
        $this->crud->addField(['name' => 'slug', 'type' => 'text', 'label' => 'Slug', 'hint' => 'URL-адрес, генерируется из названия']);
        $this->crud->addField(['name' => 'category_id', 'type' => 'select', 'label' => 'Категория', 'model' => Category::class, 'attribute' => 'title']);
        $this->crud->addField(['name' => 'price', 'type' => 'number', 'label' => 'Цена']);
        $this->crud->addField(['name' => 'quantity', 'type' => 'number', 'label' => 'Количество']);
        $this->crud->addField(['name' => 'description', 'type' => 'textarea', 'label' => 'Описание']);
        $this->crud->addField(['name' => 'active', 'type' => 'checkbox', 'label' => 'Активен']);
        $this->crud->addField(['name' => 'image', 'type' => 'upload', 'label' => 'Изображение', 'upload' => true, 'disk' => 'public']);
    }

    protected function setupUpdateOperation(): void
    {
        $productId = $this->crud->getCurrentEntryId() ?? $this->crud->entry->getKey();

        $this->crud->setValidation([
            'category_id' => 'nullable|exists:categories,id',
            'title'       => 'required|string|max:255',
            'slug'        => ['required', 'string', 'max:200', Rule::unique('products')->ignore($productId)],
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'active'      => 'boolean',
            'image'       => 'nullable|image|max:2048',
        ]);

        $this->crud->addField(['name' => 'title', 'type' => 'text', 'label' => 'Название']);
        $this->crud->addField(['name' => 'slug', 'type' => 'text', 'label' => 'Slug', 'hint' => 'URL-адрес']);
        $this->crud->addField(['name' => 'category_id', 'type' => 'select', 'label' => 'Категория', 'model' => Category::class, 'attribute' => 'title']);
        $this->crud->addField(['name' => 'price', 'type' => 'number', 'label' => 'Цена']);
        $this->crud->addField(['name' => 'quantity', 'type' => 'number', 'label' => 'Количество']);
        $this->crud->addField(['name' => 'description', 'type' => 'textarea', 'label' => 'Описание']);
        $this->crud->addField(['name' => 'active', 'type' => 'checkbox', 'label' => 'Активен']);
        $this->crud->addField(['name' => 'image', 'type' => 'upload', 'label' => 'Изображение', 'upload' => true, 'disk' => 'public']);
    }
}
