@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Просмотр пользователя</h1>

            <a href="{{ backpack_url('users') }}" class="btn btn-secondary">
                ← Назад
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">ID</div>
                    <div class="col-md-9">{{ $entry->id }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Имя</div>
                    <div class="col-md-9">{{ $entry->name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Email</div>
                    <div class="col-md-9">{{ $entry->email }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Аватар</div>
                    <div class="col-md-9">
                        @if($entry->avatar)
                            <img src="{{ asset('storage/' . $entry->avatar) }}"
                                 alt="avatar"
                                 class="rounded-circle"
                                 width="80"
                                 height="80"
                                 style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; font-weight: 600; font-size: 2rem;">
                                {{ mb_strtoupper(mb_substr($entry->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
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

        <div class="mt-3 d-flex gap-2">
            @if($crud->hasAccess('update'))
                <a href="{{ backpack_url('users/'.$entry->getKey().'/edit') }}" class="btn btn-warning">
                    Изменить
                </a>
            @endif

            @if($crud->hasAccess('delete'))
                <form action="{{ backpack_url('users/'.$entry->getKey()) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Удалить пользователя?');">
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
