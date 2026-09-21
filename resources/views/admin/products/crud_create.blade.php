@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Создание товара</h1>

            <a href="{{ backpack_url('products') }}"
               class="btn btn-secondary">
                Назад
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ backpack_url('products') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">

                    <div class="mb-3">
                        <label for="title" class="form-label">Название</label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text"
                               id="slug"
                               name="slug"
                               value="{{ old('slug') }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               placeholder="smartfony"
                               required>
                        <div class="form-text">URL-адрес, генерируется из названия</div>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Категория</label>
                        <select id="category_id"
                                name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">— Без категории —</option>
                            @foreach(\App\Models\Category::orderBy('title')->get() as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                    {{ $cat->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea id="description"
                                  name="description"
                                  rows="5"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="mb-3">
                        <label for="price" class="form-label">Цена</label>
                        <input type="number"
                               id="price"
                               name="price"
                               value="{{ old('price') }}"
                               class="form-control @error('price') is-invalid @enderror"
                               min="0"
                               step="0.01"
                               required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Количество</label>
                        <input type="number"
                               id="quantity"
                               name="quantity"
                               value="{{ old('quantity', 0) }}"
                               class="form-control @error('quantity') is-invalid @enderror"
                               min="0"
                               required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox"
                               id="active"
                               name="active"
                               value="1"
                               class="form-check-input"
                               @checked(old('active', true))>
                        <label for="active" class="form-check-label">Активен</label>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Изображение</label>
                        <input type="file"
                               id="image"
                               name="image"
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                Создать товар
            </button>

            <a href="{{ backpack_url('products') }}" class="btn btn-secondary">
                Отмена
            </a>

        </form>

    </div>
@endsection
