@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Просмотр товара</h1>

            <a href="{{ backpack_url('products') }}"
               class="btn btn-secondary">
                Назад
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">ID</div>
                            <div class="col-md-9">{{ $entry->id }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Название</div>
                            <div class="col-md-9">{{ $entry->title }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Slug</div>
                            <div class="col-md-9">{{ $entry->slug }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Категория</div>
                            <div class="col-md-9">{{ $entry->category?->title ?? '—' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Цена</div>
                            <div class="col-md-9">{{ number_format($entry->price, 2) }} ₽</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Количество</div>
                            <div class="col-md-9">{{ $entry->quantity }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Статус</div>
                            <div class="col-md-9">
                                @if($entry->active)
                                    <span class="badge bg-success">Активен</span>
                                @else
                                    <span class="badge bg-secondary">Неактивен</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Описание</div>
                            <div class="col-md-9">{{ $entry->description ?? '—' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Создан</div>
                            <div class="col-md-9">{{ $entry->created_at->format('d.m.Y H:i') }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 fw-bold">Обновлён</div>
                            <div class="col-md-9">{{ $entry->updated_at->format('d.m.Y H:i') }}</div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Изображение</h5>
                        @if($entry->image)
                            <img src="{{ asset(Storage::disk('public')->url($entry->image)) }}"
                                 alt="img"
                                 class="rounded mb-3"
                                 width="200"
                                 height="200"
                                 style="object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 200px; height: 200px; font-size: 3rem; color: #ccc;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            @if($crud->hasAccess('update'))
                <a href="{{ backpack_url('products/'.$entry->getKey().'/edit') }}" class="btn btn-warning">
                    Изменить
                </a>
            @endif

            @if($crud->hasAccess('delete'))
                <form action="{{ backpack_url('products/'.$entry->getKey()) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Удалить товар?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Удалить
                    </button>
                </form>
            @endif
        </div>

    </div>
@endsection
