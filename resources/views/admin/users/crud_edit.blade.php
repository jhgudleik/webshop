@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Редактировать пользователя</h1>

            <a href="{{ backpack_url('users') }}" class="btn btn-secondary">
                ← Назад
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

        <div class="row">
            <div class="col-md-8">
                <form action="{{ backpack_url('users/'.$entry->getKey()) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @foreach($crud->fields() as $field)
                        <div class="mb-3">
                            <label for="{{ $field['name'] }}" class="form-label">
                                {{ $field['label'] ?? $field['name'] }}
                            </label>

                            @if(($field['type'] ?? 'text') === 'password')
                                <input type="password"
                                       class="form-control @error($field['name']) is-invalid @enderror"
                                       id="{{ $field['name'] }}"
                                       name="{{ $field['name'] }}">
                            @elseif(($field['type'] ?? 'text') === 'email')
                                <input type="email"
                                       class="form-control @error($field['name']) is-invalid @enderror"
                                       id="{{ $field['name'] }}"
                                       name="{{ $field['name'] }}"
                                       value="{{ old($field['name'], $entry->{$field['name']} ?? '') }}">
                            @else
                                <input type="text"
                                       class="form-control @error($field['name']) is-invalid @enderror"
                                       id="{{ $field['name'] }}"
                                       name="{{ $field['name'] }}"
                                       value="{{ old($field['name'], $entry->{$field['name']} ?? '') }}">
                            @endif

                            @if($field['hint'] ?? false)
                                <div class="form-text">{{ $field['hint'] }}</div>
                            @endif

                            @error($field['name'])
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">
                        Сохранить
                    </button>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Аватар</h5>
                        @if($entry->avatar)
                            <img src="{{ asset('storage/' . $entry->avatar) }}"
                                 alt="avatar"
                                 class="rounded-circle mb-3"
                                 width="120"
                                 height="120"
                                 style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 120px; height: 120px; font-weight: 600; font-size: 3rem;">
                                {{ mb_strtoupper(mb_substr($entry->name, 0, 1)) }}
                            </div>
                        @endif
                        <p class="text-muted small">{{ $entry->email }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
