@extends('layouts.main')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Создать пользователя</h1>

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
                <form action="{{ backpack_url('users') }}" method="POST">
                    @csrf

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
                                       value="{{ old($field['name']) }}">
                            @else
                                <input type="text"
                                       class="form-control @error($field['name']) is-invalid @enderror"
                                       id="{{ $field['name'] }}"
                                       name="{{ $field['name'] }}"
                                       value="{{ old($field['name']) }}">
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
                        Создать
                    </button>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Аватар</h5>
                        <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                             style="width: 120px; height: 120px; font-weight: 600; font-size: 3rem;">
                            ?
                        </div>
                        <p class="text-muted small mt-2">Появится после создания</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
