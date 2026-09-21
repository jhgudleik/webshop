@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Товары</h1>

            <a href="{{ backpack_url('products/create') }}"
               class="btn btn-primary">
                Создать товар
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @php
            $entries = \App\Models\Product::with('category')->orderBy('id')->paginate(20);
        @endphp

        @if($entries->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Кол-во</th>
                        <th>Статус</th>
                        <th>Изображение</th>
                        <th width="300">Действия</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry->id }}</td>
                            <td>{{ $entry->title }}</td>
                            <td>{{ $entry->category?->title ?? '—' }}</td>
                            <td>{{ number_format($entry->price, 2) }} ₽</td>
                            <td>{{ $entry->quantity }}</td>
                            <td>
                                @if($entry->active)
                                    <span class="badge bg-success">Активен</span>
                                @else
                                    <span class="badge bg-secondary">Неактивен</span>
                                @endif
                            </td>
                            <td>
                                @if($entry->image)
                                    <img src="{{ asset(Storage::disk('public')->url($entry->image)) }}"
                                         alt="img"
                                         class="rounded"
                                         width="50"
                                         height="50"
                                         style="object-fit: cover;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($crud->hasAccess('show'))
                                    <a href="{{ backpack_url('products/'.$entry->getKey().'/show') }}"
                                       class="btn btn-sm btn-info">
                                        Просмотр
                                    </a>
                                @endif

                                @if($crud->hasAccess('update'))
                                    <a href="{{ backpack_url('products/'.$entry->getKey().'/edit') }}"
                                       class="btn btn-sm btn-warning">
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
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Удалить
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $entries->withQueryString()->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Товаров пока нет.
            </div>
        @endif

    </div>
@endsection
